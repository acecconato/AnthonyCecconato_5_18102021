<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Contact;
use Blog\Entity\Post;
use Blog\Form\FormHandler;
use Blog\Repository\PostRepository;
use Blog\Router\Exceptions\ResourceNotFound;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Router;
use Blog\Service\Paginator;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

class FrontController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RouteNotFoundException
     * @throws Exception
     */
    public function index(
        FormHandler $formHandler,
        Request $request,
        PostRepository $postRepository,
        Paginator $paginator,
        Router $router,
        MailerInterface $mailer
    ): Response {
        $page = (int)$request->query->get('page', 0);
        $nbItem = $postRepository->countAll();
        $pDatas = $paginator->getPaginatingDatas($page, $nbItem, 6);

        if ($page > $pDatas['pagesCount']) {
            return $this->redirect($router->generateUri('home'));
        }

        $posts = $postRepository->getPostsWithUsers($pDatas['offset'], $pDatas['maxPerPage']);

        $contact = new Contact();

        $form = $formHandler->loadFromRequest($request, $contact);

        $username = $form->get('name');
        $userEmail = $form->get('email');
        $message = $form->get('message');

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($_ENV['MAILER_SENDER'])
                ->to($userEmail)
                ->priority(Email::PRIORITY_NORMAL)
                ->subject('Vous avez un nouveau message!')
                ->text(strip_tags($message))
                ->html(nl2br($message));

            /** @var Session $session */
            $session = $request->getSession();

            try {
                $mailer->send($email);
                $session->getFlashBag()->add('success', "Message envoyé ! Nous reviendrons vers vous au plus vite");
                $form->clear();
            } catch (Throwable $exception) {
                $session->getFlashBag()->add('danger', "Impossible d'envoyer le message");
            }
        }

        return $this->render(
            'pages/front/home.html.twig',
            [
                'posts' => $posts,
                'pages' => $pDatas['pagesCount'],
                'page' => $page,
                'pagination_range' => $pDatas['range'],
                'errors' => $form->getErrors(),
                'form' => $form
            ]
        );
    }

    public function about(): Response
    {
        return $this->render('pages/front/about.html.twig');
    }

    public function contact(): Response
    {
        return $this->render('pages/front/contact.html.twig');
    }

    /**
     * @throws ReflectionException
     * @throws ResourceNotFound
     */
    public function showSinglePost(string $slug, PostRepository $postRepository): Response
    {
        /** @var ?Post $post */
        $post = $postRepository->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw new ResourceNotFound("Publication '$slug' introuvable");
        }

        $postRepository->loadUser($post);

        return $this->render('pages/front/post.html.twig', ['post' => $post]);
    }
}

<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Comment;
use Blog\Entity\Contact;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Form\FormHandler;
use Blog\Repository\CommentRepository;
use Blog\Repository\PostRepository;
use Blog\Repository\UserRepository;
use Blog\Router\Exceptions\ResourceNotFound;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Router;
use Blog\Security\Authenticator;
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
                ->subject("Vous avez un nouveau message de la part de $username")
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
                'form' => $form
            ]
        );
    }

    public function about(): Response
    {
        return $this->render('pages/front/about.html.twig');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ResourceNotFound
     */
    public function showSinglePost(
        string $slug,
        PostRepository $postRepository,
        CommentRepository $commentRepository,
        UserRepository $userRepository,
        FormHandler $formHandler,
        Request $request,
        Authenticator $auth
    ): Response {
        /** @var ?Post $post */
        $post = $postRepository->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw new ResourceNotFound("Publication '$slug' introuvable");
        }

        /** @var User $user */
        $user = $userRepository->find($post->getUserId());
        $post->setUser($user);

        /** @var Comment[] $comments */
        $comments = $commentRepository->findAllBy(['post_id' => $post->getId(), 'enabled' => 1]);
        $post->setComments($comments);

        foreach ($comments as $comment) {
            /** @var User $commentAuthor */
            $commentAuthor = $userRepository->find($comment->getUserId());
            $comment->setUser($commentAuthor);
        }

        $comment = new Comment();
        $form = $formHandler->loadFromRequest($request, $comment);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $postRepository->getEntityManager();
            $em->add($comment);
            $em->flush();

            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('success', "Votre commentaire est en attente de validation");

            return $this->redirect($request->getRequestUri());
        }

        return $this->render('pages/front/post.html.twig', ['post' => $post]);
    }
}

<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Contact;
use Blog\Form\FormHandler;
use Blog\Repository\PostRepository;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Router;
use Blog\Service\Paginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class FrontController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RouteNotFoundException
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function index(
        FormHandler $formHandler,
        Request $request,
        PostRepository $postRepository,
        Paginator $paginator,
        Router $router
    ): Response {
        // Pagination
        $page = (int)$request->query->get('page', 0);
        $nbItem = $postRepository->countAll();
        $pDatas = $paginator->getPaginatingDatas($page, $nbItem, 6);

        if ($page > $pDatas['pagesCount']) {
            return $this->redirect($router->generateUri('home'));
        }

        $posts = $postRepository->getPostsWithUsers($pDatas['offset'], $pDatas['maxPerPage']);

        $contact = new Contact();

        $form = $formHandler->loadFromRequest($request, $contact);

        // @todo autowiring
        $transport = Transport::fromDsn('smtp://localhost:1025');
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        die("ok");


        if ($form->isSubmitted() && $form->isValid()) {
            // todo send mail and display a flash message
        }

        return $this->render(
            'pages/front/home.html.twig',
            [
                'posts' => $posts,
                'pages' => $pDatas['pagesCount'],
                'page' => $page,
                'pagination_range' => $pDatas['range']
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

    public function showSinglePost(): Response
    {
        return $this->render('pages/front/post.html.twig');
    }
}

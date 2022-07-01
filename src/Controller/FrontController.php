<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Contact;
use Blog\Entity\Post;
use Blog\Form\FormHandler;
use Blog\Repository\PostRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(FormHandler $formHandler, Request $request, PostRepository $postRepository): Response
    {
        $page = (int)$request->query->get('page', 0);
        $nbPerPage = 6;
        $totalPage = (int)ceil($postRepository->countAll() / $nbPerPage);
        $offset = $page * $nbPerPage;

        $posts = $postRepository->getPostsWithUsers($offset, $nbPerPage);

        $contact = new Contact();

        $form = $formHandler->loadFromRequest($request, $contact);

        if ($form->isSubmitted() && $form->isValid()) {
            // todo send mail and display a flash message
        }

        return $this->render('pages/front/home.html.twig', [
            'posts' => $posts,
            'totalPage' => $totalPage,
            'page' => $page
        ]);
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

<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Post;
use Blog\Form\FormHandler;
use Blog\Repository\CommentRepository;
use Blog\Repository\PostRepository;
use Blog\Repository\UserRepository;
use Blog\Security\Authenticator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function index(
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        PostRepository $postRepository,
        Authenticator $auth
    ): Response {
        if (!$auth->isAdmin()) {
            return $this->redirect('/');
        }

        return $this->render('pages/back/index.html.twig', [
            'nbUsers' => $userRepository->countAll(),
            'nbDisabledUsers' => $userRepository->countUserAwaitingValidation(),
            'nbComments' => $commentRepository->countAwaitingValidation(),
            'nbPosts' => $postRepository->countAll()
        ]);
    }

    public function showPosts(): Response
    {
        return $this->render('pages/back/posts.html.twig');
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createPost(
        Request $request,
        FormHandler $formHandler
    ): Response {
        $post = new Post();
        $form = $formHandler->loadFromRequest($request, $post);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($post);
        }

        return $this->render('pages/back/form_post.html.twig', ['form' => $form, 'errors' => $form->getErrors()]);
    }

    public function updatePost(int $id): Response
    {
        $post = new Post();
        return $this->render('pages/back/form_post.html.twig', ['post' => $post]);
    }

    public function showComments(): Response
    {
        return $this->render('pages/back/posts.html.twig');
    }

    public function createComment(Request $request): Response
    {
        return $this->render('pages/back/form_post.html.twig');
    }

    public function updateComment(int $id): Response
    {
        $comment = new Post();
        return $this->render('pages/back/form_post.html.twig', ['comment' => $comment]);
    }

    public function showUsers(): Response
    {
        return $this->render('pages/back/form_post.html.twig');
    }
}

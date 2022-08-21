<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Post;
use Blog\Form\FormHandler;
use Blog\Repository\CommentRepository;
use Blog\Repository\PostRepository;
use Blog\Repository\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    /**
     * @throws Exception\UnauthorizedException
     */
    public function index(
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        PostRepository $postRepository,
    ): Response {
        $this->denyAccessUnlessIsAdmin();

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
     * @throws Exception\UnauthorizedException
     */
    public function createPost(
        Request $request,
        FormHandler $formHandler
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        $post = new Post();
        $form = $formHandler->loadFromRequest($request, $post);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($post);
        }

        return $this->render('pages/back/form_post.html.twig', ['form' => $form]);
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function updatePost(int $id): Response
    {
        $this->denyAccessUnlessIsAdmin();
        $post = new Post();
        return $this->render('pages/back/form_post.html.twig', ['post' => $post]);
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function showComments(): Response
    {
        $this->denyAccessUnlessIsAdmin();
        return $this->render('pages/back/posts.html.twig');
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function createComment(Request $request): Response
    {
        $this->denyAccessUnlessIsAdmin();
        return $this->render('pages/back/form_post.html.twig');
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function updateComment(int $id): Response
    {
        $this->denyAccessUnlessIsAdmin();
        $comment = new Post();
        return $this->render('pages/back/form_post.html.twig', ['comment' => $comment]);
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function showUsers(): Response
    {
        $this->denyAccessUnlessIsAdmin();
        return $this->render('pages/back/form_post.html.twig');
    }
}

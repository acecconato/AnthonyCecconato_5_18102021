<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('pages/back/index.html.twig');
    }

    public function showPosts(): Response
    {
        return $this->render('pages/back/posts.html.twig');
    }

    public function createPost(Request $request): Response
    {
        return $this->render('pages/back/form_post.html.twig');
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
}
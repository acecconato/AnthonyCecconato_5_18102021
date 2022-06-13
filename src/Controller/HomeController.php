<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Post;
use Blog\Form\FormHandler;
use Blog\ORM\EntityManager;
use Blog\Service\FileUploader;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(
        FormHandler $formHandler,
        Request $request,
        EntityManager $em,
        FileUploader $uploader
    ): Response {
        $post = new Post();
        $form = $formHandler->loadFromRequest($request, $post);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getFile()) {
                $filename = $uploader->upload($post->getFile());
                $post->setFilename($filename);
            }

            $em->add($post);
            $em->flush();
        }

        return $this->render('pages/front/home.html.twig');
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

    public function login(): Response
    {
        return $this->render('pages/front/login.html.twig');
    }

    public function resetPassword(): Response
    {
        return $this->render('pages/front/reset_password.html.twig');
    }
}

<?php

use Blog\Controller\DashboardController;
use Blog\Controller\FrontController;
use Blog\Controller\SecurityController;
use Blog\Router\Route;
use Blog\Router\RouterInterface;

return function (RouterInterface $router) {
    $router
        ->add(new Route('home', '/', [FrontController::class, 'index']))
        ->add(new Route('about', '/a-propos-de-anthony-cecconato', [FrontController::class, 'about']))
        ->add(new Route('contact', '/contactez-moi', [FrontController::class, 'contact']))
        ->add(new Route('post', '/articles/{slug:[a-zA-Z0-9-_]+}', [FrontController::class, 'showSinglePost']))

        ->add(new Route('login', '/connexion', [SecurityController::class, 'login']))
        ->add(new Route('reset_password', '/mot-de-passe-oublie', [SecurityController::class, 'resetPassword']))
        ->add(new Route('logout', '/deconnexion', [SecurityController::class, 'logout']))

        ->add(new Route('admin_dashboard', '/dashboard', [DashboardController::class, 'index']))
        ->add(new Route('admin_articles', '/dashboard/articles', [DashboardController::class, 'showPosts']))
        ->add(new Route('admin_create_post', '/dashboard/articles/ajouter', [DashboardController::class, 'createPost']))
        ->add(new Route('admin_update_post', '/dashboard/articles/{id:\d+}/modifier', [DashboardController::class, 'updatePost']))

        ->add(new Route('admin_comments', '/dashboard/commentaires', [DashboardController::class, 'showComments']))
        ->add(new Route('admin_create_comment', '/dashboard/commentaires/ajouter', [DashboardController::class, 'createComment']))
        ->add(new Route('admin_update_comment', '/dashboard/commentaires/{id:\d+}/modifier', [DashboardController::class, 'updateComment']));
};

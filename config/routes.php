<?php

use Blog\Controller\HomeController;
use Blog\Router\Route;
use Blog\Router\RouterInterface;

return function( RouterInterface $router) {
	$router
        ->add(new Route('home', '/', [HomeController::class, 'index']))
	    ->add(new Route('about', '/a-propos-de-anthony-cecconato', [HomeController::class, 'about']))
	    ->add(new Route('contact', '/contactez-moi', [HomeController::class, 'contact']))
	    ->add(new Route('single-post', '/article', [HomeController::class, 'showSinglePost']));
};

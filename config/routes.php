<?php

use Blog\Controller\HomeController;
use Blog\Router\Route;
use Blog\Router\RouterInterface;

return function( RouterInterface $router) {
	$router->add(
		new Route('home', '/', [HomeController::class, 'index']),
	);
};

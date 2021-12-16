<?php

use Blog\Controller\HomeController;
use Blog\Router\Route;

return [
    new Route('home', '/', [HomeController::class, 'showHome']),
];

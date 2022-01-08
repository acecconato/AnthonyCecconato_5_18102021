<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Router\Router;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
//        dd($this->container->get(Router::class));
        // TODO Q/A La par exemple j'aimerai recupérer la même instance de mon router contenant mes routes pour générer une url et faire un redirect
//        return $this->redirect('');
        return $this->raw('Hello World!');
    }
}

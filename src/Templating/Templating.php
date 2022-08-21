<?php

declare(strict_types=1);

namespace Blog\Templating;

use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Router;
use Blog\Security\Authenticator;
use Blog\Twig\CsrfTokenExtension;
use Blog\Twig\PathExtension;
use Blog\Twig\SecureFilter;
use Lcharette\WebpackEncoreTwig\EntrypointsTwigExtension;
use Lcharette\WebpackEncoreTwig\TagRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class Templating implements TemplatingInterface
{
    private Environment $twig;

    private bool $isDevMode;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private readonly string $cacheDir,
        private readonly string $templatesDirs,
        private readonly ContainerInterface $container,
        private readonly Request $request,
        private readonly Authenticator $auth
    ) {
        $loader = new FilesystemLoader($this->templatesDirs);

        $this->isDevMode = $_ENV['APP_ENV'] === 'development';

        $this->twig = new Environment($loader, [
            'cache' => sprintf('%s/twig', $this->cacheDir),
            'debug' => $this->isDevMode,
            'auto_reload' => $this->isDevMode,
            'strict_variables' => $this->isDevMode,
        ]);

        $this->twig->addExtension($this->getWebpackEncoreExtension());
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new CsrfTokenExtension($this->request->getSession()));
        $this->twig->addExtension(new PathExtension($this->container->get(Router::class)));
        $this->twig->addExtension(new IntlExtension());

        $this->twig->addExtension(new SecureFilter());
    }

    /**
     * @param array<mixed> $context
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $view, array $context = [], bool $mainRequest = true): string
    {
        if ($this->isDevMode) {
            $context = [
                ...$context,
                'isDevMode' => true,
                'backtrace' => debug_backtrace()
            ];
        }

        /** @var Session $session */
        $session = $this->request->getSession();

        $context = [
            ...$context,
            'app' => [
                'flashes' => ($mainRequest) ? $session->getFlashBag()->all() : $session->getFlashBag()->peekAll(),
                'request_uri' => $this->request->getRequestUri()
            ],

            'auth' => [
                'isLoggedIn' => $session->get('isLoggedIn'),
                'user' => $this->auth->getUserDatas()
            ]
        ];

        return $this->twig->render($view, $context);
    }

    /**
     * @return EntrypointsTwigExtension
     */
    private function getWebpackEncoreExtension(): EntrypointsTwigExtension
    {
        $entryPoints = new EntrypointLookup(dirname(__DIR__) . '/../public/build/entrypoints.json');
        $tagRenderer = new TagRenderer($entryPoints);
        return new EntrypointsTwigExtension($entryPoints, $tagRenderer);
    }
}

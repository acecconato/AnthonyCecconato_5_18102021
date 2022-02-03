<?php

declare(strict_types=1);

namespace Blog\Templating;

use JetBrains\PhpStorm\Pure;
use Lcharette\WebpackEncoreTwig\EntrypointsTwigExtension;
use Lcharette\WebpackEncoreTwig\TagRenderer;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Templating implements TemplatingInterface
{
    private Environment $twig;

    /**
     * @param string $cacheDir
     * @param string $templatesDirs
     */
    public function __construct(
        private string $cacheDir,
        private string $templatesDirs
    ) {
        $loader = new FilesystemLoader($this->templatesDirs);

        $isDevMode = $_ENV['APP_ENV'] === 'development';

        $this->twig = new Environment($loader, [
            'cache' => sprintf('%s/twig', $this->cacheDir),
            'debug' => (bool)$_ENV['TWIG_DEBUG'],
            'auto_reload' => $isDevMode,
            'strict_variables' => $isDevMode
        ]);

        $this->twig->addExtension($this->getWebpackEncoreExtension());
    }

    /**
     * @param string $view
     * @param array<mixed> $context
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $view, array $context = []): string
    {
        return $this->twig->render($view, $context);
    }

    /**
     * @return EntrypointsTwigExtension
     */
    #[Pure] private function getWebpackEncoreExtension(): EntrypointsTwigExtension
    {
        $entryPoints = new EntrypointLookup(dirname(__DIR__) . '/../public/build/entrypoints.json');
        $tagRenderer = new TagRenderer($entryPoints);
        return new EntrypointsTwigExtension($entryPoints, $tagRenderer);
    }
}
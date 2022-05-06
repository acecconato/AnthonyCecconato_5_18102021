<?php

declare(strict_types=1);

namespace Blog\Templating;

use Blog\Twig\CsrfTokenExtension;
use Lcharette\WebpackEncoreTwig\EntrypointsTwigExtension;
use Lcharette\WebpackEncoreTwig\TagRenderer;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Templating implements TemplatingInterface
{
    private Environment $twig;

    private bool $isDevMode;

    /**
     * @param string $cacheDir
     * @param string $templatesDirs
     */
    public function __construct(
        private string $cacheDir,
        private string $templatesDirs
    ) {
        $loader = new FilesystemLoader($this->templatesDirs);

        $this->isDevMode = $_ENV['APP_ENV'] === 'development';

        $this->twig = new Environment($loader, [
            'cache' => sprintf('%s/twig', $this->cacheDir),
            'debug' => $this->isDevMode,
            'auto_reload' => $this->isDevMode,
            'strict_variables' => $this->isDevMode
        ]);

        $this->twig->addExtension($this->getWebpackEncoreExtension());
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new CsrfTokenExtension());
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
        if ($this->isDevMode) {
            $context = [...$context, 'isDevMode' => true, 'backtrace' => debug_backtrace()];
        }

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
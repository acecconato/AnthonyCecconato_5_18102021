<?php

declare(strict_types=1);

namespace Blog\Router;

use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Route
 *
 * @package Blog\Router
 */
final class Route implements RouteInterface
{
    /**
     * Route constructor.
     *
     * @param string       $name
     * @param string       $path
     * @param array<mixed> $callable $callable
     */
    public function __construct(
        private string $name,
        private string $path,
        private array $callable
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Test if the path match a route by transforming the path to a regex
     * E.g /users/{id[\d+]} become /^\/users\/(.+)$/
     *
     * @param string $path
     *
     * @return bool
     */
    public function test(string $path): bool
    {
        $pattern = $this->path;

        /* Extracts all route's variables, it should return something like:
            array(2) {
              [0] =>
              string(10) "{id:[\d+]}"
              [1] =>
              string(12) "{slug}"
            }
        */
        preg_match_all('/{[^}]*}/', $pattern, $routeVars);
        $routeVars = array_shift($routeVars);

        /* Check if there is one or many requiremenst. If yes, we return them, otherwise we return the
        * (.+) regex to match all characters/digits.
         *
         * e.g for the /users/{id:[\d+]}/{slug} route:
         * array(2) {
              [0] =>
              string(5) "[\d+]" // There is one requirement, we only want digits as id!
              [1] =>
              string(4) "(.+)" // No requirements, we are looking for anything
            }
         */
        $routeVars = array_map(
            function ($routeVar) {
                $routeVar = preg_replace('/([{}])/', '', $routeVar);
                $routeVar = explode(':', $routeVar);

                return $routeVar[1] ?? '(?:(?!/).)+'; // (?:(?!\/).)+
            },
            $routeVars
        );

        $explodedPattern = explode('/', $pattern);

        // Replace route's variables by regex
        $routeVarsIndex = 0;
        foreach ($routeVars as $routeVar) {
            foreach ($explodedPattern as $index => $item) {
                if (preg_match('/{.+}/', $item)) {
                    $explodedPattern[$index] = $routeVars[$routeVarsIndex];
                    $routeVarsIndex++;
                }
            }
        }

        $pattern = implode('/', $explodedPattern);

        // Add a \ after all / to prepare the regex
        $pattern = str_replace('/', '\/', $pattern);

        // Add ^ and $ delimiters
        $pattern = sprintf('/^%s$/', $pattern);

        return (bool)preg_match($pattern, $path);
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
       return [new $this->callable[0], $this->callable[1]];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws ReflectionException
     */
    public function getArgs(Request $request): array
    {
        $pattern = str_replace('/', '\/', $this->path);
        $pattern = sprintf('/^%s$/', $pattern);
        $pattern = (string)preg_replace('/{([^}]*)}/', '(.+)', $pattern);

        // Get path's variables values
        preg_match($pattern, $request->getRequestUri(), $matches);

        // Get path's variables name
        preg_match_all('/{([^}]*)}/', $this->path, $paramMatches);

        array_shift($matches);

        $parameters = $paramMatches[1];

        $argsValue = [];

        if (count($parameters) > 0) {
            // Remove requirement parts from the parameters
            $parameters = array_map(
                function ($param) {
                    if (str_contains($param, ':')) {
                        return substr($param, 0, (int)strpos($param, ':'));
                    }

                    return $param;
                },
                $parameters
            );

            $parameters = array_combine($parameters, $matches);
        }

        $class = $this->callable[0];
        $method = $this->callable[1];

        $reflMethod = new ReflectionMethod($class, $method);

        $expectedParameters = $reflMethod->getParameters();

        foreach ($expectedParameters as $param) {
            if (array_key_exists($param->getName(), $parameters)) {
                $argsValue[$param->getName()] = $parameters[$param->getName()];
            }

            $type = (string)$param->getType();

            // Auto-inject request if required
            if ($type === Request::class) {
                $argsValue['request'] = $request;
            }
        }

        return $argsValue;
    }
}

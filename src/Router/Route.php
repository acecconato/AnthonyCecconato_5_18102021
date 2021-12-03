<?php

declare(strict_types=1);

namespace Blog\Router;

use ReflectionClass;
use ReflectionException;

/**
 * Class Route
 * @package Blog\Routerd
 */
final class Route implements RouteInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var array|callable
     */
    private $callable;

    /**
     * Route constructor.
     *
     * @param string $name
     * @param string $path
     * @param array|callable $callable
     */
    public function __construct(string $name, string $path, callable|array $callable)
    {
        $this->name     = $name;
        $this->path     = $path;
        $this->callable = $callable;
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

        /* Extracts all route's variables, it should returns something like:
            array(2) {
              [0] =>
              string(10) "{id:[\d+]}"
              [1] =>
              string(12) "{slug}"
            }
        */
        preg_match_all('/{[^}]*}/i', $pattern, $routeVars);
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

                return $routeVar[1] ?? '(.+)';
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
     * Call a callable and retrieve the proper variables to send them through it
     *
     * @param string $path
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function call(string $path): mixed
    {
        $pattern = str_replace('/', '\/', $this->path);
        $pattern = sprintf('/^%s$/', $pattern);
        $pattern = preg_replace('/({\w+})/', '(.+)', $pattern);

        // Get path's variables values
        preg_match($pattern, $path, $matches);

        // Get path's variables name
        preg_match_all('/{(\w+)}/', $this->path, $paramMatches);

        array_shift($matches);

        $parameters = $paramMatches[1];

        $argsValue = [];

        if (count($parameters) > 0) {
            $parameters = array_combine($parameters, $matches);

            $reflFunc = (new ReflectionClass($this->callable[0]))->getMethod($this->callable[1]);

            $args = array_map(fn(\ReflectionParameter $param) => $param->getName(), $reflFunc->getParameters());

            $argsValue = array_map(
                function (string $name) use ($parameters) {
                    return $parameters[$name];
                },
                $args
            );
        }

        return call_user_func_array([new $this->callable[0], $this->callable[1]], $argsValue);
    }
}

<?php

declare(strict_types=1);

namespace Blog\Router;

use ReflectionClass;
use ReflectionException;
use ReflectionFunction;

class Route
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
     * @param string $path
     *
     * @return bool
     */
    public function test(string $path): bool
    {
        $pattern = str_replace('/', '\/', $this->path);
        $pattern = sprintf('/^%s$/', $pattern);
        $pattern = preg_replace('/({.+})/', '(.+)', $pattern);

        return (bool)preg_match($pattern, $path);
    }

    /**
     * @param string $path
     *
     * @return false|mixed
     * @throws ReflectionException
     */
    public function call(string $path)
    {
        $pattern = str_replace('/', '\/', $this->path);
        $pattern = sprintf('/^%s$/', $pattern);
        $pattern = preg_replace('/({\w+})/', '(.+)', $pattern);
        preg_match($pattern, $path, $matches);

        preg_match_all('/{(\w+)}/', $this->path, $paramMatches);

        array_shift($matches);

        $parameters = $paramMatches[1];

        $argsValue = [];

        if (count($parameters) > 0) {
            $parameters = array_combine($parameters, $matches);

            if (is_array($this->callable)) {
                $reflFunc = (new ReflectionClass($this->callable[0]))->getMethod($this->callable[1]);

            } else {
                $reflFunc = new ReflectionFunction($this->callable);

            }

            $args = array_map(fn(\ReflectionParameter $param) => $param->getName(), $reflFunc->getParameters());

            $argsValue = array_map(
                function (string $name) use ($parameters) {
                    return $parameters[$name];
                },
                $args
            );
        }

        $callable = $this->callable;

        if (is_array($callable)) {
            $callable = [new $callable[0](), $callable[1]];
        }

        return call_user_func_array($callable, $argsValue);
    }
}

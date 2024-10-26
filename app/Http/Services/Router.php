<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Route;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Router
{

    /**
     * @throws ReflectionException
     */
    public function __construct()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        $request_uri = $_SERVER['REQUEST_URI'];

        $classes = $this->getAllControllerClasses();

        foreach ($classes as $class) {
            $methods = $this->getPublicMethods($class);
            foreach ($methods as $method) {
                $methodAttributes = $method->getAttributes(Route::class);
                foreach ($methodAttributes as $attr) {
                    $args = $attr->getArguments();
                    $route_path = $args[0];

                    $route_pattern = $this->getRoutePattern($route_path);

                    //dd($route_pattern);

                    if (preg_match($route_pattern, $request_uri, $matches)) {

                        echo "Matched! ID: " . $matches['id'];
                    } else {
                       continue;
                    }


                    dd($matches);

                    if ($matched and $request_method == $args[1]) {
                        $route = $attr->newInstance();
                        $controller = new $class();
                        $controller->{$method->getName()}();
                        return;
                    }
                }


                abort(404, 'not found');
            }
        }

    }

    /**
     * @throws ReflectionException
     */
    private function getClassesWithAttribute(array $classes): array
    {
        $result = [];
        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            if ($reflectionClass->getAttributes(Controller::class)) {
                $result[] = $class;
            }
        }
        return $result;
    }

    private function getAllControllerClasses(): array
    {

        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path(config('app.controller_directory'))));
        $classes = [];
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }
            $class = str_replace(base_path(config('app.controller_directory')) . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $class = str_replace('.php', '', $class);
            $namespace = ucfirst(config('app.controller_directory'));
            $class = $namespace . DIRECTORY_SEPARATOR . $class;
            $class = str_replace(DIRECTORY_SEPARATOR, '\\', $class);

            if (class_exists($class)) {
                $classes[] = $class;
            }
        }
        $classes = $this->getClassesWithAttribute($classes);
        return $classes;

    }

    /**
     * @param mixed $class
     * @return ReflectionMethod[]
     * @throws ReflectionException
     */
    public function getPublicMethods(mixed $class): array
    {
        $reflection_class = new ReflectionClass($class);
        return $reflection_class->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    private function getRoutePattern(mixed $route_path): string
    {
        // Replace dynamic segments with regex patterns
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route_path); // Required parameters
        $pattern = preg_replace('/\{(\w+)\?\}/', '(?P<$1>[^/]*)?', $pattern); // Optional parameters

        // Add start and end anchors
        return '#^' . $pattern . '$#';
    }

}
<?php

namespace GrizzIt\MicroRouter\Http;

use Exception;
use GrizzIt\MicroService\Service\ServiceFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Throwable;

class Router
{
    private static array $routeFiles = [];

    private static array $routeFileNames = ['routes.yaml'];

    /**
     * Load routes from a file.
     *
     * @param string $path
     *
     * @return void
     */
    public static function loadRoutesFromFile(string $path): void
    {
        static::$routeFiles[] = $path;
    }

    /**
     * Run the router.
     *
     * @return Response
     *
     * @throws Exception
     */
    public static function run(): Response
    {
        try {
            try {
                $context = new RequestContext();
                $request = Request::createFromGlobals();
                $context->fromRequest($request);
                $locator = new FileLocator(static::$routeFiles);
                $yamlFileLoader = new YamlFileLoader($locator);
                $collection = null;
                foreach (static::$routeFileNames as $fileName) {
                    if (!$collection) {
                        $collection = $yamlFileLoader->load($fileName);
                        continue;
                    }

                    $collection->addCollection($yamlFileLoader->load($fileName));
                }

                $matcher = new UrlMatcher($collection, $context);
                $parameters = $matcher->match($context->getPathInfo());
                $request->request->add($parameters);
                [$controller, $method] = explode('::', $parameters['_controller']);

                return ServiceFactory::create($controller)->$method($request);
            } catch (ResourceNotFoundException $exception) {
                throw new Exception('Not Found', 404, $exception);
            }
        } catch (Throwable $exception) {
            return json_response(
                [
                    'error' => true,
                    'message' => $exception->getMessage()
                ],
                $exception->getCode()
            );
        }
    }
}
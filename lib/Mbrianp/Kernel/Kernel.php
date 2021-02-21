<?php

namespace Mbrianp\FuncCollection\Kernel;

use Mbrianp\FuncCollection\DIC\DIC;
use Mbrianp\FuncCollection\DIC\Service;
use Mbrianp\FuncCollection\Http\HttpDependenciesDefinition;
use Mbrianp\FuncCollection\Http\HttpParameterResolver;
use Mbrianp\FuncCollection\Http\Request;
use Mbrianp\FuncCollection\Http\Response;
use Mbrianp\FuncCollection\Logic\AbstractController;
use Mbrianp\FuncCollection\ORM\ORMDependenciesDefinition;
use Mbrianp\FuncCollection\ORM\ORMParameterResolver;
use Mbrianp\FuncCollection\Routing\Attribute\Route;
use Mbrianp\FuncCollection\Routing\Router;
use Mbrianp\FuncCollection\Routing\RouterParameterResolver;
use Mbrianp\FuncCollection\Routing\Routing;
use Mbrianp\FuncCollection\View\TemplateManager;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

class Kernel
{
    /** @var array<int, string> */
    protected array $parametersResolvers = [
        HttpParameterResolver::class,
        RouterParameterResolver::class,
        ORMParameterResolver::class,
    ];

    protected array $servicesDefinitions = [
        HttpDependenciesDefinition::class,
        ORMDependenciesDefinition::class,
    ];

    protected const NESTED_ROUTES_SEPARATOR = '_';

    protected DIC $dependenciesContainer;

    public function __construct(protected array $config, protected array $registeredControllers = [])
    {
        $this->initContainer();
    }

    protected function initContainer(): void
    {
        $this->dependenciesContainer = new DIC();

        foreach ($this->servicesDefinitions as $serviceDefinition) {
            $definition = new $serviceDefinition($this->dependenciesContainer, $this->config);
            $services = $definition->getServices();

            foreach ($services as $service) {
                $this->dependenciesContainer->addService($service);
            }
        }
    }

    /**
     * @return Router
     * @throws ReflectionException
     */
    protected function resolveRouterWithRoutes(): Router
    {
        $routes = [];

        foreach ($this->registeredControllers as $class) {
            if (class_exists($class)) {
                $prefix['name'] = null;
                $prefix['path'] = null;

                $rc = new ReflectionClass($class);

                if (1 == count($rc->getAttributes(Route::class))) {
                    /** @var Route $class_routeMetadata */
                    $class_routeMetadata = $rc->getAttributes(Route::class)[0]->newInstance();

                    $prefix['name'] = $class_routeMetadata->name;
                    $prefix['path'] = $class_routeMetadata->path;
                }

                foreach ($rc->getMethods() as $method) {
                    if (1 == count($method->getAttributes(Route::class))) {
                        /** @var Route $method_routeMetadata */
                        $method_routeMetadata = $method->getAttributes(Route::class)[0]->newInstance();

                        $method_routeMetadata->data['__controller'] = $rc->getName();
                        $method_routeMetadata->data['__method'] = $method->getName();

                        if ($prefix['name'] !== null)
                            $method_routeMetadata->name = $prefix['name'] . static::NESTED_ROUTES_SEPARATOR . $method_routeMetadata->name;

                        if ($prefix['path'] !== null)
                            $method_routeMetadata->path = $prefix['path'] . $method_routeMetadata->path;

                        $routes[] = $method_routeMetadata;
                    }
                }
            } else {
                throw new RuntimeException(\sprintf('Class %s does not exist', $class));
            }
        }

        return new Router($routes);
    }

    /**
     * @param array<int, ReflectionParameter> $parameters
     * @return array
     */
    protected function resolveParams(array $parameters): array
    {
        $resolvedParameters = [];

        foreach ($parameters as $parameter) {
            foreach ($this->parametersResolvers as $parameterResolver) {
                /**
                 * @var ParameterResolver $resolver
                 */
                $resolver = new $parameterResolver($this->dependenciesContainer);

                if ($resolver->supports($parameter)) {
                    $resolvedParameters[] = $resolver->resolve();
                }

                continue 1;
            }
        }

        return $resolvedParameters;
    }

    public function deployApp(Request $request): void
    {
        $router = $this->resolveRouterWithRoutes();

        // Render framework's pages like NotFound or Default Home Page
        $templateManager = new TemplateManager($this->dependenciesContainer,__DIR__ . '/templates');

        if (!$router->hasRoutes() && '/' == $request->path) {
            (new Response($templateManager->render('DefaultHomepage.html.php'), 200))->send();

            return;
        }

        $route = $router->resolveCurrentRoute($request);

        if (null == $route) {
            (new Response($templateManager->render('NotFound.html.php', ['path' => $request->path]), 404))->send();

            return;
        }

        $routingService = new Service('kernel.routing', Routing::class, [$route, $router->routes]);
        $this->dependenciesContainer->addService($routingService);

        ['__controller' => $controller, '__method' => $method] = $route->data;

        $rm = new ReflectionMethod($controller, $method);
        $params = $this->resolveParams($rm->getParameters());
        $constructorParams = [];

        if (\in_array(AbstractController::class, \class_parents($controller))) {
            $constructorParams[] = $this->config['templates_dir'];
            $constructorParams[] = $this->dependenciesContainer;
        }

        $controller = new $controller(...$constructorParams);
        $response = $controller->$method(...$params);

        if (!$response instanceof Response) {
            throw new \LogicException(\sprintf('Invalid data returned from %s::%s must be of type %s, %s given', $controller::class, $method, Response::class, \get_debug_type($response)));
        }

        $response->send();
    }
}
<?php
namespace nigma\component\route;

use nigma\component\exception\RouteNotFoundException;
use nigma\component\http\Request;

/**
 * Class Router
 * @package nigma\component\route
 */
class Router
{
  /**
   * @var Route[]
   */
  private $routes;

  public function __construct($config)
  {
    $this->routes = [];

    if (isset($config['routes']) && is_array($config['routes']))
    {
      foreach ($config['routes'] as $name => $routeConfig)
      {
        $route = new Route(
          $name,
          (isset($routeConfig['path']) ? $routeConfig['path'] : '/'),
          $routeConfig['controller'],
          isset($routeConfig['action']) ? $routeConfig['action'] : 'index',
          isset($routeConfig['methods']) ? $routeConfig['methods']: []
        );


        $this->routes[$name] = $route;
      }
    }
  }

  /**
   * @param $routeName
   * @param array $params
   * @param bool $absolute
   * @throws RouteNotFoundException
   */
  public function generateUrl($routeName, array $params = [], $absolute = false, $secure = false)
  {
    /** @var Route $route */
    $route = $this->getRouteByName($routeName);

    $result = $route->generateUrl($params);

    if ($absolute)
    {
      $protocol = $secure ? 'https' : 'http';
      $result = $protocol . '://' . $_SERVER['HTTP_HOST'] . $result;
    }

    return $result;
  }

  /**
   * Возвращает объект типа Route, соответствующий запросу $request. Если маршрут не найден, то генерируется исключение
   * RouteNotFoundException
   * @param Request $request
   * @return Route
   */
  public function getRouteByRequest(Request $request)
  {
    $result = null;
    $path = $request->getPath();

    foreach ($this->routes as $name => $route)
    {
      if ($route->isPathEquivalent($path))
      {
        $result = $route;
        break;
      }
    }

    if (!$result)
    {
      throw new RouteNotFoundException('Route by path "' . $path . '" does not exist');
    }

    if (!empty($result->getAllowHttpMethods()) && (!in_array($request->getMethod(), $result->getAllowHttpMethods())))
    {
      throw new RouteNotFoundException('Route by path "' . $path . '" does not exist');
    }

    return $result;
  }

  public function getRouteByName($name)
  {
    if (!isset($this->routes[$name]))
    {
      throw new RouteNotFoundException('Route "' . $name . '" does not exist');
    }

    return $this->routes[$name];
  }

  /**
   * Парсит из строки запроса данные, соответствующие заполнителям в маршруте, и устанавливает их
   * запросу в качестве атрибутов $request->attributes
   *
   * @param Request $request
   * @param Route $route
   */
  public function initRequestAttributesByRoute(Request $request, Route $route)
  {
    if (!$route->isPathEquivalent($request->getPath()))
    {
      return;
    }

    $placeHolders = $route->getPathPlaceholders();
    $requestPathComponents = explode('/', $request->getPath());

    foreach ($placeHolders as $position => $name)
    {
      $request->setAttribute($name, $requestPathComponents[$position]);
    }
  }
}
<?php

namespace nigma\component\application;


use nigma\component\Controller;
use nigma\component\exception\NotFoundException;
use nigma\component\exception\RouteNotFoundException;
use nigma\component\exception\SystemException;
use nigma\component\http\Request;
use nigma\component\http\Response;
use nigma\component\route\Route;
use nigma\component\route\Router;
use nigma\model\User;


class WebApplication extends Application
{
  private $request;

  public function run()
  {
    // инициализировать запрос
    $request = $this->getRequest();

    // инициализовать маршрут
    /** @var Router $router */
    $router = $this->getComponent('router');

    try
    {
      $route = $router->getRouteByRequest($request);
    }
    catch (RouteNotFoundException $exception)
    {
      $route = $router->getRouteByName('not_found');
    }

    // добавить значение параметров, переданных в заполнителях урла
    $router->initRequestAttributesByRoute($request, $route);

    // инициализировать пользователя в системе по его запросу
    $this->initRequestUser($request);

//    // получить контроллер
//    $controllerClass = $route->getControllerClass();
//    if (!class_exists($controllerClass))
//    {
//      throw new SystemException('Controller "' . $controllerClass . '" does not exist');
//    }
//
//
//    /** @var Controller $controller */
//    $controller = new $controllerClass;
//
//    $actionMethod = $route->getActionName() . 'Action';
//    if (!method_exists($controller, $actionMethod))
//    {
//      throw new SystemException('Method "' . $controllerClass . '->' . $actionMethod . '() does not exist');;
//    }
//
//    // установить контроллеру входной запрос
//    $controller->setRequest($request);
//    // вызвать контроллер
//    $response = $controller->{$actionMethod}();
//
//    // обработать ответ
//    if (!($response instanceof Response))
//    {
//      $response = new Response($response);
//    }

    // отдать ответ пользователю

    $response = null;

    try
    {
      $response = $this->executeRoute($route, $request);
    }
    catch (NotFoundException $notFoundError)
    {
      $route = $router->getRouteByName('not_found');
      $response = $this->executeRoute($route, $request);
    }
    catch (SystemException $systemError)
    {
      $route = $router->getRouteByName('system');
      $response = $this->executeRoute($route, $request);
    }

    $response->send();
  }

  private function executeRoute(Route $route, Request $request)
  {
    $controllerClass = $route->getControllerClass();
    if (!class_exists($controllerClass))
    {
      throw new SystemException('Controller "' . $controllerClass . '" does not exist');
    }

    /** @var Controller $controller */
    $controller = new $controllerClass;

    $actionMethod = $route->getActionName() . 'Action';
    if (!method_exists($controller, $actionMethod))
    {
      throw new SystemException('Method "' . $controllerClass . '->' . $actionMethod . '() does not exist');;
    }

    // установить контроллеру входной запрос
    $controller->setRequest($request);
    // вызвать контроллер
    $response = $controller->{$actionMethod}();

    // обработать ответ
    if (!($response instanceof Response))
    {
      $response = new Response($response);
    }

    return $response;
  }


  /**
   * @return Request
   */
  private function getRequest()
  {
    if (!$this->request)
    {
      $this->request = new Request();
    }

    return $this->request;
  }

  private function initRequestUser(Request $request)
  {
    $token = $request->getCookie('token') ? $request->getCookie('token') : '';

    if ($token != '')
    {
      $user = User::findOneBy(['authToken' => $token]);
      if ($user)
      {
        $request->setAttribute('user', $user);
      }
    }
  }
}
<?php

namespace nigma\component;
use nigma\component\application\Application;
use nigma\component\http\Request;
use nigma\component\http\Response;
use nigma\component\route\Router;

/**
 * Base class for all application controller
 * Class Controller
 * @package nigma\component
 */
abstract class Controller
{
  /**
   * @var Request
   */
  protected $request;

  public function setRequest(Request $request)
  {
    $this->request = $request;
  }

  public function getRequest()
  {
    return $this->request;
  }

  protected function render($template, array $params = [], $layout = 'layout')
  {
    $params = array_merge($params, [
      'user' => $this->getUser(),
      'request' => $this->getRequest()
    ]);

    $params['title'] = isset($params['title']) ? $params['title'] : 'Posts';

    $view = new View($template, $params, $layout);
    $contents = $view->render();

    $response = new Response($contents);
    return $response;
  }

  protected function getUser()
  {
    return $this->getRequest()->hasAttribute('user') ? $this->getRequest()->getAttribute('user') : null;
  }

  protected function getUnprocessableEntityResponse($content)
  {
    return new Response($content, [], 422);
  }

  protected function getUnprocessableEntityJsonResponse($data = [])
  {
    return $this->getJsonResponse($data, ['Content-Type' => 'application/json'], 422);
  }

  protected function getJsonResponse($data = [], $headers = [], $code = 200)
  {
    $result = new Response(json_encode($data));

    $result->setHeaders($headers);
    $result->setStatusCode($code);

    return $result;
  }

  protected function get($componentId)
  {
    return Application::getInstance()->getComponent($componentId);
  }

  protected function getRedirectResponse($path)
  {
    $result = new Response();
    $result->setHeader('Location', $path);

    return $result;
  }

  protected function generateUrl($route, $params, $absolute = false, $secure = false)
  {
    /** @var Router $router */
    $router = $this->get('router');

    return $router->generateUrl($route, $params, $absolute, $secure);
  }
}
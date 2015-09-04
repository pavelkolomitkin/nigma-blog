<?php

namespace nigma\component\route;


use nigma\component\Controller;

class Route
{
  protected static function trimSlashPath($path)
  {
    $result = trim($path);

    if (($result != '') && ($result[0] == '/'))
    {
      $result = substr($result, 1);
    }

    if (($result != '') && ($result[strlen($result) - 1] == '/'))
    {
      $result = substr($result, 0, strlen($result) - 1);
    }

    return $result;
  }


  /**
   * @var string
   */
  private $name;

  /**
   * @var string
   */
  private $path;

  /**
   * @var string
   */
  private $controllerClass;

  /**
   * @var string
   */
  private $actionName;

  /**
   * @var array
   */
  private $allowHttpMethods;




  /**
   * @param $name
   * @param $controller
   * @param $action
   * @param array $allowMethods
   */
  public function __construct($name, $path, $controller, $action, $allowHttpMethods = [])
  {
    $this->name = $name;
    $this->path = $path;
    $this->controllerClass = $controller;
    $this->actionName = $action;
    $this->allowHttpMethods = $allowHttpMethods;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getControllerClass()
  {
    return $this->controllerClass;
  }

  /**
   * @return string
   */
  public function getActionName()
  {
    return $this->actionName;
  }

  public function getAllowHttpMethods()
  {
    return $this->allowHttpMethods;
  }

  public function generateUrl($params = [])
  {
    $result = $this->getPath();

    $notFoundedParamsInPath = [];
    foreach ($params as $name => $value)
    {
      $placeholder = '{' . $name . '}';

      if (strpos($result, $placeholder) !== false)
      {
        $result = str_replace($placeholder, $value, $result);
      }
      else
      {
        $notFoundedParamsInPath[$name] = $value;
      }
    }

    if (!empty($notFoundedParamsInPath))
    {
      $result .= '?' . http_build_query($notFoundedParamsInPath);
    }

    return $result;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function setPath($path)
  {
    $this->path = $path;
    return $this;
  }

  /**
   * Проверяет эквивалентность пути маршруту
   * @param $path
   * @return bool
   */
  public function isPathEquivalent($path)
  {
    $result = true;

    $pathComponents = explode('/', $path);
    $routeComponents = explode('/', $this->getPath());

    if (count($pathComponents) == count($routeComponents))
    {
      foreach ($pathComponents as $index => $pathComponent)
      {
        $routeComponent = $routeComponents[$index];

        if (!(($this->isPlaceHolder($routeComponent)) || ($routeComponent == $pathComponent)))
        {
          $result = false;
          break;
        }

      }
    }
    else
    {
      $result = false;
    }

    return $result;
  }

  /**
   * Возвращает наборе имет заполнителей в пути как [номер позиции => имя заполнителя]
   * @return array
   */
  public function getPathPlaceholders()
  {
    $result = [];

    $pathComponents = explode('/', $this->getPath());

    foreach ($pathComponents as $position => $component)
    {
      if ($this->isPlaceHolder($component))
      {
        $name = $this->getPlaceHolderName($component);
        $result[$position] = $name;
      }
    }



    return $result;
  }

  private function isPlaceHolder($component)
  {
    $result = false;

    if (mb_strlen($component) > 2)
    {
      $result = ($component[0] == '{') && ($component[mb_strlen($component) - 1] == '}');
    }

    return $result;
  }

  private function getPlaceHolderName($placeHolder)
  {
    $result = substr($placeHolder, 1, -1);
    return $result;
  }
}
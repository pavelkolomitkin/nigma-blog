<?php
namespace nigma\component\http;


class Request
{
  /**
   * @var array
   */
  protected $attributes = [];

  /**
   * @var array
   */
  protected $headers = [];

  public function __construct()
  {

    $this->initHeaders();
  }

  protected function initHeaders()
  {
    $this->headers = getallheaders();
  }

  public function getParameter($name, $default = null)
  {
    $result = $default;

    if (isset($_GET[$name]))
    {
      $result = $_GET[$name];
    }
    else if (isset($_POST[$name]))
    {
      $result = $_POST[$name];
    }
    else if ($this->hasAttribute($name))
    {
      $result = $this->getAttribute($name);
    }

    return $result;
  }

  public function setAttributes(array $attributes)
  {
    $this->attributes = $attributes;
    return $this;
  }

  public function getAttributes()
  {
    return $this->attributes;
  }

  public function setAttribute($name, $value)
  {
    $this->attributes[$name] = $value;
  }

  public function getAttribute($name)
  {
    return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
  }

  public function hasAttribute($name)
  {
    return array_key_exists($name, $this->attributes);
  }

  public function getMethod()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function isPost()
  {
    return ($this->getMethod() == 'POST');
  }

  public function getCookie($name, $default = null)
  {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
  }

  public function getPath()
  {
    return strtok($_SERVER["REQUEST_URI"],'?');
  }

  public function getUri()
  {
    return $_SERVER['REQUEST_URI'];
  }
}
<?php

namespace nigma\component\http;


class Response
{
  /**
   * @var string
   */
  protected $content;

  /**
   * @var array
   */
  protected $headers;

  /**
   * @var int
   */
  protected $statusCode;

  public function __construct($content = '', array $headers = [], $code = 200)
  {
    $this->setContent($content);
    $this->headers = $headers;
    $this->statusCode = $code;
  }

  public function getStatusCode()
  {
    return $this->statusCode;
  }

  public function setStatusCode($code)
  {
    $this->statusCode = $code;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setHeaders($headers)
  {
    $this->headers = $headers;
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function getHeader($name, $default = null)
  {
    return (isset($this->headers[$name]) ? $this->headers[$name] : $default);
  }

  public function setHeader($name, $value)
  {
    $this->headers[$name] = $value;
  }

  public function setCookie($name, $value = null, $expire = null, $path = '/', $domain = null, $secure = null, $httpOnly = null)
  {
    $result = setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

    if (!is_null($value))
    {
      $_COOKIE[$name] = $value;
    }
    else
    {
      unset($_COOKIE[$name]);
    }

    return $result;
  }

  public function send()
  {
    http_response_code($this->getStatusCode());

    foreach ($this->getHeaders() as $name => $value)
    {
      header($name . ': ' . $value);
    }

    print $this->content;
  }
}
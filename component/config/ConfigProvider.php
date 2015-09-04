<?php

namespace nigma\component\config;

class ConfigProvider implements IConfigProvider
{
  /**
   * @var array
   */
  private $params;

  public function __construct(array $params)
  {
    $this->params = $params;
  }

  /**
   * @param $key
   * @param null $default
   * @return mixed
   */
  public function getParameter($key, $default = null)
  {
    return isset($this->params['parameters'][$key]) ? $this->params['parameters'][$key] : $default;
  }

  /**
   * @return mixed
   */
  public function getParameters()
  {
    return $this->params['parameters'];
  }

  /**
   * @return array
   */
  public function getSection($name)
  {
    return isset($this->params[$name]) ? $this->params[$name] : null;
  }
}
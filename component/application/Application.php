<?php

namespace nigma\component\application;


use nigma\component\exception\ComponentNotFoundException;
use nigma\component\exception\SystemException;
use nigma\component\config\IConfigProvider;
use nigma\component\Model;
use nigma\component\View;

abstract class Application
{
  /**
   * @var static
   */
  private static $instance;

  public static function getInstance()
  {
    return static::$instance;
  }

  public static function setInstance(Application $application)
  {
    static::$instance = $application;
  }

  /**
   * @var \nigma\component\config\IConfigProvider
   */
  protected $config;

  /**
   * @var array
   */
  protected $components;

  public function __construct(IConfigProvider $config)
  {
    $this->config = $config;
    $this->initServices();
  }

  protected function initServices()
  {
    $servicesConfig = $this->config->getSection('services');
    $servicesConfig = $servicesConfig ? $servicesConfig : [];

    $this->components = [];
    foreach ($servicesConfig as $componentId => $componentConfig)
    {
      $this->validateConfigComponentClass($componentConfig);

      $component = null;
      $componentClass = $componentConfig['class'];

      unset($componentConfig['class']);
      if (isset($componentConfig['params']))
      {
        $component = new $componentClass($componentConfig['params']);
        unset($componentConfig['params']);
      }
      else
      {
        $component = new $componentClass();
      }


      foreach ($componentConfig as $name => $value)
      {
        if (property_exists($component, $name))
        {
          $component->{$name} = $value;
        }
        else if (method_exists($component, 'set' . ucfirst($name)))
        {
          $component->{'set' . ucfirst($name)}($value);
        }
        else
        {
          throw new SystemException('Class "' . $componentClass . '" has no property "' . $name . '" or method "' . 'set' . ucfirst($name) . '"');
        }
      }

      $this->components[$componentId] = $component;
    }

    Model::init($this->getComponent('db'));
    View::setTemplateDirectory($this->config->getParameter('templatePath'));
  }

  private function validateConfigComponentClass(array $config)
  {
    if (!isset($config['class']))
    {
      throw new SystemException('Property "class" does not exist');
    }
    if (!class_exists($config['class']))
    {
      throw new SystemException('Class "' . $config['class'] . '" does not exist');
    }
  }

  abstract public function run();

  /**
   * Возвращает компонент по его идентификатору, увказанному в конфигурации. Если
   * компонент не найден, то генерируется исключение ComponentNotFoundException
   * @param $id
   * @return mixed
   */
  public function getComponent($id)
  {
    if (!isset($this->components[$id]))
    {
      throw new ComponentNotFoundException('Component with id "' . $id . '" does not exist');
    }

    return $this->components[$id];
  }
}
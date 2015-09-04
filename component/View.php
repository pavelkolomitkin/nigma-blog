<?php

namespace nigma\component;
use nigma\component\application\Application;

/**
 * Class View
 * @package nigma\component
 */
class View
{
  protected static $templateDirectory;

  public static function setTemplateDirectory($path)
  {
    static::$templateDirectory = $path;
  }

  public function getTemplatePath($template)
  {
    return static::$templateDirectory . '/' . $template . '.php';
  }

  /**
   * @var string
   */
  protected $template;

  /**
   * @var array
   */
  protected $params;

  /**
   * @var string
   */
  protected $layout;


  public function __construct($template, array $params = [], $layout = null)
  {
    $this->template = $template;

    $this->params = $params;

    $this->layout = $layout;
  }

  /**
   * Возвращает итоговый результат
   * @return string
   */
  public function render()
  {
    $templatePath = static::getTemplatePath($this->template);

    ob_start();

    extract($this->params);
    include $templatePath;
    $result = ob_get_contents();

    ob_end_clean();

    if ($this->layout)
    {
      $layoutPath = static::getTemplatePath($this->layout);

      $body = $result;

      ob_start();

      include $layoutPath;
      $result = ob_get_contents();

      ob_end_clean();
    }

    return $result;
  }

  protected function escape($value)
  {
    return htmlspecialchars($value);
  }

  protected function process($value)
  {
    $value = nl2br($value);
    return $value;
  }

  protected function generateUrl($route, $params = [], $absolute = false, $secure = false)
  {
    return Application::getInstance()->getComponent('router')->generateUrl($route, $params, $absolute, $secure);
  }
}
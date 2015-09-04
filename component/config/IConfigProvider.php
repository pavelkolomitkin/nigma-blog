<?php

namespace nigma\component\config;


interface IConfigProvider
{
  /**
   * @param $key
   * @param null $default
   * @return mixed
   */
  function getParameter($key, $default = null);

  /**
   * @return mixed
   */
  function getParameters();

  /**
   * @return array
   */
  function getSection($name);
}
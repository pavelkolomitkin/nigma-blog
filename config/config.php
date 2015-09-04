<?php

$common = include_once('common.php');
$custom = include_once('custom.php');

foreach ($common as $key => $value)
{
  if (isset($custom[$key]) && is_array($custom[$key]))
  {
    $common[$key] = array_merge($common[$key], $custom[$key]);
    unset($custom[$key]);
  }
}

$provider = new \nigma\component\config\ConfigProvider(array_merge($common, $custom));
return $provider;
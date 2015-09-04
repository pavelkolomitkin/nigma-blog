<?php

namespace nigma\component\validator;


use nigma\component\exception\SystemException;

class LengthValidator extends Validator
{
  public function __construct(array $options = [])
  {
    if (isset($options['min']) && isset($options['max']))
    {
      throw new SystemException('LengthValidator requires min or max option');
    }

    parent::__construct($options);
  }

  public function validate($value)
  {
    $error = null;

    if ($value === null)
    {
      $value = '';
    }


    $min = isset($this->options['min']) ? $this->options['min'] : null;
    $max = isset($this->options['max']) ? $this->options['max'] : null;

    if (($min !== null) && (mb_strlen($value) < $min))
    {
      $error = new Error('Значение не может быть меньше ' . $min . ' символов');
    }
    else if (($max !== null) && (mb_strlen($value) > $max ))
    {
      $error = new Error('Значение не может быть больше ' . $max . ' символов');
    }

    return $error;
  }
}
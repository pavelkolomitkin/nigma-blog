<?php

namespace nigma\component\validator;


class NotBlankValidator extends Validator
{
  public function validate($value)
  {
    $error = null;

    if (is_null($value) ||(trim($value) == ''))
    {
      $error = new Error($this->getMessage() ?  $this->getMessage() : 'Поле не может быть пустым');
    }

    return $error;
  }
}
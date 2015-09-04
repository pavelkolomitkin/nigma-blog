<?php

namespace nigma\component\validator;


class EmailValidator extends Validator {

  public function validate($value)
  {
    $error = null;

    if (!filter_var($value, FILTER_VALIDATE_EMAIL))
    {
      $error = new Error($this->getMessage() ?  $this->getMessage() : 'Введите корректный Email');
    }

    return $error;
  }
}
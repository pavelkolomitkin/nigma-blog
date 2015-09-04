<?php

namespace nigma\component\validator;

abstract class Validator
{
  /**
   * @var array
   */
  protected $options;

  public function __construct(array $options = [])
  {
    $this->options = $options;
  }

  public function getMessage()
  {
    return isset($this->options['message']) ? $this->options['message'] : null;
  }

  abstract public function validate($value);
}
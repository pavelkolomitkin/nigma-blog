<?php

namespace nigma\component\validator;


class Error
{
  /**
   * @var string
   */
  private $message;

  public function __construct($message)
  {
    $this->message = $message;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function __toString()
  {
    return $this->getMessage();
  }
} 
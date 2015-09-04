<?php

namespace nigma\component\form;


class Form
{
  /**
   * @var array
   */
  protected $fields = [];

  /**
   * @var array
   */
  protected $data = [];

  protected $options = [];

  public function __construct(array $options = [])
  {
    $this->options = $options;

    $this->build();
  }

  protected function build()
  {

  }

  public function addField(Field $field)
  {

    $this->fields[$field->getName()] = $field;
    return $this;
  }

  public function removeField(Field $field)
  {
    if (isset($this->fields[$field->getName()]))
    {
      unset($this->fields[$field->getName()]);
    }

    return $this;
  }

  public function removeFieldByName($name)
  {
    if (isset($this->fields[$name]))
    {
      unset($this->fields[$name]);
    }

    return $this;
  }

  public function setData(array $data)
  {
    $this->data = $data;
    $this->updateFieldData();
  }

  public function getData()
  {
    return $this->data;
  }

  public function validate()
  {
    $errors = [];

    /** @var Field $field */
    foreach ($this->fields as $field)
    {
      $fieldError = $field->validate();
      if ($fieldError)
      {
        $errors[$field->getName()] = $fieldError;
      }
    }

    return $errors;
  }

  protected function updateFieldData()
  {
    /** @var Field $field */
    foreach ($this->fields as $field)
    {
      $field->setValue(
        isset($this->data[$field->getName()]) ? $this->data[$field->getName()] : null
      );
    }
  }
}
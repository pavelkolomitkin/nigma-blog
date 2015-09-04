<?php
namespace nigma\component\form;


use nigma\component\validator\Validator;

class Field
{
  /**
   * @var Validator
   */
  protected $validators;

  /**
   * @var string
   */
  protected $type;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var Form
   */
  protected $form;

  /**
   * @var array
   */
  protected $attributes;

  /**
   * @var mixed
   */
  protected $value;

  public function __construct($name, $type, array $options = [])
  {
    $this->setName($name);
    $this->setType($type);

    if (isset($options['validators']) && (is_array($options['validators'])))
    {
      foreach ($options['validators'] as $validator)
      {
        $this->addValidator($validator);
      }
    }

    if (isset($options['attributes']) && (is_array($options['attributes'])))
    {
      $this->setAttributes($options['attributes']);
    }

  }

  public function setValue($value)
  {
    $this->value = $value;
    return $this;
  }

  public function getValue()
  {
    return $this->value;
  }

  public function setAttributes(array $attributes)
  {
    $this->attributes = $attributes;

    if (isset($this->attributes['value']))
    {
      $this->setValue($this->attributes['value']);
    }

    return $this;
  }

  public function getAttributes()
  {
    return $this->attributes;
  }

  public function getValidators()
  {
    return $this->validators;
  }

  public function addValidator(Validator $validator)
  {
    $this->validators[] = $validator;
    return $this;
  }

  public function removeValidator(Validator $validator)
  {
    $index = array_search($validator, $this->validators, true);
    if ($index !== false)
    {
      unset($this->validators[$index]);
    }

    return $this;
  }

  public function setForm(Form $form)
  {
    $this->form = $form;
    return $this;
  }

  public function getForm()
  {
    return $this->form;
  }

  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setType($type)
  {
    $this->type = $type;
    return $this;
  }

  public function getType()
  {
    return $this->type;
  }

  public function validate()
  {
    $error = null;

    /** @var Validator $validator */
    foreach ($this->validators as $validator)
    {
      $error = $validator->validate($this->value);
      if ($error)
      {
        break;
      }
    }

    return $error;
  }
}
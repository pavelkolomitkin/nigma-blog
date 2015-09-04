<?php

namespace nigma\form;


use nigma\component\form\Field;
use nigma\component\form\Form;
use nigma\component\validator\LengthValidator;
use nigma\component\validator\NotBlankValidator;

class Post extends Form
{
  protected function build()
  {
    $this->addField(new Field('title', 'text', [
      'attributes' => [
        'placeholder' => 'Название',
        'required' => 'required'
      ],
      'validators' => [
        new NotBlankValidator(),
        new LengthValidator(['max' => 255])
      ]
    ]))
      ->addField(new Field('text', 'textarea', [
        'attributes' => [
          'placeholder' => 'Текст статьи',
          'required' => 'required'
        ],
        'validators' => [
          new NotBlankValidator(),
          new LengthValidator(['max' => 50000])
        ]
      ]));
  }

} 
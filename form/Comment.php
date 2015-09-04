<?php

namespace nigma\form;


use nigma\component\form\Field;
use nigma\component\form\Form;
use nigma\component\validator\LengthValidator;
use nigma\component\validator\NotBlankValidator;

class Comment extends Form
{
  protected function build()
  {
    $isUserAuthorized = isset($this->options['userAuthorized']) ? $this->options['userAuthorized'] : false;

    if (!$isUserAuthorized)
    {
      $this->addField(new Field('authorName', 'text', [
        'attributes' => [
          'placeholder' => 'Ваше имя',
          'required' => 'required'
        ],
        'validators' => [
          new NotBlankValidator()
        ]
      ]))->addField(new Field('authorEmail', 'text', [
        'attributes' => [
          'placeholder' => 'Ваше имя',
          'required' => 'required'
        ],
        'validators' => [
          new NotBlankValidator()
        ]
      ]));
    }

    $this->addField(new Field('text', 'textarea', [
      'attributes' => [
        'placeholder' => 'Ваш комментарий',
        'required' => 'required'
      ],
      'validators' => [
        new NotBlankValidator(),
        new LengthValidator(['max' => 300])
      ]
    ]));
  }
}
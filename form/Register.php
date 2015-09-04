<?php

namespace nigma\form;


use nigma\component\form\Field;
use nigma\component\form\Form;
use nigma\component\validator\EmailValidator;
use nigma\component\validator\Error;
use nigma\component\validator\LengthValidator;
use nigma\component\validator\NotBlankValidator;
use nigma\model\User;

class Register extends Form
{
  protected function build()
  {
    $this->addField(new Field('email', 'email', [
      'attributes' => [
        'placeholder' => 'Ваш email',
        'required' => 'required'
      ],
      'validators' => [
        new NotBlankValidator(['message' => 'Укажите Ваш E-mail']),
        new EmailValidator(['message' => 'Введите корректный E-mail']),
        new LengthValidator(['message' => 'Поле не может превышать 100 символов', 'max' => 100])
      ]
    ]))
      ->addField(new Field('name', 'name', [
        'attributes' => [
          'placeholder' => 'Ваше имя',
          'required' => 'required'
        ],
        'validators' => [
          new NotBlankValidator(['message' => 'Укажите Ваше имя']),
          new LengthValidator(['message' => 'Поле не может превышать 100 символов', 'max' => 100])
        ]
      ]))
      ->addField(new Field('password', 'password', [
        'attributes' => [
          'placeholder' => 'Пароль',
          'required' => 'required'
        ],
        'validators' => [
          new NotBlankValidator()
        ]
      ]))
      ->addField(new Field('repeatPassword', 'password', [
        'attributes' => [
          'placeholder' => 'Повторите пароль',
          'required' => 'required'
        ],
        'validators' => [
          new NotBlankValidator()
        ]
      ]));
  }

  public function validate()
  {
    $errors = parent::validate();

    if (!isset($errors['email']))
    {
      $user = User::findOneBy(['email' => $this->data['email']]);
      if ($user)
      {
        $errors['email'] = new Error('Пользователь с таким email уже существует');
      }
    }

    if (count($errors) == 0)
    {
      if ($this->data['password'] != $this->data['repeatPassword'])
      {
        $error = new Error('Пароли должны совпадать');

        $errors['password'] = $error;
        $errors['repeatPassword'] = $error;
      }
    }

    return $errors;
  }
}
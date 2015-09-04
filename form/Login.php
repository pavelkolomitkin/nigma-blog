<?php

namespace nigma\form;


use nigma\component\application\Application;
use nigma\component\form\Field;
use nigma\component\form\Form;
use nigma\component\security\Security;
use nigma\component\validator\EmailValidator;
use nigma\component\validator\Error;
use nigma\component\validator\NotBlankValidator;
use nigma\model\User;

class Login extends Form
{
  protected function build()
  {
    $this->addField(new Field('email', 'email', [
      'attributes' => [
        'placeholder' => 'Ваш email',
        'required' => 'required'
      ],
      'validators' => [
        new NotBlankValidator(),
        new EmailValidator()
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
      ]));
  }

  public function validate()
  {
    $errors = parent::validate();

    if (empty($errors))
    {
      $email = $this->getData()['email'];
      $password = $this->getData()['password'];

      $user = User::findOneBy(array(
        'email' => $email
      ));
      if (!$user)
      {
        $errors['email'] = new Error('Неправильно введен логин или пароль');
      }
      else
      {

        /** @var Security $security */
        $security = Application::getInstance()->getComponent('security');
        if ($user->password != $security->getPasswordHash($password, $user->salt))
        {
          $errors['email'] = new Error('Неправильно введен логин или пароль');
        }
      }
    }

    return $errors;
  }
}
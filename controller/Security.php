<?php

namespace nigma\controller;

use nigma\component\application\Application;
use nigma\component\Controller;
use nigma\component\http\Response;
use nigma\form\Login;
use nigma\form\Register;
use nigma\model\User;

class Security extends Controller
{
  public function loginAction()
  {
    $request = $this->getRequest();


    if ($request->isPost())
    {
      $form = new Login();
      $form->setData(
        [
          'email' => $request->getParameter('email'),
          'password' => $request->getParameter('password')
        ]
      );

      $errors = $form->validate();
      if (count($errors) > 0)
      {
        $content = [];
        foreach ($errors as $name => $error)
        {
          $content[$name] = $error->getMessage();
        }
        return $this->getUnprocessableEntityJsonResponse(['errors' => $content]);
      }
      else
      {
        $token = $this->get('security')->generateUniqueToken();

        $user = User::findOneBy(['email' => $form->getData()['email']]);
        $user->authToken = $token;
        $user->save();

        $response = $this->getJsonResponse();
        $response->setCookie('token', $token);

        return $response;
      }
    }
    else
    {
      return $this->render('security/index');
    }
  }

  public function registerAction()
  {
    $request = $this->getRequest();

    $form = new Register();
    $form->setData([
      'email' => $request->getParameter('email'),
      'name' => $request->getParameter('name'),
      'password' => $request->getParameter('password'),
      'repeatPassword' => $request->getParameter('repeatPassword')
    ]);

    $errors = $form->validate();
    if (count($errors) > 0)
    {
      $content = [];
      foreach ($errors as $name => $error)
      {
        $content[$name] = $error->getMessage();
      }
      return $this->getUnprocessableEntityJsonResponse(['errors' => $content]);
    }
    else
    {
      $data = $form->getData();

      $user = new User();

      $user->email = $data['email'];
      $user->name = $data['name'];
      $user->salt = $this->get('security')->generateUniqueToken();
      $user->password = $this->get('security')->getPasswordHash($data['password'], $user->salt);

      $user->save();

      return $this->getJsonResponse();
    }
  }

  public function logoutAction()
  {
    $request = $this->getRequest();

    $token = $request->getCookie('token');

    $response = $this->getRedirectResponse('/');

    if ($token)
    {
      // найти по куки пользователя и занулить поле authToken
      $user = User::findOneBy(['authToken' => $request->getCookie('token')]);
      if ($user)
      {
        $user->authToken = null;
        $user->save();
      }

      // удалить куки
      $response->setCookie('token');
    }

    return $response;
  }
}
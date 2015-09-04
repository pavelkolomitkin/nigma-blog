<?php

namespace nigma\controller;


use nigma\component\Controller;

class Error extends Controller
{
  public function notFoundAction()
  {
    return $this->render('error/notFound');
  }

  public function systemAction()
  {
    return $this->render('error/system');
  }
}
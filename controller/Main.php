<?php

namespace nigma\controller;


use nigma\component\Controller;

class Main extends Controller
{
  public function indexAction()
  {
    return $this->render('main/index');
  }
}
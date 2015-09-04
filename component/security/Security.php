<?php

namespace nigma\component\security;


class Security
{
  public function generateUniqueToken()
  {
    return sha1(microtime(true) . '_' . rand(0, 9999));
  }

  public function getPasswordHash($password, $salt)
  {
    return sha1($salt . '_' . $password . '_' . $salt . '_' . $salt);
  }
} 
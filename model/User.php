<?php

namespace nigma\model;


use nigma\component\Model;

class User extends Model
{
  protected static $tableName = 'users';

  protected static $fieldMap = [
    'id' => [
      'field' => 'id',
      'type' => self::FIELD_TYPE_INT
    ],
    'email' => [
      'field' => 'email',
      'type' => self::FIELD_TYPE_STRING
    ],
    'name' => [
      'field' => 'name',
      'type' => self::FIELD_TYPE_STRING
    ],
    'password' => [ // as hash
      'field' => 'password',
      'type' => self::FIELD_TYPE_STRING
    ],
    'salt' => [
      'field' => 'salt',
      'type' => self::FIELD_TYPE_STRING
    ],
    'authToken' => [
      'field' => 'authToken',
      'type' => self::FIELD_TYPE_STRING
    ]
  ];

} 
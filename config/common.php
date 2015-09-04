<?php

return [
  'services' => [
    'db' => [
      'class' => 'nigma\component\database\DatabaseProvider',
      'params' => [
        'type' => 'mysql',
        'host' => 'localhost',
        'port' => '',
        'user' => 'root',
        'password' => null,
        'dbname' => 'nigma_blog',
        'encoding' => 'utf8'
      ]
    ],

    'router' => [
      'class' => 'nigma\component\route\Router',

      'params' => [
        'routes' =>
          [
            'main' => [
              'path' => '/',
              'controller' => 'nigma\controller\Main',
              'action' => 'index', // optional
              'methods' => ['GET'] // optional
            ],
            'login' => [
              'path' => '/login',
              'controller' => 'nigma\controller\Security',
              'action' => 'login', // optional
              'methods' => ['GET', 'POST']
            ],

            'register' => [
              'path' => '/register',
              'controller' => 'nigma\controller\Security',
              'action' => 'register',
              'methods' => ['POST']
            ],
            'logout' => [
              'path' => '/logout',
              'controller' => 'nigma\controller\Security',
              'action' => 'logout'
            ],

            'post_create' => [
              'path' => '/post/create',
              'controller' => 'nigma\controller\Post',
              'action' => 'create'
            ],

            'post_list' => [
              'path' => '/post/list',
              'controller' => 'nigma\controller\Post',
              'action' => 'list'
            ],

            'post_view' => [
              'path' => '/post/{id}',
              'controller' => 'nigma\controller\Post',
              'action' => 'index'
            ],

            'post_edit' => [
              'path' => '/post/edit/{id}',
              'controller' => 'nigma\controller\Post',
              'action' => 'edit'
            ],

            'post_delete' => [
              'path' => '/post/delete/{id}',
              'controller' => 'nigma\controller\Post',
              'action' => 'delete',
              'methods' => ['POST']
            ],

            'comment_create' => [
              'path' => '/comment/create',
              'controller' => 'nigma\controller\Comment',
              'action' => 'create',
              'methods' => ['POST']
            ],

            'not_found' => [
              'url' => '/404',
              'controller' => 'nigma\controller\Error',
              'action' => 'notFound', // optional
              'methods' => ['GET'] // optional
            ],
            'system_error' => [
              'url' => '',
              'controller' => 'nigma\controller\Error',
              'action' => 'system', // optional
              'methods' => ['GET'] // optional
            ]
          ]
      ]
    ],

    'security' => [
      'class' => 'nigma\component\security\Security'
    ]
  ],

  'parameters' => [
    'templatePath' => __DIR__ . '/../view'
  ]

];
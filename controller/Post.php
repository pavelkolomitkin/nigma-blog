<?php

namespace nigma\controller;


use nigma\component\Controller;
use nigma\component\exception\AccessDeniedException;
use nigma\component\exception\NotFoundException;
use nigma\component\http\Response;

class Post extends Controller
{
  public function indexAction()
  {
    $id = $this->getRequest()->getParameter('id');

    $post = \nigma\model\Post::find($id);
    if (!$post)
    {
      throw new NotFoundException('Post with id = ' . $id . ' does not exist');
    }

    return $this->render('post/index', [
      'post' => $post
    ]);
  }

  public function listAction()
  {
    $request = $this->getRequest();

    $search = $request->getParameter('search', '');
    $page = $request->getParameter('page', 1);


    $posts = \nigma\model\Post::getPosts($search, $page);

    return $this->getJsonResponse(['posts' => $posts]);
  }

  public function createAction()
  {
    $request = $this->getRequest();

    $user = $this->getUser();
    if (!$user)
    {
      throw new AccessDeniedException('Ошибка доступа');
    }

    $params = [];

    if ($request->isPost())
    {
      $form = new \nigma\form\Post();
      $form->setData([
        'title' => $request->getParameter('title'),
        'text' => $request->getParameter('text')
      ]);

      $errors = $form->validate();
      if (count($errors) > 0)
      {
        $params['errors'] = $errors;
      }
      else
      {
        $post = new \nigma\model\Post();

        $post->title = $form->getData()['title'];
        $post->text = $form->getData()['text'];
        $post->ownerId = $user->id;

        $post->save();

        return $this->getRedirectResponse($this->generateUrl('post_view', ['id' => $post->id]));
      }
    }

    return $this->render('post/create', $params);
  }

  public function editAction()
  {
    $request = $this->getRequest();

    $user = $this->getUser();
    if (!$user)
    {
      throw new AccessDeniedException('У Вас нет прав редактировать эту статью');
    }

    $id = $request->getParameter('id');

    $post = \nigma\model\Post::find($id);
    if (!$post)
    {
      throw new NotFoundException('Статья не найдена');
    }

    if ($post->ownerId != $user->id)
    {
      throw new AccessDeniedException('У Вас нет прав редактировать эту статью');
    }

    $params = [
      'post' => $post,
      'data' => [
        'title' => $post->title,
        'text' => $post->text
      ]
    ];

    if ($request->isPost())
    {
      $form = new \nigma\form\Post();
      $form->setData([
        'title' => $request->getParameter('title'),
        'text' => $request->getParameter('text')
      ]);

      $params['data'] = $form->getData();

      $errors = $form->validate();
      if (count($errors) > 0)
      {
        $params['errors'] = $errors;
      }
      else
      {
        $post->title = $form->getData()['title'];
        $post->text = $form->getData()['text'];

        $post->save();

        return $this->getRedirectResponse($this->generateUrl('post_view', ['id' => $post->id]));
      }
    }

    return $this->render('post/edit', $params);
  }

  public function deleteAction()
  {
    $request = $this->getRequest();

    $user = $this->getUser();
    if (!$user)
    {
      throw new AccessDeniedException('Ошибка доступа');
    }

    $id = $request->getParameter('id');

    $post = \nigma\model\Post::find($id);
    if (!$post)
    {
      throw new NotFoundException('Ошибка доступа');
    }

    if ($post->ownerId != $user->id)
    {
      throw new AccessDeniedException('Ошибка доступа');
    }

    $post->delete();

    return $this->getRedirectResponse('/');
  }
}
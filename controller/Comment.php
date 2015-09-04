<?php

namespace nigma\controller;


use nigma\component\Controller;
use nigma\component\exception\NotFoundException;
use nigma\model\Post;

class Comment extends Controller
{
  public function createAction()
  {
    $request = $this->getRequest();

    $postId = $request->getParameter('postId');

    $post = Post::find($postId);
    if (!$post)
    {
      throw new NotFoundException("Статья не найдена");
    }

    $user = $this->getUser();

    $form = new \nigma\form\Comment(['userAuthorized' => isset($user)]);

    $form->setData([
      'authorName' => $request->getParameter('authorName'),
      'authorEmail' => $request->getParameter('authorEmail'),
      'text' => $request->getParameter('text'),
    ]);

    $errors = $form->validate();
    if (count($errors) > 0)
    {
      $data = [];
      foreach ($errors as $name => $error)
      {
        $data[$name] = $error->getMessage();
      }

      return $this->getUnprocessableEntityJsonResponse(['errors' => $data]);
    }
    else
    {
      $data = $form->getData();

      $comment = new \nigma\model\Comment();

      $comment->text = $data['text'];

      if ($user)
      {
        $comment->ownerId = $user ? $user->id : null;
        $comment->authorEmail = $user->email;
        $comment->authorName = $user->name;
      }
      else
      {
        $comment->authorEmail = $data['authorEmail'];
        $comment->authorName = $data['authorName'];
      }

      $comment->postId = $post->id;

      $comment->save();

      return $this->getJsonResponse(['comment' => $comment]);
    }
  }
} 
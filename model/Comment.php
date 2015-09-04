<?php

namespace nigma\model;


use nigma\component\Model;

class Comment extends Model implements \JsonSerializable
{
  protected static $tableName = 'comment';

  protected static $fieldMap = [
    'id' => [
      'field' => 'id',
      'type' => self::FIELD_TYPE_INT
    ],
    'text' => [
      'field' => 'text',
      'type' => self::FIELD_TYPE_STRING
    ],
    'ownerId' => [
      'field' => 'ownerId',
      'type' => self::FIELD_TYPE_INT
    ],
    'authorName' => [
      'field' => 'authorName',
      'type' => self::FIELD_TYPE_STRING
    ],
    'authorEmail' => [
      'field' => 'authorEmail',
      'type' => self::FIELD_TYPE_STRING
    ],
    'postId' => [
      'field' => 'postId',
      'type' => self::FIELD_TYPE_INT
    ],
    'createdAt' => [
      'field' => 'createdAt',
      'type' => self::FIELD_TYPE_DATETIME
    ]
  ];

  /**
   * @var User
   */
  protected $owner;

  /**
   * @var Post
   */
  protected $post;

  public function getOwner()
  {
    if ($this->ownerId && !$this->owner)
    {
      $this->owner = User::find($this->ownerId);
    }

    return $this->owner;
  }

  public function getPost()
  {
    if ($this->postId && !$this->post)
    {
      $this->post = Post::find($this->postId);
    }

    return $this->post;
  }

  public function save()
  {
    if (!$this->createdAt)
    {
      $this->createdAt = new \DateTime();
    }
    parent::save();
  }

  /**
   * (PHP 5 &gt;= 5.4.0)<br/>
   * Specify data which should be serialized to JSON
   * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
   * @return mixed data which can be serialized by <b>json_encode</b>,
   * which is a value of any type other than a resource.
   */
  function jsonSerialize()
  {
    $properties = $this->properties;

    $properties['createdAt'] = $properties['createdAt']->format('Y-m-d H:i');

    return $properties;
  }
}
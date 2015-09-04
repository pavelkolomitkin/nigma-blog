<?php

namespace nigma\model;


use nigma\component\Model;

class Post extends Model implements \JsonSerializable
{
  protected static $tableName = 'post';

  protected static $fieldMap = [
    'id' => [
      'field' => 'id',
      'type' => self::FIELD_TYPE_INT
    ],
    'title' => [
      'field' => 'title',
      'type' => self::FIELD_TYPE_STRING
    ],
    'text' => [
      'field' => 'text',
      'type' => self::FIELD_TYPE_STRING
    ],
    'ownerId' => [
      'field' => 'ownerId',
      'type' => self::FIELD_TYPE_INT
    ],
    'updatedAt' => [
      'field' => 'updatedAt',
      'type' => self::FIELD_TYPE_DATETIME
    ]
  ];


  public static function getPosts($search = '', $page = 1, $limit = 10)
  {
    $sql = "SELECT * FROM `post` WHERE 1";

    $search = trim($search);
    $params = [];

    if ($search != '')
    {
      $sql .= " AND `title` LIKE :search";
      $params['search'] = $search . '%';
    }

    $sql .= " ORDER BY `updatedAt` DESC LIMIT " . static::getOffset($page, $limit) . ", " . intval($limit);

    $statement = static::$databaseProvider->executeSql($sql, $params);

    $result = [];
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC))
    {
      $result[] = static::rowToObject($row);
    }

    return $result;
  }

  function jsonSerialize()
  {
    $result = $this->properties;

    $result['updatedAt'] = $result['updatedAt']->format('Y-m-d H:i');

    $owner = $this->getOwner();
    $result['authorName'] = $owner->name;

    return $result;
  }

  /**
   * @var User
   */
  protected $owner;

  public function getOwner()
  {
    if ($this->ownerId && !$this->owner)
    {
      $this->owner = User::find($this->ownerId);
    }

    return $this->owner;
  }

  protected $comments = null;

  public function getComments()
  {
    if ($this->comments === null)
    {
      $this->comments = Comment::findBy(['postId' => $this->id]);
    }

    return $this->comments;
  }

  public function save()
  {
    $this->updatedAt = new \DateTime();
    parent::save();
  }
}
<?php

namespace nigma\component\database;


class DatabaseProvider
{

  /**
   * @var \PDO
   */
  private $pdo;

  public function __construct(array $params)
  {
    $dsn = $params['type'] . ':host=' . $params['host'] . ';port=' . $params['port'] . ';dbname=' . $params['dbname'];

    $this->pdo = new \PDO($dsn, $params['user'], $params['password']);
    if (isset($params['encoding']))
    {
      $this->pdo->query("SET NAMES " . $params['encoding']);
    }
  }

  public function insert($tableName, array $fields)
  {
    $this->prepareValues($fields);

    $fieldNames = array_keys($fields);

    $valueParameterNames = $fieldNames;
    array_walk($valueParameterNames, function(&$item, $key)
    {
      $item = ':' . $item;
    });

    $sql = "INSERT INTO `" . $tableName .
      "` (`" . implode("`, `", $fieldNames) . "`) VALUES (" . implode(", ", $valueParameterNames) . ")";

    $statement = $this->pdo->prepare($sql);
    $statement->execute($fields);

    return $this->pdo->lastInsertId();
  }

  public function update($tableName, $id, array $fields)
  {
    $this->prepareValues($fields);

    $setExpression = [];
    foreach ($fields as $name => $value)
    {
      $setExpression[] = '`' . $name . '` = :' . $name;
    }
    $setExpression  = implode(', ', $setExpression);

    $sql = "UPDATE `" . $tableName . "` SET " . $setExpression . ' WHERE `id` = :id';

    $statement = $this->pdo->prepare($sql);
    return $statement->execute(array_merge($fields, ['id' => $id]));
  }

  public function delete($tableName, $id)
  {
    $sql = "DELETE FROM `" . $tableName . "` WHERE `id` = :id";

    $statement = $this->pdo->prepare($sql);
    return $statement->execute(['id' => $id]);
  }

  /**
   * @param $tableName
   * @param $id
   * @return mixed
   */
  public function findById($tableName, $id)
  {
    $sql = "SELECT * FROM `" . $tableName . "` WHERE `id` = :id";

    $statement = $this->executeSql($sql, ['id' => $id]);
    return $statement->fetch(\PDO::FETCH_ASSOC);
  }

  public function findOneBy($tableName, array $criteria)
  {
    $whereCondition = $this->getAndEqualCondition($criteria);

    $sql = "SELECT * FROM `" . $tableName . "` WHERE " . $whereCondition;
    $statement = $this->executeSql($sql, $criteria);

    return $statement->fetch(\PDO::FETCH_ASSOC);
  }

  public function findBy($tableName, array $criteria)
  {
    $whereCondition = $this->getAndEqualCondition($criteria);

    $sql = "SELECT * FROM `" . $tableName . "` WHERE " . $whereCondition;
    $statement = $this->executeSql($sql, $criteria);

    return $statement->fetchAll(\PDO::FETCH_ASSOC);
  }

  private function getAndEqualCondition(array $criteria)
  {
    $result = [];
    foreach ($criteria as $name => $value)
    {
      $result[] = "`" . $name . "` = :" . $name;
    }

    return implode(' AND ', $result);
  }

  /**
   * @param $sql
   * @param array $params
   * @return \PDOStatement
   */
  public function executeSql($sql, array $params = [])
  {
    $statement = $this->pdo->prepare($sql);
    $statement->execute($params);

    return $statement;
  }

  private function prepareValues(array &$fields)
  {
    foreach ($fields as $name => $value)
    {
      if ($value instanceof \DateTime)
      {
        $fields[$name] = $value->format('Y-m-d H:i:s');
      }
    }
  }
}
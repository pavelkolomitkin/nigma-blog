<?php

namespace nigma\component;
use nigma\component\application\Application;
use nigma\component\database\DatabaseProvider;
use nigma\component\exception\SystemException;

/**
 * Base class for application models
 * Class Model
 * @package nigma\component
 */
abstract class Model
{
  const FIELD_TYPE_STRING = 'string';
  const FIELD_TYPE_INT = 'int';
  const FIELD_TYPE_DATETIME = 'DateTime';

  protected static $tableName = [];

  protected static $fieldMap = [];

  /**
   * @var array
   */
  //protected static $propertyFieldMap;


  /**
   * @var DatabaseProvider
   */
  protected static $databaseProvider;

  public static function init(DatabaseProvider $provider)
  {
    static::$databaseProvider = $provider;
  }

  public static function getTableName()
  {
    return static::$tableName;
  }

  public static function find($id)
  {
    return static::rowToObject(static::$databaseProvider->findById(static::$tableName, $id));
  }

  public static function findOneBy(array $criteria)
  {
    return static::rowToObject(static::$databaseProvider->findOneBy(static::$tableName, $criteria));
  }

  public static function findBy(array $criteria)
  {
    $result = [];

    $rows = static::$databaseProvider->findBy(static::$tableName, $criteria);
    foreach ($rows as $row)
    {
      $result[] = static::rowToObject($row);
    }

    return $result;
  }

  protected static function rowToObject($row)
  {
    $result = null;

    if ($row)
    {
      $result = new static();

      foreach ($row as $name => $value)
      {
        $propertyName = static::getPropertyByColumn($name);
        $result->{$propertyName} = $value;
      }
    }

    return $result;
  }

  protected static function getPropertyByColumn($columnName)
  {
    $result = '';

    foreach (static::$fieldMap as $property => $metaData)
    {
      if ($columnName == $metaData['field'])
      {
        $result = $property;
        break;
      }
    }

    return $result;
  }


  protected static function getOffset($page, $limit)
  {
    $page = ($page > 1) ? $page : 1;
    $limit = ($limit > 0) ? $limit : 0;

    return ($page - 1) * $limit;
  }


  protected $properties = [];


  public function __construct()
  {
    foreach (static::$fieldMap as $name => $metaData)
    {
      $this->properties[$name] = null;
    }
  }

  protected function prepareValue($value, $type)
  {
    if (!is_null($value))
    {
      switch ($type)
      {
        case static::FIELD_TYPE_INT:

          $value = intval($value);

          break;

        case static::FIELD_TYPE_STRING:

          $value = (string)$value;

          break;

        case static::FIELD_TYPE_DATETIME:

          $value = ($value instanceof \DateTime) ? $value : new \DateTime($value);

          break;
      }
    }

    return $value;
  }


  public function __get($name)
  {
    if (!array_key_exists($name, $this->properties))
    {
      throw new SystemException('Undefined property "' . $name . '" in ' . static::class);
    }

    return $this->properties[$name];
  }

  public function __set($name, $value)
  {
    if (!array_key_exists($name, $this->properties))
    {
      throw new SystemException('Undefined property "' . $name . '" in ' . static::class);
    }

    $this->properties[$name] = $this->prepareValue($value, static::$fieldMap[$name]['type']);
  }

  public function save()
  {
    $model = static::find($this->id);

    $columnValues = $this->getPropertyValuesAsFields();
    if (!$model)
    {
      unset($columnValues['id']);
      $this->id = static::$databaseProvider->insert(static::$tableName, $columnValues);
    }
    else
    {
      static::$databaseProvider->update(static::$tableName, $this->id, $columnValues);
    }
  }

  public function delete()
  {
    static::$databaseProvider->delete(static::$tableName, $this->id);
  }

  protected function getPropertyValuesAsFields()
  {
    $result = [];

    foreach (static::$fieldMap as $propertyName => $metaData)
    {
      $result[$metaData['field']] = $this->{$propertyName};
    }

    return $result;
  }
}
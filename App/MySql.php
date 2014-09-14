<?php

/*
* Class: MySql
*
* Interface that works with the database.
*/

class MySql
{
  protected static $instance;
  private $dbh;

  private static $dbHost = 'mysql1-win.activeby.net';
  private static $dbUser = 'user1_laser';
  private static $dbName = 'user1152365_laser';
  private static $dbPass = '6At!b2r8';

  private  function __construct()
  {
    $dsn = 'mysql:dbname=' . self::$dbName . ';host=' . self::$dbHost;

    try {
      $this->dbh = new PDO($dsn, self::$dbUser, self::$dbPass);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Подключение не удалось: ' . $e->getMessage();
      throw new Exception("Unable connect to server");
    }
    $this->dbh->query("SET NAMES utf8");
  }

  public static function getInstance()
  {
    if (null === self::$instance)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function lastInsertId()
  {
    return $this->dbh->lastInsertId();
  }

  public function select($query, $params = array())
  {
    Statistics::sqlStart();
    $result = $this->dbh->prepare($query);
    $result->execute($params);
    Statistics::sqlEnd();
    if ($result)
    {
      return (0 == $result->rowCount()) ? array() : $result->fetchAll(PDO::FETCH_ASSOC);
    }

    return array();
  }

  public function execute($query, $params = array())
  {
    Statistics::sqlStart();

    $result = $this->dbh->prepare($query);
    $result->execute($params);

    Statistics::sqlEnd();

    return $result;
  }

  public function insert($params, $tableName)
  {
    $columns = implode(', ', array_keys($params));
    $columns = str_replace(':', '', $columns);
    $values = implode(', ', array_keys($params));
    $query = 'INSERT INTO ' . $tableName . '
              (' . $columns . ')
              VALUES (' . $values . ')';
    Statistics::sqlStart();
    $result = $this->dbh->prepare($query);
    $insert = $result->execute($params);
    Statistics::sqlEnd();
    return $insert;
  }

  public function update($params, $conditions, $tableName)
  {
    $set = $condition = '';
    foreach ($params as $key => $value) {
      $set .= trim($key, ':') . ' = ' . $key . ', ';
    }
    foreach ($conditions as $key => $value) {
      $condition .= trim($key, ':') . ' = ' . $key . ' AND ';
    }
    $set = substr($set, 0, -2);
    $condition = substr($condition, 0, -5);
    $query = 'UPDATE ' . $tableName . '
              SET ' . $set . '
              WHERE ' . $condition;
    Statistics::sqlStart();
    $result = $this->dbh->prepare($query);
    $update = $result->execute(array_merge($params, $conditions));
    Statistics::sqlEnd();
    return $update;
  }

  public function delete($conditions, $tableName)
  {
    $condition = '';
    foreach ($conditions as $key => $value) {
      $condition .= trim($key, ':') . ' = ' . $key . ' AND ';
    }
    $condition = substr($condition, 0, -5);
    $query = 'DELETE FROM ' . $tableName . '
              WHERE ' . $condition;
    Statistics::sqlStart();
    $result = $this->dbh->prepare($query);
    $delete = $result->execute($conditions);
    Statistics::sqlEnd();
    return $delete;
  }

  public function checkUniqueField($value, $fieldName, $tableName, $existsId = 0) {
    $query = 'SELECT *
              FROM ' . $tableName . '
              WHERE ' . $fieldName . ' = :value';
    $params = array(':value' => $value);
    if ($existsId && is_numeric($existsId))
    {
      $query .= ' AND id <> :id';
      $params[':id'] = $existsId;
    }
    Statistics::sqlStart();
    $result = $this->dbh->prepare($query);
    $result->execute($params);
    Statistics::sqlEnd();

    return (0 == $result->rowCount()) ? true : $result->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getColumnsNames($tableName) {
    $query = "DESCRIBE $tableName";
    Statistics::sqlStart();
    $result = $this->dbh->prepare($query);
    $result->execute();
    Statistics::sqlEnd();
    return $result->fetchAll(PDO::FETCH_COLUMN);
  }

  public function quote($param)
  {
    return $this->dbh->quote($param);
  }

  public function __destruct()
  {
    if (null !== $this->dbh)
    {
      $this->dbh = null;
    }
  }
}

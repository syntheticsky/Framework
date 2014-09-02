<?php

/*
* Class: pagesRepository
*
* Interface that works with the table Topics
*/

abstract class Repository
{
  public $table;

  protected $mysql;
  protected $columns;

  public function __construct($tableName)
  {
    $this->mysql = MySql::getInstance();
    $this->table = $tableName;
    $this->columns = $this->mysql->getColumnsNames($this->table);
  }
  public function lastInsertId()
  {
    return $this->mysql->lastInsertId();
  }

  public function find($id, $tableName = '')
  {
    if (!$tableName)
    {
      $tableName = $this->table;
    }
    $query = 'SELECT *
        FROM ' . $tableName . '
        WHERE id = ?';
    return $this->mysql->select($query, array($id));
  }

//return some page of topics
  public function findAll($page = null, $tableName = '')
  {
    if (!$tableName)
    {
      $tableName = $this->table;
    }
    $result = array();
    $query = 'SELECT *
        FROM ' . $tableName . '
        WHERE 1';
    if ($page)
    {
      $page = (int)$page;
      $from = ADMIN_ITEMS_PER_PAGE * ($page - 1);
      $to = ADMIN_ITEMS_PER_PAGE;
      $query .= ' LIMIT ' . $from . ', ' . $to;
    }

    if ($rows = $this->mysql->select($query))
    {
      $result = $rows;
    }

    return $result;
  }

  public function execute($query, $params = array())
  {
    return $this->mysql->execute($query, $params);
  }

  public function select($query, $params = array())
  {
    return $this->mysql->select($query, $params);
  }

  public function insert($params, $tableName = '')
  {
    if (!$tableName)
    {
      $tableName = $this->table;
    }

    return $this->mysql->insert($params, $tableName);
  }

  public function update($params, $conditions, $tableName = '')
  {
    if (!$tableName)
    {
      $tableName = $this->table;
    }

    return $this->mysql->update($params, $conditions, $tableName);
  }

  public function delete($conditions, $tableName = '')
  {
    if (!$tableName)
    {
      $tableName = $this->table;
    }

    return $this->mysql->delete($conditions, $tableName);
  }

  public function getParams($data)
  {
    $params = array();
    foreach ($this->columns as $name)
    {
      if (isset($data[$name]))
      {
        $key = ':' . $name;
        $params[$key] = $data[$name];
      }
    }
    return $params;
  }

  public function checkUniqueField($value, $fieldName, $id = 0, $tableName = '') {
    if (!$tableName)
    {
      $tableName = $this->table;
    }
    return $this->mysql->checkUniqueField($value, $fieldName, $tableName, $id);
  }

  public function formatAlias($value, $id = 0, $prefix = '', $suffix = '')
  {
    $value = preg_replace('/[\W\d_А-Яа-я\s]/', '', $value);
    if (empty($value))
    {
      $value = 'new';
    }
    $value = strtolower($value);
    while (TRUE !== $this->checkUniqueField($value, 'alias', $id, $this->table))
    {
      $value = $this->modifyToUnique($value, $prefix, $suffix);
    }
    return $value;
  }

  public function modifyToUnique($value, $prefix = null, $suffix = null) {
    $prefix ? $value = $prefix . $value : '';
    $suffix ? $value = $value . $suffix : '';
    if (!$prefix && !$suffix) {
      $value .= 'new';
    }

    return $value;
  }

  public function sanitizeString($str)
  {
    $str = preg_replace('/[\W_\s]/', '', $str);
    return $str;
  }
}

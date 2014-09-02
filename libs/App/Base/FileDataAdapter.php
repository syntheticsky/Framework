<?php
/**
 * Clas FileDataAdapter
 */

class FileDataAdapter implements FileData
{
  private $class;

  public function __construct($type = 'mysql')
  {
    // if ($type == 'mysql')
    // {
    //   $this->class = MySql::getInstance();
    // }
    if ($type == 'json')
    {
      $this->class = new JSONData();
    }
    if ($type == 'xml')
    {
      $this->class = new XMLData();
    }
    // if ($type == 'yml')
    // {
    //   $this->class = new YMLData();
    // }
    if ($type == 'ini')
    {
      $this->class = new INIData();
    }
  }
  public function parseAllData()
  {
    $this->class->parseAllData();
  }
  public function getData()
  {
    return $this->class->getData();
  }

  public function saveData($data, $fileName, $method = NULL)
  {
    $this->class->saveData($data, $fileName, $method);
  }
}

<?php
/**
 * Clas FileDataAdapter
 */

class FileDataAdapter implements FileData
{
  private $class;

  public function __construct($config = array())
  {
    if ($config) {
      $type = $config['config']['data_type'];
      switch ($type) {
        case 'mysql':
          $this->class = MySql::getInstance();
          break;
        default:
          $class = strtoupper($type) . 'Data';
          $this->class = $class::getInstance();   
          break;
      }
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

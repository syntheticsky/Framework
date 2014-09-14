<?php

class INIData implements FileData
{
  protected static $instance;

  private $data;
  private $dataDirectory;
  private $modelFile = 'model';
  private $fileExtension = 'ini';

  public function __construct()
  {
    $this->dataDirectory = DATA_DIRECTORY;
    $this->parseAllData();
  }

  public static function getInstance()
  {
    if (null === self::$instance)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }
  
  public function parseAllData()
  {
    if ($dh = $this->openDir($this->dataDirectory))
    {
      $pathFile = $this->dataDirectory . DS . $this->modelFile . '.' . $this->fileExtension;
      if (file_exists($pathFile))
      {
        if ($data = parse_ini_file($pathFile, true))
        {
          $data = array_keys($data);
          foreach ($data as $val)
          {
            if (!file_exists($this->dataDirectory . DS . $val . '.' . $this->fileExtension))
            {
              $this->createFile($this->dataDirectory . DS . $val . '.' . $this->fileExtension);
            }
          }
          while (($file = $this->readDir($dh)) !== FALSE) {
            // echo "файл: $file : тип: " . filetype($this->dataDirectory . DS . $file) . "\n";
            if ('file' === filetype($this->dataDirectory . DS . $file) && preg_match('/(.+)\.' . $this->fileExtension . '$/i', $file, $matches))
            {
              $temp = parse_ini_file($this->dataDirectory . DS . $file, true);
              $this->arrayWalkRecurcive($temp, array($this, 'htmlspecialcharsDecode'));
              $this->data[$matches[1]] = $temp;
            }
          }
          $this->closeDir($dh);
        }
        else
        {
          die('Не найдены настройки в файле model.' . $this->fileExtension);
        }
      }
      else
      {
        die('Создайте файл model.' . $this->fileExtension . ' с нужными настройками.');
      }
    }
    else
    {
      $this->data = array();
    }
  }

  public function getData()
  {
    return $this->data;
  }

  public function saveData($data, $fileName, $method = NULL)
  {
    switch ($method) {
      case 'add':
        $result = $this->setINIString($data);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, implode("\r\n", $result), FILE_APPEND | LOCK_EX);
        break;
      case 'update':
        $allData = $this->data;
        !isset($allData[$fileName][key($data)]) ? $allData[$fileName][key($data)] = '' : '';
        $allData[$fileName][key($data)] = $data[key($data)];
        $result = $this->setINIString($allData[$fileName]);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, implode("\r\n", $result));
        break;
      case 'delete':
        $allData = $this->data;
        unset($allData[$fileName][key($data)]);
        $result = $this->setINIString($allData[$fileName]);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, implode("\r\n", $result));
        break;
      case 'insert':
        $result = $this->setINIString($data);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, implode("\r\n", $result));
        break;
    }
  }

  public function setINIString($data)
  {
    $result = array();
    if ($data)
    {
      foreach($data as $key => $val)
      {
        if(is_array($val))
        {
          $temp[] = "[$key]";
          foreach($val as $skey => $sval) $temp[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
        }
        else $result[] = "$key = " . (is_numeric($val) ? $val : '"' . $val . '"');
      }
      $temp[] = '';
      $result = array_merge($result, $temp);
    }

    return $result;
  }

  private function arrayWalkRecurcive(&$data, $funcname, $userdata = '')
  {
    array_walk_recursive($data, $funcname, $userdata);
  }

  private function htmlspecialcharsDecode(&$v, $k)
  {
    $v = htmlspecialchars_decode($v, ENT_QUOTES);
  }

  private function openDir($dir)
  {
    if (is_dir($dir)) {
      return opendir($dir);
    }
    return FALSE;
  }

  private function readDir($dirHandler)
  {
    return readdir($dirHandler);
  }

  private function closeDir($dirHandler)
  {
    closedir($dirHandler);
  }

  private function createFile($filePath)
  {
    return touch($filePath);
  }

  private function varName($var)
  {
    $trace = debug_backtrace();
    $vLine = file( __FILE__ );
    $fLine = $vLine[ $trace[0]['line'] - 1 ];
    preg_match( "#\\$(\w+)#", $fLine, $match );
    return $match;
  }
}

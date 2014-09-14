<?php

class JSONData implements FileData
{
  protected static $instance;
  
  private $data;
  private $dataDirectory;
  private $modelFile = 'model';
  private $fileExtension = 'json';

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
        if ($data = $this->jsonDecode(file_get_contents($pathFile), TRUE))
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

              $temp = $this->jsonDecode(file_get_contents($this->dataDirectory . DS . $file), TRUE);
              // $this->arrayWalkRecurcive($temp, array($this, 'htmlspecialcharsDecode'));
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
        $result = $this->jsonEncode($data);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, $result . "\r\n", FILE_APPEND | LOCK_EX);
        break;
      case 'update':
        $allData = $this->data;
        $allData[$fileName][key($data)] = $data[key($data)];
        $result = $this->jsonEncode($allData[$fileName]);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, $result);
        break;
      case 'delete':
        $allData = $this->data;
        unset($allData[$fileName][key($data)]);
        $result = $this->jsonEncode($allData[$fileName]);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, $result);
        break;
      case 'insert':
        $result = $this->jsonEncode($data);
        return file_put_contents($this->dataDirectory . DS . $fileName . '.' . $this->fileExtension, $result);
        break;
    }
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

  private function jsonDecode($data, $options = 0)
  {
    return json_decode($data, $options);
  }

  private function jsonEncode($data)
  {
    if (PHP_VERSION_ID < 50400)
    {
      return $this->rawJsonEncode($data);
    }
    else
    {
      return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
  }

  private function rawJsonEncode($input) {

    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
          return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
        },
        json_encode($input)
    );
  }
}

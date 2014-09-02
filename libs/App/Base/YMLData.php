<?php

class YMLData implements FileData
{
  private $data;
  private $dataDirectory;
  private $fileExtension = 'yml';

  public function __construct()
  {
    $this->dataDirectory = DATA_DIRECTORY;
    $this->data = $this->getData();
  }

  public function getData()
  {
    if ($dh = $this->openDir($this->dataDirectory))
    {
      while (($file = $this->readDir($dh)) !== FALSE) {
        echo "файл: $file : тип: " . filetype($this->dataDirectory . DS . $file) . "\n";
        if ('file' === filetype($this->dataDirectory . DS . $file) && preg_match('/(.+).' . $this->fileExtension . '$/', $file))
        {
          $this->data = yaml_parse_file($this->dataDirectory . DS . $file);
        }
      }
      $this->closeDir($dh);
    }
    return array();
  }

  public function saveData($data, $fileName)
  {
    $this->data = array();
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
}

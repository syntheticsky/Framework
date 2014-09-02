<?php

interface FileData
{
  public function parseAllData();
  public function getData();
  public function saveData($data, $fileName, $method);
}

<?php

/*
* Class: ModelBase
*
* Contains basic functions and methods of all models.
*/

abstract class Model
{
  protected $data = array();
  protected $repository;
  protected $mysql;
  protected $request;
  protected $helper;
  protected $config;

  public function __construct()
  {
    $this->request = Request::getInstance();
    $this->helper = Helper::getInstance();
    $this->config = $this->request->getConfig();
    if ($this->config['config']['data_type'] !== 'mysql')
    {
      $this->repository = new FileDataAdapter($this->config);
    }
  }

  public function getData()
  {
    return $this->repository->getData();
  }

  public function saveData($data, $file, $method = NULL)
  {
    return $this->repository->saveData($data, $file, $method);
  }

  protected function redirectTomain()
  {
	  header('Location: ' . $this->config['config']['site_url']);
  }

  protected function getJson()
  {
	  header("Content-type: text/json; charset=utf-8");

	  echo json_encode($this->data);
  }

//get data normal format
  protected function getDate($timeString)
  {
	  $timestamp = strtotime($timeString);
	  $dateStr = "";

	  $daysOfWeek = array(
		  "Пн", 	"Вт", 	"Ср",	"Чт",
		  "Пт",	"Сб", 	"Вс"
		  );

	  $months = array(
		  "Янв", 	"Фев", 	"Марта", 	"Апр",
		  "Мая", 		"Июня",		"Июля", 	"Авг",
		  "Сен", "Окт", 	"Ноя",	"Дек"
	  );

	  $msgDayOfFeek = $daysOfWeek[date("w", $timestamp) - 1];
	  $msgMonth	  = $months[date("m", $timestamp) - 1];
	  $msgDay 	  = date("d", $timestamp);
	  $msgYear 	  = 	date("Y", $timestamp);

	  if (date("Y") == $msgYear && date("m") ==  date("m", $timestamp) && date("d") == $msgDay)
	  {
		  $dateStr .= "Сегодня, ".date("H:i", $timestamp);
	  }
	  else
	  {
		  $dateStr .= $msgDayOfFeek." ";
		  $dateStr .= $msgDay." ";
		  $dateStr .= $msgMonth.", ";
		  $dateStr .= date("Y H:i", $timestamp)." ";
	  }

	  return $dateStr;
  }
}

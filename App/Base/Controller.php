<?php
/*
* Class: ControllerBase
*
* Contains basic functions and methods all controllers.
* Attaches necessary model and view.
*/

abstract class Controller
{
  protected $debug;
  protected $model;
  protected $view ;
  protected $request;

  private $helper;
  private $twig;
  private $image;
  private $config;

  public function __construct($pageName)
  {
    $this->request = Request::getInstance();
    $this->helper = Helper::getInstance();
    $loader = new Twig_Loader_Filesystem(TEMPALTES_DIR);
    $this->twig = new Twig_Environment($loader, array(
//        'cache'       => 'cache',
//        'auto_reload' => $this->config['config']['twig_autoreload'],
    ));
    $this->image = new \Imagine\Gd\Imagine();
    $this->config = $this->request->getConfig();
    $this->getModel($pageName);
    // $this->getView($pageName);
  }

  public function render($template, $args = array())
  {
    print $this->twig->render($template, $args);
  }

  protected function getStat()
  {
    $this->debug = array();
    if ($this->config['config']['debug'])
    {
      Statistics::totalTimerEnd();
      $this->debug['php_percents'] = Statistics::getPhpPercents();
      $this->debug['sql_percents'] = Statistics::getSqlPercents();
      $this->debug['query'] = Statistics::getSqlQueryAmount();
      $this->debug['sql'] = Statistics::getSqlTime();
      $this->debug['total'] = Statistics::getTotalTime();
      $this->debug['twig_autoreload'] = $this->config['config']['twig_autoreload'] ? 'enabled' : 'disabled';
      $this->debug['data'] = $this->config['config']['data_type'];
    }
  }

  private function getModel($pageName) {
    $modelName = "Model" . ucfirst($pageName);
    $modelPath = MODELS_DIR . DS . $modelName . '.php';
    $this->model = new $modelName();
  }

  private function getView($pageName) {
    $viewName = "View" . ucfirst($pageName);
    $viewPath = VIEWS_DIR . DS . $viewName . '.php';
    if (file_exists($viewPath))
    {
      require_once $viewPath;
      $this->view = new $viewName($pageName);
    }
  }
}

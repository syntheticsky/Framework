<?php

/*
* Class: Router
*
* Launches the desired controller
* and the desired method of this controller.
*/

class Router
{
  private $page = 'Index';
  private $action = 'index';
  private $args = null;
  private $request;
  private $helper;
  private $routes;
  private $config;

  public function __construct()
  {
    $this->request = Request::getInstance();
    $this->helper = Helper::getInstance();
    $this->config = $this->request->getConfig();
    $this->request->setSession();
    $this->routes = $this->request->getRoutes();
    $this->getRoute();
    $this->getController();
  }

  private function getRoute()
  {
    $url_parts = $this->request->getUrlParts();
    if (is_array($this->routes))
    {
      // k($this->routes);
      // k($url_parts);
      if (!empty($url_parts)) {
       if ($url_parts[0] == $this->config['config']['admin_page'])
       {
         $this->getAdminRoute();
       }
       else
       {
         
       }
      }
    }
    else
    {
      if (!empty($url_parts)) {
        $this->action = 'notFoundedAction';
      }
    }
  }

  private function getController() {
    if ($this->page == $this->config['config']['admin_page'])
    {
      $controllerName = ucfirst($this->page) . "Controller";
      $controllerPath = CONTROLLERS_DIR . $controllerName . '.php';
    }
    else
    {
      $controllerName = ucfirst($this->page) . "Controller";
      $controllerPath = CONTROLLERS_DIR . $controllerName . '.php';
    }

    if (is_file($controllerPath))
    {
      $controller = new $controllerName($this->page);
      if (method_exists($controller, $this->action))
      {
        $controller->{$this->action}($this->args);
      }
      else
      {
	       $path = $this->page == $this->config['config']['admin_page'] ? $this->config['config']['admin_page'] : '';
	       $this->request->redirectTo($path);
      }
    }
    else
    {
      $this->request->redirectTo();
    }
  }

  private function getAdminRoute()
  {
    $url_parts = $this->request->getUrlParts();
    $admin = $url_parts[0];
    unset($url_parts[0]);
    if ($url_parts) :
      $url_parts = array_reverse(array_values($url_parts));
      foreach ($url_parts as $k => $value) :
        if ($k == 0) :
          $this->action = $value;
        else:
          $this->action .= ucfirst($value);
        endif;
      endforeach;
    endif;
    $this->setPage($admin);
    $this->setAction($this->action);
  }

  private function setPage($page) {
    if ($page) {
      $this->page = $page;
    }
  }

  private function setAction($action) {
    if ($action) {
      $this->action = $action;
    }
  }
}

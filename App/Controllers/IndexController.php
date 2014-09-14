<?php

/*
* Class: ControllerIndex
*
* Controller of index page.
*/

class IndexController extends Controller
{
  private $page;

  public function __construct($pageName)
  {
    parent::__construct($pageName);
    $this->page = $pageName;
  }

  public function mainAction()
  {
    $urlParts = $this->request->getUrlParts();
    $count = count($urlParts);
    if ($count > 0)
    {
      $this->notFoundedAction();
    }
    else
    {
      $this->index();
    }
  }

  public function index()
  {
    $data = $this->model->getData();
    $this->prepareData($data);
    $this->getStat();
    $data['debug'] = $this->debug;
    $this->render('index.html.twig', $data);
  }

  private function pageAction($item)
  {
    $this->model->pageAction($item);
    $this->view->assignData($this->model->getData());
    $this->view->display();
  }

  private function nodeAction($item)
  {
    $this->model->nodeAction($item);
    $this->view->assignData($this->model->getData());
    $this->view->display();
  }

  public function notFoundedAction()
  {
    $this->getStat();
    $data['debug'] = $this->debug;
    $this->render('404.html.twig', $data);
  }

  private function prepareData(&$data, $sort = 'weight')
  {
    $sortArray = array();
    $temp = $data['blocks'];
    unset($data['blocks']);
    foreach ($temp as $key => $value)
    {
      if ($value['status'] == 0)
      {
        continue;
      }
      $sortArray[$key] = $value[$sort];
    }
    asort($sortArray);
    foreach ($sortArray as $key => $value)
    {
      $data['blocks'][$key] = $temp[$key];
    }
  }
}

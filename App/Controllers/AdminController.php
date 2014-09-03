<?php

/*
* Class: ControllerIndex
*
* Controller of index page.
*/

class ControllerAdmin extends Controller
{
  public function __construct($pageName)
  {
    parent::__construct($pageName);
    if (!$this->request->isAuthenticated() && $this->request->getUrlParts(1) != LOGIN_PATH) {
      $this->request->redirectTo(ADMIN_PAGE . DS . LOGIN_PATH);
    }
  }

  public function index()
  {
    $data = $this->model->getData();
    $menu = $this->getAdminMenu();
    $this->getStat();
    return $this->render('admin/index.html.twig', array(
      'adminMenu' => $menu,
      'debug' => $this->debug,
    ));
  }

  public function settings()
  {
    $data = $this->model->getData();
    $menu = $this->getAdminMenu();
    $this->getStat();
    return $this->render('admin/settings.html.twig', array(
      'adminMenu' => $menu,
      'settings' => $data['settings'],
      'debug' => $this->debug,
    ));
  }

  public function loadFaviconSettings()
  {
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      $files = $this->request->getUploadedFiles();
      if ($files && isset($post['add-favicon']))
      {
        $images = $this->request->moveUploadedFiles($files, UPLOAD_PATH . 'favicon' . DS);
        if (!$save = $this->model->saveData(array('favicon' => $images['file_uri']), 'settings', 'update'))
        {
          //ОШИБКА СОХРАНЕНИЯ
        }
        $this->request->redirectTo(ADMIN_PAGE . DS . 'settings');
      }
    }
  }

  public function loadLogoSettings()
  {
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      $files = $this->request->getUploadedFiles();
      if ($files && isset($post['add-logo']))
      {
        $images = $this->request->moveUploadedFiles($files, UPLOAD_PATH . 'logo' . DS);
        if (!$save = $this->model->saveData(array('logo' => $images['file_uri']), 'settings', 'update'))
        {
          //ОШИБКА СОХРАНЕНИЯ
        }
        $this->request->redirectTo(ADMIN_PAGE . DS . 'settings');
      }
    }
  }

  public function blocks()
  {
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      if (isset($post['remove']))
      {
        foreach ($post['blocks'] as $key => $value)
        {
          $currentData = $this->model->getData();
          foreach ($currentData['blocks'][$key] as $k => $v) {
            if (preg_match('/image\d+_uri/', $k))
            {
              unlink(getcwd() . DS . $currentData['blocks'][$key][$k]);
            }
          }
          $data[$key] = array();
        }
        if (!$delete = $this->model->saveData($data, 'blocks', 'delete'))
        {
          //ОШИБКА УДАЛЕНИЯ
        }
        $this->request->redirectTo(ADMIN_PAGE . DS . 'blocks');
      }
    }
    $blocks = array();
    $menu = $this->getAdminMenu();
    $data = $this->model->getData();
    if (isset($data['blocks']))
    {
      $this->sortDataByWeight($data);
      $blocks = $data['blocks'];
    }
    $this->getStat();
    return $this->render('admin/blocks.html.twig', array(
      'adminMenu' => $menu,
      'blocks' => $blocks,
      'debug' => $this->debug,
    ));
  }

  public function addBlocks()
  {
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      if (isset($post['add']))
      {
        $files = $this->request->getUploadedFiles();
        if (!isset($post['blocks']['status']))
        {
          $post['blocks']['status'] = 0;
        }
        // $post['blocks']['machineName'] = preg_replace('/[\dА-Яа-я!"№;%:?*()+-=,.\/\\\';"]/', '', $post['blocks']['machineName']);
        foreach ($post['blocks'] as $key => $value)
        {
          $data[$post['blocks']['machineName']][$key] = $value;
        }
        if ($files)
        {
          foreach ($files as $key => $file) {
            if ($images = $this->request->moveUploadedFiles(array($key => $file), UPLOAD_PATH . 'blocks' . DS . $post['blocks']['machineName'] . DS))
            {
              if ($images)
              {
                $data[$post['blocks']['machineName']][$key . '_uri'] = $images['file_uri'];
                $data[$post['blocks']['machineName']][$key . '_orig_name'] = $images['name'];
                $data[$post['blocks']['machineName']][$key . '_name'] = $post['blocks'][$key . '_name'];
              }
            }
          }
        }

        if (!$save = $this->model->saveData($data, 'blocks', 'add'))
        {
          //ОШИБКА СОХРАНЕНИЯ
        }
        $this->request->redirectTo(ADMIN_PAGE . DS . 'blocks' . DS . 'edit?machineName=' . $data[key($data)]['machineName']);
      }
    }
    $data = $this->model->getData();
    krumo($data);
    $menu = $this->getAdminMenu();
    $this->getStat();
    return $this->render('admin/blocksAdd.html.twig', array(
      'adminMenu' => $menu,
      'debug' => $this->debug,
    ));
  }

  public function editBlocks()
  {
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      if (isset($post['edit']))
      {
        $tmp = $this->model->getData();
        $currentData[$post['blocks']['machineName']] = $tmp['blocks'][$post['blocks']['machineName']];

        $files = $this->request->getUploadedFiles();
        if (!isset($post['blocks']['status']))
        {
          $post['blocks']['status'] = 0;
        }
        foreach ($post['blocks'] as $key => $value)
        {
          $data[$post['blocks']['machineName']][$key] = $value;
        }

        $data[$post['blocks']['machineName']] = array_merge_recursive($data[$post['blocks']['machineName']], array_diff_key($currentData[$post['blocks']['machineName']], $data[$post['blocks']['machineName']]));
        if (isset($post['blocks']['delete']))
        {
          foreach ($post['blocks']['delete'] as $name => $v)
          {
            unlink(getcwd() . DS . $data[$post['blocks']['machineName']][$name . '_uri']);
            unset($data[$post['blocks']['machineName']][$name . '_name'], $data[$post['blocks']['machineName']][$name . '_orig_name'], $data[$post['blocks']['machineName']][$name . '_uri']);
          }
        }
        if ($files)
        {
          foreach ($files as $key => $file) {
            if ($images = $this->request->moveUploadedFiles(array($key => $file), UPLOAD_PATH . 'blocks' . DS . $post['blocks']['machineName'] . DS))
            {
              if ($images)
              {
                $data[$post['blocks']['machineName']][$key . '_uri'] = $images['file_uri'];
                $data[$post['blocks']['machineName']][$key . '_orig_name'] = $images['name'];
                $data[$post['blocks']['machineName']][$key . '_name'] = $post['blocks'][$key . '_name'];
              }
            }
          }
        }

        if (!$update = $this->model->saveData($data, 'blocks', 'update'))
        {
          //ОШИБКА СОХРАНЕНИЯ
        }
      }
      // $this->request->redirectTo(ADMIN_PAGE . DS . 'blocks' . DS . 'edit?machineName=' . $data[key($data)]['machineName']);
    }
    if ($get = $this->request->getGet())
    {
      $data = $this->model->getData();
      if (isset($get['machineName']) && array_key_exists($get['machineName'], $data['blocks']))
      {
        $menu = $this->getAdminMenu();
        $this->getStat();
        return $this->render('admin/blocksAdd.html.twig', array(
          'adminMenu' => $menu,
          'block' => $data['blocks'][$get['machineName']],
          'debug' => $this->debug,
        ));
      }
    }
    $this->request->redirectTo(ADMIN_PAGE . DS . 'blocks');
  }

  public function deleteBlocks()
  {
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      if (isset($post['delete']))
      {
        foreach ($currentData['blocks'][$post['blocks']['machineName']] as $key => $value) {
          if (preg_match('/image\d+_uri/', $key))
          {
            unlink(getcwd() . DS . $currentData['blocks'][$post['blocks']['machineName']][$key]);
          }
        }
        foreach ($post['blocks'] as $key => $value)
        {
          $data[$post['blocks']['machineName']] = array();
        }
        if (!$delete = $this->model->saveData($data, 'blocks', 'delete'))
        {
          //ОШИБКА УДАЛЕНИЯ
        }
      }
      $this->request->redirectTo(ADMIN_PAGE . DS . 'blocks');
    }
    if ($get = $this->request->getGet())
    {
      $data = $this->model->getData();
      if (isset($get['machineName']) && array_key_exists($get['machineName'], $data['blocks']))
      {
        $menu = $this->getAdminMenu();
        $this->getStat();
        return $this->render('admin/blocksDelete.html.twig', array(
          'adminMenu' => $menu,
          'block' => $data['blocks'][$get['machineName']],
          'debug' => $this->debug,
        ));
      }
    }
    $this->request->redirectTo(ADMIN_PAGE . DS . 'blocks');
  }

  public function meta()
  {
    $data = $this->model->getData();
    if ($this->request->isMethod('post')) {
      $post = $this->request->getPost();
      if (isset($post['add']))
      {
        if (!$this->model->saveData($post['meta'], 'meta', 'insert'))
        {
          //ОШИБКА СОХРАНЕНИЯ
        }
      }
      $this->request->redirectTo(ADMIN_PAGE . DS . 'meta');
    }
    $menu = $this->getAdminMenu();
    $this->getStat();
    return $this->render('admin/meta.html.twig', array(
      'adminMenu' => $menu,
      'meta' => $data['meta'],
      'debug' => $this->debug,
    ));
  }


  public function login()
  {
    if ($this->request->isAuthenticated())
    {
      $this->request->redirectTo(ADMIN_PAGE);
    }
    if ($this->request->isMethod('post'))
    {
      $post = $this->request->getPost();
      if (isset($post['submit']))
      {
      	if ($post['login'] == ADMIN_NAME && $post['password'] == ADMIN_PASS)
      	{
      	  $this->request->setSession('user', array('name' => ADMIN_NAME, 'AUTH_OK' => true));
      	  $this->request->redirectTo(ADMIN_PAGE);
      	}
      }
    }
    // $this->model->login();
    // $this->view->assignData($this->model->getData());
    return $this->render('globals/login.html.twig');
  }

  public function logout()
  {
    if ($this->request->isAuthenticated())
    {
      unset($_SESSION['user']);
    }
    $this->request->redirectTo(ADMIN_PAGE);
  }

  public function getAdminMenu()
  {
    $data = $this->model->getData();
    $menu[''] = array('name' => 'Главная');
    $menu = array_merge($menu, $data['model']);
    $url_parts = $this->request->getUrlParts();
    (!isset($url_parts[1])) ? $url_parts[1] = '' : '';
    foreach ($menu as $key => $value)
    {
      $menu[$key]['active'] = ($url_parts[1] == $key) ? TRUE : FALSE;
    }

    return $menu;
  }

  private function sortDataByWeight(&$data)
  {
    $sortArray = array();
    $temp = $data['blocks'];
    unset($data['blocks']);
    foreach ($temp as $key => $value)
    {
      $sortArray[$key] = $value['weight'];
    }
    asort($sortArray);
    foreach ($sortArray as $key => $value)
    {
      $data['blocks'][$key] = $temp[$key];
    }
  }
}

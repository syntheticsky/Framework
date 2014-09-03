<?php
/*
* Class: Validator
*
* Initial processing of all incoming data.
* Processing Post, Get, Cookies and Sessions
*/

class Request
{
	private static $instance;

  private $config;
  private $routes;
	private $post = array();
	private $get  = array();
	private $cookie = array();
	private $server = array();
	private $session = array();
	private $files = array();
	private $imagine;

	public static function getInstance()
	{
	  if (self::$instance == null)
	  {
	    self::$instance = new self();
	  }

	  return self::$instance;
	}

	private function __construct()
	{
    $this->routes = Yaml::parse(DATA_DIRECTORY . 'route.yml');
    $this->config = Yaml::parse(__DIR__ . '/../' . 'config.yml');
		$this->imagine = new \Imagine\Gd\Imagine();
		$this->get = $this->validate($_GET);
		$this->post = $this->validate($_POST);
		$this->cookie = $this->validate($_COOKIE);
		$this->server = $_SERVER;
		$this->session = $this->setSession();
		$this->files = $this->validate($_FILES);
	}

  public function getRoutes() {
    return $this->routes;
  }

  public function getConfig() {
    return $this->config;
  }

	public function getGet($key = null)
	{
		return $this->getVar("get", $key);
	}

	public function getPost($key = null)
	{
		return $this->getVar("post", $key);
	}

	public function getUploadedFiles($key = null)
	{
		return $this->getVar('files', $key);
	}

	//public function getCookie($key)
	//{
	  //return $this->getVar("cookie", $key);
	//}

	//public function setCookie($name, $string) {
	  //$args = func_get_args();
	  //var_dump($args);
	//}

	public function getServer($key = null)
	{
	  return $this->getVar("server", $key);
	}

	public function setSession($key = null, $value = null)
	{
	  if (!$this->sessionStarted()) {
	    session_start();
	  }
	  else {
	    $key ? $_SESSION[$key] = $this->validate($value) : '';
	  }
	  $this->session = $_SESSION;
	}

	public function getSession($key)
	{
	  return $this->getVar('session', $key);
	}

	public function isAuthenticated()
	{
	  $auth = $this->getVar('session', 'user');
	  if ($auth['name'] == $this->config['config']['admin_name'] && $auth['AUTH_OK'] = true) {
	    return true;
	  }
	  return false;
	}

	public function isMethod($type = 'post')
	{
	  return (strtolower($this->getServer('REQUEST_METHOD')) === strtolower($type)) ? TRUE : FALSE;

	}

	public function getUrlParts($arg = null)
	{
    $url_string = preg_replace('#/(.+)/index\.php#', '', $this->getServer('PHP_SELF'));

	  $parts = explode('/', $url_string);
	  if (is_array($parts))
	  {
      unset($parts[0]);
	    $parts = array_values($parts);
	    if (is_null($arg))
	    {
	      return $parts;
	    }
	    elseif (isset($parts[$arg]))
	    {
	      return $parts[$arg];
	    }
	  }

	  return array();
	}

	public function redirectTo($path = null)
	{
	  header('Location: ' . SITE_URL . $path);
	  exit;
	}

	public function moveUploadedFiles($files, $uploadPath = UPLOAD_PATH, $multiple = false, $resizeW = null, $resizeH = null, $mode = 'THUMBNAIL_INSET')
	{
		$uri = $uploadPath;
		$uploadPath = getcwd() . DS . ltrim($uploadPath, '\\/');
		$images = array();
		$files = current($files);
	  if ($multiple)
	  {
	  	foreach ($files as $key => $options) {
				if ($key == 'name'){
					foreach ($options as $k => $img) {
						if ($files["tmp_name"][$k]) {
							$allowedExts = array("gif", "jpeg", "jpg", "png", "ico");
							$temp = explode(".", $files["name"][$k]);
							$name = current($temp);
							$extension = end($temp);
							if ((($files["type"][$k] == "image/gif")
								|| ($files["type"][$k] == "image/jpeg")
								|| ($files["type"][$k] == "image/jpg")
								|| ($files["type"][$k] == "image/pjpeg")
								|| ($files["type"][$k] == "image/x-png")
								|| ($files["type"][$k] == "image/png")
								|| ($files["type"][$k] == "image/x-icon"))
								&& ($files["size"][$k] < UPLOAD_MAX_SIZE)
								&& in_array($extension, $allowedExts))
							{
								if ($resizeW && $resizeH)
								{
									$size = new \Imagine\Image\Box($resizeW, $resizeH);
									$mode = \Imagine\Image\ImageInterface::$mode;
								}

	k($files, $uploadPath);
								// $iname = substr(md5(uniqid(rand())), 1, 10).".$extension";
								// $images[] = array('name'=>$iname,'type' => $files['type'][$k],'size' => $files['size'][$k]);
								// $this->createThumbs($files["tmp_name"][$k], DS . 'uploads' . DS . 'thumbs' . DS . $iname, 200, $extension);
	// move_uploaded_file($files["tmp_name"], $uploadPath . $name);
							}
						}
					}
				}
			}
	  }
	  else
	  {
			if ($files["tmp_name"]) {
				$allowedExts = array("gif", "jpeg", "jpg", "png", "ico");
				$temp = explode(".", $files["name"]);
				$name = current($temp);
				$extension = end($temp);
				if ((($files["type"] == "image/gif")
					|| ($files["type"] == "image/jpeg")
					|| ($files["type"] == "image/jpg")
					|| ($files["type"] == "image/pjpeg")
					|| ($files["type"] == "image/x-png")
					|| ($files["type"] == "image/png")
					|| ($files["type"] == "image/x-icon"))
					&& ($files["size"] < UPLOAD_MAX_SIZE)
					&& in_array($extension, $allowedExts))
				{
					if (!file_exists($uploadPath))
					{
						mkdir($uploadPath);
					}

					$this->getUniqueFileName($files['name'], $uploadPath);
					if (move_uploaded_file($files["tmp_name"], $uploadPath . $files['name']))
					{
						$images = array(
							'name' => $files['name'],
							'type' => $files['type'],
							'size' => $files['size'],
							'file_uri' => $uri . $files['name'],
							'file_path' => $uploadPath . $files['name'],
						);
						// if ($resizeW && $resizeH)
						// {
						// 	$size = new \Imagine\Image\Box($resizeW, $resizeH);
						// 	$mode = \Imagine\Image\ImageInterface::$mode;
						// 	$this->imagine->open($uploadPath . $files['name'])
				  //   		->save($uploadPath . $files['name']);
				  //   }
			    }
				}
			}
	  }
		return $images;
	}

	private function getUniqueFileName(&$name, $uploadPath)
	{
		$i = 0;
		while (file_exists($uploadPath . $name))
		{
			$tmp = explode('.', $name);
			$name = rtrim($tmp[0], '_1234567890') . '_' . $i . '.' . $tmp[1];
			$i++;
		}
	}

	private function createThumbs ($source_name, $new_name, $wN=120, $ext = "jpg")
	{
		list ($w, $h) = getimagesize ($source_name);
		// print "s: $source_name $w, $h<br>";
		$n = 1;
		$hN = ($wN * $h) / $w;

		if ($source_name) {
			if ($ext == "gif") $source = @imagecreatefromgif($source_name);
			elseif ($ext == 'jpg' || $ext == 'jpeg') $source = @imagecreatefromjpeg($source_name);
			elseif ($ext == 'png') $source = @imagecreatefrompng($source_name);
			if ($source) {
				$target = imagecreatetruecolor ($wN, $hN);
				imagecopyresampled ($target, $source, 0, 0, 0, 0, $wN, $hN, $w, $h);
				$res = imagejpeg ($target, getcwd() . $new_name, 70);
				imagedestroy($target);
			}
		}
	}

	private function sessionStarted()
	{
	  if ( php_sapi_name() !== 'cli' ) {
	    if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	      return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	    } else {
	      return session_id() === '' ? FALSE : TRUE;
	    }
	  }
	  return FALSE;
	}

	private function getVar($type, $key)
	{
	  switch (strtolower($type))
	  {
		  case 'get':
		  case 'post':
		  case 'cookie':
		  case 'server':
		  case 'session':
		  case 'files':
		  break;

		  default:
		  $type = false;
	  }
	  if ($type)
	  {
	    $key = $this->protectVar($key);

	    if ($key && array_key_exists($key, $this->$type))
	    {
	      $var = $this->$type;

	      if (!empty($var))
	      {
			return $var[$key];
	      }
	      else
	      {
			return false;
	      }
	    }
	    elseif (!$key) {
	      return $this->$type;
	    }
	    else
	    {
	      return false;
	    }
	  }
	  else
	  {
	    throw new Exception("Unknown type");
	  }
	}

	private function validate($var)
	{
	  if (is_array($var))
	  {
	    foreach ($var as $key => $val)
	    {
	      $var[$key] = $this->validate($val);
	    }
	  }
	  else
	  {
	    if (!is_numeric($var))
	    {
	      $var = $this->protectVar($var);
	    }
	  }

	  return $var;
	}

	private function protectVar($var)
	{
	  return trim(htmlspecialchars($var));
	}
}

<?php
define('DS', DIRECTORY_SEPARATOR);
//paths to necessary folders
define('HOME_DIR', __DIR__ . DS);
define('APP_DIR', __DIR__ . DS . 'App' . DS);
define('LIBS_DIR', __DIR__ . DS . 'App' .DS . 'libs' . DS);
define('MODELS_DIR', __DIR__ . DS . 'App' . DS . 'Models' . DS);
define('VIEWS_DIR', __DIR__ . DS . 'App' . DS . 'Views' . DS);
define('CONTROLLERS_DIR', __DIR__ . DS . 'App' . DS . 'Controllers' . DS);
define('BASE_DIR', __DIR__ . DS . 'App' . DS . 'Base' . DS);
define('REPOSITORY_DIR', __DIR__ . DS . 'App' . DS . 'Repository' . DS);
define('DATA_DIRECTORY', __DIR__ . DS . 'App' . DS . 'Data' . DS);
define('MISC_DIR', __DIR__ . DS . 'misc' . DS);
define('TEMPALTES_DIR', __DIR__ . DS . 'templates' . DS);
define('GLOB_TEMPLATES_DIR', __DIR__ . DS . 'templates' . DS .'globals' . DS);
define('TEMPLATES_MODULES_DIR', __DIR__ . DS . 'templates' . DS . 'modules' . DS);

define('SITE_URL', 'http://localhost/framework/');
define('ADMIN_PAGE', 'admin');
//define('AUTO_RELOAD_STATUS', TRUE);
//define('DEBUG_MODE', TRUE);
define('DATA_TYPE', 'ini');


require_once BASE_DIR . DS . 'interfaces.php';
require_once LIBS_DIR . 'Krumo' . DS . 'krumo.php';

function autoload($class_name) {
    //class directories
    $directories = array(
        APP_DIR,
        BASE_DIR,
        CONTROLLERS_DIR,
        MODELS_DIR,
        LIBS_DIR,
        LIBS_DIR . 'Twig' . DS,
        LIBS_DIR . 'Yaml' . DS,
        LIBS_DIR . 'Yaml' . DS . 'Exception',
//        LIBS_DIR . 'Doctrine',
    );
    //for each directory
    foreach($directories as $dir)
    {
        $class_name = str_replace('\\', DS, $class_name);
        if ($class_name == 'Parser') {
            var_dump( $class_name, $dir .  $class_name . '.php', is_file($dir .  $class_name . '.php'));
        }
        //see if the file exsists
        if(is_file($dir .  $class_name . '.php'))
        {
            require_once($dir . $class_name . '.php');
            //only require the class once, so quit after to save effort (if you got more, then name them something else
            return;
        }
    }
}
spl_autoload_register('autoload');

//function imagineLoader($class) {
//     $path = LIBS_DIR . DS . $class;
//     $path = str_replace('\\', DS, $path) . '.php';
//     if (file_exists($path)) {
//         include $path;
//     }
// }
// spl_autoload_register('\imagineLoader');

//require_once __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR  . '/Symfony/Component/classLoader/UniversalClassLoader.php';
//use Symfony\Component\ClassLoader\UniversalClassLoader;
//$loader = new UniversalClassLoader();
//$loader->registerNamespaces(array(
//    'libs' => __DIR__,
//    'libs\App\Base' => __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR . 'App' . DIRECTORY_SEPARATOR . 'Base',
//    'libs\App\Controllers' => __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR . 'App' . DIRECTORY_SEPARATOR . 'Controllers',
//    'Imagine\Gd' => __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR,
//    'Imagine\Image' => __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR,
//    'Symfony\Component' => __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR,
//    'Symfony\Component\Yaml' => __DIR__ . DIRECTORY_SEPARATOR . LIBS_DIR,
//));
//
//$loader->register();

// require_once LIBS_DIR . DS . 'Statistics.php';
// Statistics::totalTimerStart();

// //require_once LIBS_DIR.DS."functions.php";
// require_once LIBS_DIR . DS . 'Router.php';
// // require_once LIBS_DIR . DS . 'MySql.php';

// require_once LIBS_DIR . DS . 'Request.php';
// require_once LIBS_DIR . DS . 'Helper.php';

// // require_once BASE_DIR . DS . 'Model.php';
// // require_once BASE_DIR . DS . 'View.php';
// require_once BASE_DIR . DS . 'Controller.php';
// // require_once BASE_DIR . DS . 'Repository.php';


// require_once REPOSITORY_DIR . DS . 'indexRepository.php';
// require_once REPOSITORY_DIR . DS . 'pagesRepository.php';
// require_once REPOSITORY_DIR . DS . 'nodesRepository.php';

// require_once LIBS_DIR . DS . '/Twig/Twig_Autoloader.php';

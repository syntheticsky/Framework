<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');

require_once 'autoloader.php';

/** Start Statistic */
Statistics::totalTimerStart();

/** Twig register */
Twig_Autoloader::register();

$router = new Router();

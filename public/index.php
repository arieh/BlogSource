<?php
require_once 'autoloader.php';
require_once 'paths.php';
date_default_timezone_set('Asia/Jerusalem');
ob_start();
session_start();
session_regenerate_id();

$dbconf = json_decode(file_get_contents('../config/db.json'));

PancakeTF_PDOAccess::connect('mysql',$dbconf->host,$dbconf->dbname,$dbconf->username,$dbconf->password);
$user = new User();

$router = new Router($paths[1]);

$savant = new Savant3();
$savant->addPath('template','../includes/app/templates');
$savant->assign('base_path',$paths[0]);
$savant->assign('cdn',$paths[2]);

$debug = false;

if ($router->getFolder(0) == 'rss'){
    $router->removeFolder(0);
    $controller = new RSSController($router,$savant,new PancakeTF_PDOAccess(), $debug);
}else{
    $controller = new HTMLController($router,$savant,new PancakeTF_PDOAccess(), $debug);
}

echo $controller->generate();
ob_flush();
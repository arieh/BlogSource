<?php
require_once 'autoloader.php';
require_once '../config/paths.php';
//require_once '../config/db.php';
date_default_timezone_set('Asia/Jerusalem');
ob_start();
session_start();
session_regenerate_id();
$dbconf = json_decode(file_get_contents('../config/db.json'));
PancakeTF_PDOAccess::connect('mysql',$dbconf->host,$dbconf->dbname,$dbconf->username,$dbconf->password);
$user = new User();

$router = new Router($paths[1]);

$view = new TFView('../includes/app/templates/');
$view->assign('base_path',$paths[0]);
$view->assign('cdn',$paths[2]);

$online = false;
if ($router->getFolder(0) == 'rss'){
    $router->removeFolder(0);
    $controller = new RSSController($router,$view,new PancakeTF_PDOAccess(), $online);
}else{
	$controller = new HTMLController($router,$view,new PancakeTF_PDOAccess(), $online);
}

echo $controller->generate();
ob_flush();
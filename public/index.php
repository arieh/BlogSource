<?php
require_once 'autoloader.php';
require_once 'paths.php';
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

$controller = new HTMLController($router,$savant,new PancakeTF_PDOAccess(), false);

echo $controller->generate();
ob_flush();
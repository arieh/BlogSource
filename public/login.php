<?php
require_once('autoloader.php');
require_once('../config/paths.php');

session_start();
session_regenerate_id();

$login = (
    isset($_POST['name'])
    && isset($_POST['pass'])
    && $_POST['name'] == 'arieh-glazer'
    && sha1($_POST['pass']) == 'ff98171993d3bc05181e4b29ed0310fac5419d6f'
    );

$user = new User;
if ($login) $user->setId(2);
else{
    
}
header("Location:{$paths[0]}");
    
    
<?php
require_once('autoloader.php');
require_once('paths.php');

session_start();
session_regenerate_id();
$user = new User;
$user->setId(User::DEFAULT_ID);
header("Location:{$paths[0]}");
<?php

$controllerName = $_GET['controller'] ?? 'post';
$actionName = $_GET['action'] ?? 'showHome';

$controllerClassName = ucfirst($controllerName)."Control";
include_once "MVC/Controller/$controllerClassName.php";

$controller = new $controllerClassName();
$controller -> $actionName();


//include_once "../Controller/PostControl.php";
//$controller = new PostControl();
//$controller -> getAllPosts();
//include_once "home.php";
?>
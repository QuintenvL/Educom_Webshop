<?php
include_once 'controllers/pageController.php';
include_once 'cruds/crud.php';
session_start();
$controller = new PageController(new Crud());
$controller -> handleRequest();
?>
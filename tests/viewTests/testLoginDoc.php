<?php
include_once __DIR__.'/../../views/loginDoc.php';
include_once __DIR__.'/../../constants.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';

$model = new PageModel();
$model->page = 'login';
$activeMenuItem = new MenuItem('Login');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'login'=>$activeMenuItem);
$model->pageHeader = 'Login';
$model->showPage = true;
$model->formData = LOGIN_FORMDATA;
$model->buttonName = 'Login';

$view = new LoginDoc($model);
$view->show();
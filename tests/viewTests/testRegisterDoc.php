<?php
include_once __DIR__.'/../../views/registerDoc.php';
include_once __DIR__.'/../../constants.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';

$model = new PageModel();
$model->page = 'register';
$activeMenuItem = new MenuItem('Register');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'register'=>$activeMenuItem);
$model->pageHeader = 'Register';
$model->showPage = true;
$model->formData = REGISTER_FORMDATA;
$model->buttonName = 'Register';

$view = new RegisterDoc($model);
$view->show();
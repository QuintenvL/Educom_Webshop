<?php
include_once __DIR__.'/../../views/thanksDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';

$model = new PageModel();
$model->page = 'thanks';
$activeMenuItem = new MenuItem('Contact');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'contact'=>$activeMenuItem);
$model->pageHeader = 'Thanks';
$model->showPage = true;
$model->resultData = array('Name' => 'tester', 'Email'=> 'test@test.com', 'Comment'=>'test');

$view = new ThanksDoc($model);
$view-> show();
?>
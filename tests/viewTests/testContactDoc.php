<?php
include_once __DIR__.'/../../views/contactDoc.php';
include_once __DIR__.'/../../constants.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';

$model = new PageModel();
$model->page = 'contact';
$activeMenuItem = new MenuItem('Contact');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'contact'=>$activeMenuItem);
$model->pageHeader = 'Contact';
$model->showPage = true;
$model->formData = CONTACT_FORMDATA;
$model->buttonName = 'Submit';

$view = new ContactDoc($model);
$view->show();
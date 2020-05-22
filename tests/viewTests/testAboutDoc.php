<?php
include_once __DIR__.'/../../views/aboutDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
    
    $model = new PageModel();
    $model->page = 'about';
    $activeMenuItem = new MenuItem('About');
    $activeMenuItem->setClass('active mr-2');
    $model->menuItems = array('home'=>new MenuItem('Home'), 'about'=>$activeMenuItem);
    $model->pageHeader = 'About';
    $model->showPage = true;
    
    $view = new AboutDoc($model);
    $view->show();

?>
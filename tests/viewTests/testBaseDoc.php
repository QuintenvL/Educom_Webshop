<?php
include_once __DIR__.'/../../views/baseDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
    
    $model = new PageModel();
    $model->page = 'page1';
    $model->menuItems = array('home'=>new MenuItem('Home'));
    $model->pageHeader = 'Page 1';
    $model->showPage = true;
    
    $view = new baseDoc($model);
    $view->show();

?>
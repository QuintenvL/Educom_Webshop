<?php
include_once __DIR__.'/../../views/errorDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
    
    $model = new PageModel();
    $model->page = 'erere';
    $model->menuItems = array('home'=>new MenuItem('Home'), 'register'=>new MenuItem('register'));
    $model->pageHeader = 'erere';
    $model->showPage = true;
    
    $view = new ErrorDoc($model);
    $view->show();

?>
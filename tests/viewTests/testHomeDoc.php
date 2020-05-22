<?php
include_once __DIR__.'/../../views/homeDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
    
    $model = new PageModel();
    $model->page = 'home';
    $activeMenuItem = new MenuItem('Home');
    $activeMenuItem->setClass('active mr-2');
    $model->menuItems = array('home'=>$activeMenuItem, 'about'=>new MenuItem('About'));
    $model->pageHeader = 'Home';
    $model->showPage = true;
    
    $view = new HomeDoc($model);
    $view->show();

?>
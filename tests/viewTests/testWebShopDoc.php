<?php
include_once __DIR__.'/../../views/webShopDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
include_once __DIR__.'/../../objects/product.php';

$product = new Product();
$product->product_id = 1;
$product->name = 'Treecko';
$product->image_dir = 'http://localhost/opdracht_4.4/images/treecko.jpg';
$product->price = 834;
$model = new PageModel();
$model->page = 'shop';
$activeMenuItem = new MenuItem('Shop');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'shop'=>$activeMenuItem);
$model->pageHeader = 'Shop';
$model->showPage = true;
$model->loggedIn = true;
$model->needAddButton = true;
$model->needAmountInput = false;
$model->products = array($product);

$view = new WebShopDoc($model);
$view->show();
?>
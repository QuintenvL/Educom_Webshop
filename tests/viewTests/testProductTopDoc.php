<?php
include_once __DIR__.'/../../views/productTopDoc.php';
include_once __DIR__.'/../../constants.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
include_once __DIR__.'/../../objects/product.php';
include_once __DIR__.'/../../objects/productWithExtra.php';

$product = new Product();
$product->product_id = 1;
$product->name = 'Treecko';
$product->image_dir = 'http://localhost/opdracht_4.4/images/treecko.jpg';
$product->price = 834;
$product->total_amount = 12;
$product = new ProductWithExtra($product);
$product->setRanking(1);
$model = new PageModel();
$model->page = 'top5';
$activeMenuItem = new MenuItem('Top 5');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'top5'=>$activeMenuItem);
$model->pageHeader = 'Top 5';
$model->showPage = true;
$model->products = array($product);


$view = new ProductTopDoc($model);
$view->show();
?>
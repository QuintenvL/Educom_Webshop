<?php
include_once __DIR__.'/../../views/productDetailDoc.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../objects/menuItem.php';
include_once __DIR__.'/../../objects/product.php';

$product = new Product();
$product->product_id = 1;
$product->name = 'Treecko';
$product->image_dir = 'http://localhost/opdracht_4.4/images/treecko.jpg';
$product->price = 834;
$product->description = 'description';
$product->category = 'grass';
$product->size = 134;
$product->weight = 34341;
$product->setFormattedSize();
$product->setFormattedWeight();
$model = new PageModel();
$model->page = 'product';
$activeMenuItem = new MenuItem('Shop');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'shop'=>$activeMenuItem);
$model->pageHeader = 'Shop';
$model->showPage = true;
$model->loggedIn = true;
$model->needAddButton = true;
$model->products = array($product);

$view = new ProductDetailDoc($model);
$view->show();
?>
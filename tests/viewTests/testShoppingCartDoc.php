<?php
include_once __DIR__.'/../../views/shoppingCartDoc.php';
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
$product = new ProductWithExtra($product);
$product->setAmount(3);
$product->calculateSubTotal();
$model = new PageModel();
$model->page = 'cart';
$activeMenuItem = new MenuItem('Shopping cart');
$activeMenuItem->setClass('active mr-2');
$model->menuItems = array('home'=>new MenuItem('Home'), 'cart'=>$activeMenuItem);
$model->pageHeader = 'Shopping cart';
$model->priceTotal = $product->subTotal;
$model->showPage = true;
$model->loggedIn = true;
$model->needAmountInput = true;
$model->needAddButton = false;
$model->products = array($product);

$view = new ShoppingCartDoc($model);
$view->show();
?>
<?php

class ProductController {
    private $model;
    
    public function __construct($model, $crud){
        include_once 'models/productModel.php';
        $this->model = new ProductModel($model, $crud);
    }
    /**
     * Handle the post actions.
     */
    private function post(){
        if ($this->model->isPost){
            $this->model->handleCartActions();
            $this->model->fillMenuItems();
        }
    }
    /**
     * Determine if extra content should be shown for a product.
     */
    private function extra(){
        $this->model->isAddButtonNeeded();
        $this->model->isAmountInputNeeded();
    }
    /**
     * Perform actions for the shop page.
     */
    public function shop(){
        $this->post();
        $this->model->getShopProducts();
        $this->extra();
        include_once 'views/webShopDoc.php';
        $view = new WebShopDoc($this->model);
        $view->show();
    }
    /**
     * Perform actions for the cart page.
     */
    public function cart(){
        $this->post();
        if ($this->model->showPage){
            $this->model->getCartProducts();
            $this->extra();
        }
        include_once 'views/shoppingCartDoc.php';
        $view = new ShoppingCartDoc($this->model);
        $view->show();
    }
    /**
     * Perform actions for the detail page.
     */
    public function detail(){
        $this->post();
        $this->model->getDetailProduct();
        $this->extra();
        $this->model->setPage('shop');
        $this->model->setPageHeader($this->model->page);
        $this->model->fillMenuItems();
        include_once 'views/productDetailDoc.php';
        $view = new productDetailDoc($this->model);
        $view->show();
    }
    /**
     * Perform actions for the top page.
     */
    public function top(){
        $this->model->getTopProducts();
        $this->extra();
        $this->model->setPageHeader('Top 5');
        include_once 'views/productTopDoc.php';
        $view = new productTopDoc($this->model);
        $view->show();
    }
}

?>
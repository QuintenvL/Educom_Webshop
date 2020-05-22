<?php
include_once 'pageModel.php';
class ProductModel extends PageModel{
    
    public $products;
    public $needAddButton = false;
    public $needAmountInput = false;
    public $cartMessage;
    
    public function __construct($model, $crud){
        parent::__construct($model);
        $this->crud = $crud;
        $this->cartMessage = 'Your shopping cart is still empty. Please visit the shop to add products.';
    }
    /**
     * Get all the products and store each product in a product class.
     */
    public function getShopProducts(){
        $this->products = array();
        try {
            include_once __DIR__.'\..\cruds\productCrud.php';
            $productCrud = new ProductCrud($this->crud);
            $dbProducts = $productCrud->readAllProducts();
            $this->products = $dbProducts;
        }
        catch (PDOException $e){
            $this->exception = DATABASE_ERROR;
            $this->debugLog('Getting all the products failed with the following error: '. $e->getMessage());
            $this->debugLog = 'Getting all the products failed with the following error: '. $e->getMessage();
        }
    }
    /**
     * Get all the cart products and store each product in a product class.
     */
    public function getCartProducts(){
        $this->products = array();
        $ids =  $this->session->getProductIds();
        try {
            if (!empty($ids)){
                include_once __DIR__.'\..\cruds\productCrud.php';
                $productCrud = new ProductCrud($this->crud);
                $dbProducts = $productCrud->readProductsByIds($ids);
                $this->priceTotal = 0;
                foreach($dbProducts as $product){
                    include_once __DIR__.'\..\objects\productWithExtra.php';
                    $product = new ProductWithExtra($product);
                    $amount = $this->session->getProductQuantity($product->product_id);
                    $product->setAmount($amount);
                    $product->calculateSubTotal();
                    $this->products[$product->product_id] = $product;
                    $this->priceTotal += $product->subTotal;
                }
            }
        }
        catch (PDOException $e){
            $this->exception = DATABASE_ERROR;
            $this->debugLog('Getting all the cart products failed with the following error: '.$e->getMessage());
            $this->debugLog = 'Getting all the cart products failed with the following error: '.$e->getMessage();
        }
    }
    /**
     * Get a single product and store this product in a product class.
     */
    public function getDetailProduct(){
        $id = $this->getUrlVar('product');
        $this->products = array();
        try{
            include_once __DIR__.'\..\cruds\productCrud.php';
            $productCrud = new ProductCrud($this->crud);
            $dbProduct = $productCrud->readProductById($id);
            if (!empty($dbProduct)){
                $dbProduct->setFormattedSize();
                $dbProduct->setFormattedWeight();
                $this->products = array($dbProduct);
            }
        }
        catch (PDOException $e){
            $this->exception = DATABASE_ERROR;
            $this->debugLog('Getting a single product failed with the following error: '.$e->getMessage());
            $this->debugLog = 'Getting a single product failed with the following error: '.$e->getMessage();
        }
    }
    /**
     * Get all the products of the top list and store each product in a product class.
     */
    public function getTopProducts(){
        $this->products = array();
        try {
            include_once __DIR__.'\..\cruds\productCrud.php';
            $productCrud = new ProductCrud($this->crud);
            $dbProducts = $productCrud->readTopProducts();
            $rankingCounter = 1;
            foreach($dbProducts as $product){
                include_once __DIR__.'\..\objects\productWithExtra.php';
                $product = new ProductWithExtra($product);
                $product->setRanking($rankingCounter);
                $rankingCounter += 1;
                $this->products[$product->product_id] = $product;
            }
        }
        catch (PDOException $e){
            $this->exception = DATABASE_ERROR;
            $this->debugLog('Getting all the top products failed with the following error: '. $e->getMessage());
            $this->debugLog = 'Getting all the top products failed with the following error: '. $e->getMessage();
        }
    }
    /**
     * Perform different actions in the session and database based on the send action.
     */
    public function handleCartActions(){
        $id = $this->getPostVar('product_id') ;
        $action = $this->getPostVar('action');
        switch ($action){
            case 'add_to_cart':
                $this->session->addProductToCart($id);
                break;
            case 'change_amount':
                $amount = $this->getPostVar('amount');
                $this->session->updateProductInCart($id, $amount);
                break;
            case 'order':
                try {
                    $this->saveOrder();
                    $this->getCartProducts();
                    $this->saveProductOrders();
                    $this->session->clearCart();
                    $this->cartMessage = 'Thank you for buying our products. &#128516; Your products will be delivered soon.';   
                }
                catch (PDOException $e){
                    $this->exception = DATABASE_ERROR;
                    $this->debugLog('Saving the order failed with the following error: '.$e->getMessage());
                    $this->debugLog = 'Saving the order failed with the following error: '.$e->getMessage();
                }
                break;
        }
    }
    /**
     * Save an order in the orders table.
     */
    public function saveOrder(){
        include_once __DIR__.'\..\cruds\productCrud.php';
        $productCrud = new ProductCrud($this->crud);
        $this->orderId = $productCrud->createOrder($this->session->getUserId());
        $this->debugLog('Successfully added order with order id '.$this->orderId);
    }
    /**
     * Save for each product a product_order in the database.
     */
    public function saveProductOrders(){
        include_once __DIR__.'\..\cruds\productCrud.php';
        $productCrud = new ProductCrud($this->crud);
        $this->productOrdersIds = array();
        foreach ($this->products as $id => $product){
            $insertId = $productCrud->createProductOrder($id, $product->amount, $product->price,$this->orderId);
            array_push($this->productOrdersIds, $insertId);
            $this->debugLog('Successfully ordered '.$product->amount.' * '.$product->name.' with product order id '.$insertId);
        }
    }
    
    /**
     * Set whether the add button should be shown.
     */
    public function isAddButtonNeeded(){
        if ($this->loggedIn && ($this->page == 'shop'||$this->page == 'product')){
            $this->needAddButton = true;
        }
    }
    /**
     * Set whether the amount input should be shown.
     */
    public function isAmountInputNeeded(){
        if ($this->page == 'cart'){
            $this->needAmountInput = true;
        }
    }
}

?>
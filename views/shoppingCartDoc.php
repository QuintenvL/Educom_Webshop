<?php
include_once 'productCardDoc.php';
class ShoppingCartDoc extends ProductCardDoc{
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show the content of the shopping cart. 
     * If the cart is empty, show the page message.
     * Else show teh productscontent inside a form with a checkout button.
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        if (isset($this->model->exception)){
            $this->htmlElement('p', $this->model->exception, 'lead text-danger text-center');
        }
        else if (empty($this->model->products)){
            $this->htmlElement('p', $this->model->cartMessage, 'text-center');
        }
        else {
            $this->productsContent();
            $this->checkoutButtton();
            $this->priceTotal();
            
        }
    }
    /**
     * The products content puts each product card of a product in a div with column width specifications.
     */
    protected function middleProducts(){
        foreach ($this->model->products as $product){
            $this->htmlElementStart('div', 'col-sm-6 col-md-4 col-lg-3 mt-2');
                $this->productCard($product);
            $this->htmlElementEnd('div');
        }
    }
    /**
     * Show the checkout button for the shopping cart. 
     */
    private function checkoutButtton(){
        echo '<form action="index.php" method="post">
                    <input type="hidden" name="page" value="'.$this->model->page.'">
                    <input type="hidden" name="action" value="order">';
        echo '<button type="submit" class="mt-3 btn btn-success font-weight-light">Checkout</button>';
        $this->htmlElementEnd('form');
    }
    
    private function priceTotal(){
        $this->htmlElement('p', 'Total : &euro; '.number_format($this->model->priceTotal/100, 2), ' lead  font-weight-bold text-danger');
    }
}

?>
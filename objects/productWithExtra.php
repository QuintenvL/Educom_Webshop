<?php
include_once 'product.php';

class ProductWithExtra extends Product{
    public $amount;
    public $ranking;
    public $total_amount;
    public $subTotal;
    
    public function __construct($product){
        parent::__construct($product);
        if (isset($product->total_amount)){
            $this->total_amount = $product->total_amount;
        }
    }
    /**
     * Set the amount of the product.
     * used for the quantity in the shopping cart, but also for the time's ordered for the top list.
     *
     * @param string $amount the amount of the product.
     */
    public function setAmount ($amount){
        $this->amount = intval($amount);
    }
    /**
     * Set the ranking of the product.
     * Used on the top page.
     *
     * @param number $ranking the ranking number of the product.
     */
    public function setRanking($ranking){
        $this->ranking = $ranking;
    }
    
    public function calculateSubTotal(){
        $this->subTotal = $this->price * $this->amount;
    }
}

?>
<?php
include_once 'productsDoc.php';
abstract class ProductCardDoc extends ProductsDoc{
    protected $product;
    protected $productId;
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show a product card.
     * Set also the product data and the id to class variables. 
     * 
     * @param string $id the id of the product.
     */
    protected function productCard($product){
        $this->product = $product;
        $this->productId = $product->product_id;
        $this->beginProductCard();
        $this->bodyProductCard();
        $this->endProductCard();
    }
    /**
     * Show the begin of the product card.
     */
    private function beginProductCard(){
        $this->htmlElementStart('div', 'card border border-primary bg-transparent');
    }
    /**
     * Show the body of the product card.
     * This can be changed by child classes. 
     * Default is the body for the product in the webshop and shopping cart.
     */
    protected function bodyProductCard(){
        $this->htmlElementStart('div', 'card-body text-center');
        $this->link('card-text mt-2 lead text-decoration-none text-secondary font-weight-bold');
        $this->price('card-text text-danger font-weight-bold');
        if ($this->model->needAddButton){
            $this->addButton();
        }
        if ($this->model->needAmountInput){
            $this->amountInput();
            $this->subTotal();
        }
        $this->htmlElementEnd('div');
    }
    /**
     * Show the end of the product card.
     */
    private function endProductCard(){
        $this->htmlElementEnd('div');
    }
    /**
     * Show the price of the product
     * @param string $class the class name of the price paragraph.
     */
    protected function price($class){
        $this->htmlElement('p', '&euro;'.number_format($this->product->price/100, 2), $class);
    }
    /**
     * Show a link with the product image and name.
     * 
     * @param string $class the class name of the link.
     * @param string $id (optional) the id of the image element (needed for the css of the image). 
     */
    protected function link($linkClass, $imageClass= 'shop card-img-top img-fluid mt-2'){
        echo '<a class="'.$linkClass.'"
             href="index.php?page=product&amp;product='. $this->productId.'">'
                 .$this->image($imageClass)
                 .$this->product->name.'</a>';
    }
    /**
     * Return a string with a image element.
     * 
     * @param string $id the id of the image (needed for the css of the image).
     * @param string $class (optional) the class name of the image
     * @return string the image element
     */
    protected function image($class=''){
        return '<img '.$this->classValue($class). ' src="'. $this->product->image_dir. '" alt="'. $this->product->name. '">';
    }
    /**
     * Show the add to cart button. (only when logged in)
     * The button is shown in a form containing hidden inputs.
     * These inputs give the shopping cart all the needed information of the product.
     */
    protected function addButton(){
        if ($this->model->loggedIn){
            echo'<form action="index.php" method="post">
                    <input type="hidden" name="page" value="shop">
                    <input type="hidden" name="product_id" value="'.$this->productId.'">
                    <input type="hidden" name="action" value="add_to_cart">
                    <button type="submit" class="btn btn-primary font-weight-light" > Add to Cart
                    </button>
                 </form>';
        }
    }
    /**
     * Show the input to change the amount. 
     */
    protected function amountInput(){
        echo '<form action="index.php" method="post">
                  <input type="hidden" name="page" value="cart">
                  <input type="hidden" name="action" value="change_amount">
                  <input type="hidden" name="product_id" value="'.$this->productId.'">   
                  <input class="col-3 p-1" value="'.$this->product->amount.'" type="number" name="amount" min="0">
                  <button type="submit" class=" updateButton circle btn btn-info btn-sm rounded">Save</button>
              </form>';
    }
    
    protected function subTotal(){
        $this->htmlElement('p', 'Total : &euro;'.number_format($this->product->subTotal/100, 2), 'card-text text-success font-weight-bold');
    }
    /**
     * Show the description of a product. 
     */
    protected function description(){
        $this->htmlElement('h4','Description: ', 'text-info font-weight-bold mt-2');
        $this->htmlElement('p', $this->product->description, 'text-secondary font-weight-bold');
        $this->htmlElement('h4', 'Product details: ', 'text-info font-weight-bold');
        $detailsArray = array('category'=>$this->product->category, 'size'=>$this->product->size, 'weight'=>$this->product->weight);
        $this->list('ul', $detailsArray);
    }
}
?>
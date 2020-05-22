<?php
include_once 'productCardDoc.php';
class ProductDetailDoc extends ProductCardDoc{
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show the content of a product detail page.
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        if (empty($this->model->products)){
            $this->htmlElement('p', 'Sorry, this product cannot be found', 'font-weight-bold lead text-center text-danger');
        }
        $this->productsContent(); 
    }
    /**
     * Show a correct body of the product card on the detail page.
     * This differs from the default body content of the product card set in the ProductCardDoc.
     *  
     * {@inheritDoc}
     * @see ProductCardDoc::bodyProductCard()
     */
    protected function bodyProductCard(){
        $this->htmlElementStart('div', 'card-body');
            $this->htmlElement('h2', $this->product->name, 'card-heading text-center');
            echo $this->image('detail card-img mt-2');
            $this->price('card-text text-danger lead text-right font-weight-bold');
                $this->htmlElementStart('div', 'row justify-content-end');
                    $this->addButton();
                $this->htmlElementEnd('div');
            $this->description();
        $this->htmlElementEnd('div');
    }
}

?>
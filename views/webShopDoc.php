<?php
include_once 'productCardDoc.php';
class WebShopDoc extends ProductCardDoc{
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show the content of the webshop page. 
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        $this->htmlElement('h2', 'Welcome to the shop!', 'text-center');
        $this->productsContent();
    }
    /**
     * The products content puts each product card of a product in a div with column width specifications. 
     * 
     * {@inheritDoc}
     * @see ProductsDoc::middleProducts()
     */
    protected function middleProducts(){
        foreach ($this->model->products as $product){
            $this->htmlElementStart('div', 'col-sm-6 col-md-4 col-lg-3 mt-2');
                $this->productCard($product);
            $this->htmlElementEnd('div');
        }
    }
}

?>
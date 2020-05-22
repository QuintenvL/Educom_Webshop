<?php
    include_once 'baseDoc.php';
    abstract class ProductsDoc extends BaseDoc{
        public function __construct($model){
            parent::__construct($model);
        }
        /**
         * Show the start of the products content.
         */
        protected function startProducts(){
            $this->htmlElementStart('div', 'row card-group justify-content-center');
        }
        /**
         * Show the middle of the products content.
         * Show for each product its product card.
         * 
         */
        protected function middleProducts(){
            foreach($this->model->products as $product){
                $this->productCard($product);
            }
        }
        /**
         * Show the end of the products content.
         */
        private function endProducts(){
            $this->htmlElementEnd('div');
        }
        /**
         * Show the products content.
         * If an exception is set, show it instead of the product content.
         */
        protected function productsContent(){
            if (isset($this->model->exception)){
                $this->htmlElement('p', $this->model->exception, 'lead text-danger text-center');
            }
            else {
                $this->startProducts();
                $this->middleProducts();
                $this->endProducts();
            }
            
        }
    }
    
?>
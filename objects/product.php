<?php
class Product{
    public $product_id;
    public $name;
    public $image_dir;
    public $price;
    public $description;
    public $category;
    public $size;
    public $weight;
    
    public function __construct($product = null){
        if ($product !== null){
            $this->product_id = $product->product_id;
            $this->name = $product->name;
            $this->image_dir = $product->image_dir;
            $this->price = $product->price;
            $this->description = $product->description;
            $this->category = $product->category;
            $this->size = $product->size;
            $this->size = $product->size;
        }
    }
    
    public function setFormattedSize(){
        $this->size = number_format($this->size/100, 2) . ' m';
    }
    
    public function setFormattedWeight(){
        $this->weight = number_format($this->weight/100, 2) . ' kg';
    }
}

?>
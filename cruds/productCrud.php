<?php
include_once __DIR__.'\..\constants.php';
class ProductCrud{
    private $crud;
    public function __construct($crud){
        $this->crud = $crud;
    }
    /**
     * Create a new product in the database. TODO use and test this function.
     * 
     * @param object $product a product object with all product information.
     * @return integer the id of the inserted row.
     */
    public function createProduct($product){
        $sql = 'INSERT INTO products (name,price,image_dir, description, category, size, weight)
                VALUES (:name, :price, :image_dir, :description, :category, :size, :weight)';
        $param = array('name'=>$product->name, 'price'=>$product->price, 
                       'image_dir'=>$product->image_dir, 'description'=>$product->description, 
                       'category'=>$product->category, 'size'=>$product->size, 'weight'=>$product->weight);
        return $this->crud->createRow($sql, $param);
    }
    /**
     * Create an order for the orders table in the database.
     * 
     * @param string $userId the id of the user.
     * @return integer the id of the inserted row.
     */
    public function createOrder($userId){
        $sql = 'INSERT INTO orders (user_id) VALUES (:userId)';
        $param = array('userId'=>$userId);
        return $this->crud->createRow($sql, $param);
    }
    /**
     * Create a product order for in the product order table in the database.
     * 
     * @param string $productId the id of the product
     * @param integer $amount the amount of the product.
     * @param integer $price the price of the product at this time.
     * @param string $orderId the id of the linked order in the order table.
     * @return string the id of the inserted row.
     */
    public function createProductOrder($productId, $amount,$price, $orderId){
        $sql = 'INSERT INTO product_order (order_id, product_id, price, amount) VALUES (:orderId, :productId, :price, :amount)';
        $param = array('orderId' => $orderId, 'productId'=> $productId, 'price'=>$price, 'amount'=>$amount);
        return $this->crud->createRow($sql, $param);
    }
    /**
     * Read a single product from the database.
     * 
     * @param string $productId the id of product.
     * @return object|null the founded product stored in the Product object. Null when multiple products or none products are found.  
     */
    public function readProductById($productId){
        $sql = 'SELECT * FROM products WHERE product_id = :product_id';
        $param = array('product_id'=>$productId);
        include_once __DIR__.'/../objects/Product.php';
        return $this->crud->readOneRow($sql, $param, 'Product');
    }
    /**
     * Read all the products in the database.
     * 
     * @return array array of Product objects for all founded products.
     */
    public function readAllProducts(){
        $sql = 'SELECT * FROM products';
        $param = array();
        include_once __DIR__.'/../objects/Product.php';
        return $this->crud->readMultiRows($sql, $param, 'product_id', 'Product');
    }
    /**
     * Read the products by the given id array.
     * 
     * @param array $ids the ids of the products that should return in the result.
     * @return array array of Products objects for all founded objects. 
     */
    public function readProductsByIds($ids){
        $sql = 'SELECT * FROM products WHERE product_id in (:ids)';
        $param = array("ids" => $ids);
        include_once __DIR__.'/../objects/Product.php';
        return $this->crud->readMultiRows($sql,$param, 'product_id', 'Product');
    }
    /**
     * Read all the products for the top most ordered products.
     * 
     * @return array array of Products objects for all founded objects.
     */
    public function readTopProducts(){
        $sql = 'SELECT
                p_o.product_id,
                p.name,
                p.image_dir,
                SUM(p_o.amount) AS total_amount
                FROM (SELECT order_id FROM orders WHERE order_date >= ADDDATE(CURRENT_DATE(), INTERVAL '.TOP_TIME_INTERVAL.'))
                 AS latest_orders  
                 LEFT JOIN product_order p_o ON p_o.order_id = latest_orders.order_id  
                 LEFT JOIN products p USING(product_id) 
                 GROUP BY p_o.product_id 
                 ORDER BY total_amount DESC, p.name 
                 LIMIT :productLimit';
        $param = array('productLimit'=> PRODUCT_TOP_LIMIT);
        include_once __DIR__.'/../objects/Product.php';
        return $this->crud->readMultiRows($sql, $param, 'product_id', 'Product');
        
    }
}

?>
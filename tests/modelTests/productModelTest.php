<?php
include_once __DIR__.'/../../models/productModel.php';
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../validator.php';
include_once __DIR__.'/../../constants.php';
include_once __DIR__.'/../../objects/product.php';
include_once __DIR__.'/../testCrud.php';
include_once __DIR__.'/../../request/testRequest.php';

use PHPUnit\Framework\TestCase;

class ProductModelTest extends TestCase{
    
    /**
     * Helper function to create a product
     * @param integer $id the id of the product.
     * @return Product
     */
    private function createProduct($id){
        $product = new Product();
        $product->product_id = $id;
        $product->name = 'Product'.$id;
        $product->image_dir = 'testImage.jpg';
        $product->price = 359;
        $product->description = 'description of product';
        $product->category = 'product';
        $product->size = 12;
        $product->weight = 335;
        return $product;
    }
    /**
     * Combination of asserts to check the bindValues and Sql in the db.
     * 
     * @param Crud $db the test crud.
     * @param array $expected_bindValues expected content of bindValues.
     * @param number $expected_number expected number of entries in the arrays.
     */
    private function assertSingleQueryExecuted($db, $expected_bindValues, $expected_number = 1){
        $this->assertCount($expected_number, $db->sqlQueries, 'sqlQueries: correct length?');
        $this->assertCount($expected_number, $db->bindValues, 'bindValues: correct length?');
        if ($expected_number > 1){
            $this->assertEquals($expected_bindValues, $db->bindValues, 'is bindValues correct?');
        }
        else {
            $this->assertEquals(array($expected_bindValues), $db->bindValues, 'is bindValues correct?');
        }
    }
    /**
     * Test the construction of the productModel. 
     */
    public function test_construct(){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        
        $productModel = new ProductModel($pageModel, $db);

        $this->assertEquals('Your shopping cart is still empty. Please visit the shop to add products.', $productModel->cartMessage, 'is cart message correct?');
    }
   
    /**
     * Test the getShopProducts function.
     */
    public function test_getShopProducts(){
        $db = new TestCrud();
        $productId = 1;
        $db->arrayToReturn = array($productId=>$this->createProduct($productId));
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productModel->products = null;
        
        $productModel->getShopProducts();

        $this->assertNotEmpty($productModel->products,'is products not empty?');
        $this->assertEquals($db->arrayToReturn, $productModel->products, 'are the products correct?');
        $this->assertCount(1, $productModel->products, 'is the right amount of products set?');
        $this->assertSingleQueryExecuted($db, array());
    }
    
    /**
     * Test the getCartProducts function.
     */
    public function test_getCartProducts(){
        $db = new TestCrud();
        $productId = 1;
        $db->arrayToReturn = array($productId=>$this->createProduct($productId));
        $_SESSION['products'] = array($productId=>3);
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productModel->products = null;
        
        $productModel->getCartProducts();
        
        $this->assertNotEmpty($productModel->products,'is products not empty?');
        $this->assertCount(1, $productModel->products, 'is the right amount of products set?');
        $this->assertEquals($_SESSION['products'][1], $productModel->products[1]->amount, 'is the amount correct?');
        $this->assertSingleQueryExecuted($db, array("ids" => array(1)));
    }
    
    /**
     * Test the getTopProducts function.
     */
    public function test_getTopProducts(){
        $db = new TestCrud();
        $productId = 1;
        $db->arrayToReturn = array($productId=>$this->createProduct($productId));
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productModel->products = null;
        
        $productModel->getTopProducts();
        
        $this->assertNotEmpty($productModel->products, 'is products not empty?');
        $this->assertCount(1, $productModel->products, 'is the right amount of products set?');
        $this->assertEquals(1, $productModel->products[$productId]->ranking, 'is the ranking correct?');
        $this->assertSingleQueryExecuted($db, array('productLimit'=> PRODUCT_TOP_LIMIT));
    }
    
    /**
     * Test the getDetailProduct function.
     */
    public function test_getDetailProduct(){
        $productId = 1;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['product'] = $productId;
        $db = new TestCrud();
        $db->objToReturn = $this->createProduct($productId);
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productModel->products = null;
        
        $productModel->getDetailProduct();
        
        $this->assertNotEmpty($productModel->products, 'is products not empty?');
        $this->assertEquals(array($db->objToReturn), $productModel->products, 'contains the products variable the correct product information?');
        $this->assertSingleQueryExecuted($db, array('product_id'=> $productId));
        
    }
    
    /**
     * Test the cart action add_to_cart.
     */
    public function test_handleCartActions_addToCart(){
        $db = new TestCrud();
        $productId = 1;
        $_POST['product_id'] = $productId;
        $_POST['action'] = 'add_to_cart';
        $_SESSION['products'] = array($productId=>2);
        $_SESSION['userId'] = '1';
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productsInCart = array_sum($_SESSION['products']);
        
        $productModel->handleCartActions();
        
        $this->assertGreaterThan($productsInCart, array_sum($_SESSION['products']), 'changed the amount of products?');
    }
    
    /**
     * Test the cart action change_amount.
     */
    public function test_handleCartActions_changeAmount(){
        $db = new TestCrud();
        $productId = '1';
        $_POST['product_id'] = $productId;
        $_POST['action'] = 'change_amount';
        $_POST['amount'] = 3;
        $_SESSION['products'] = array($productId=>2);
        $_SESSION['userId'] = '1';
        $productQuantity = $_SESSION['products'][$productId];
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        
        $productModel->handleCartActions();
        
        $this->assertNotEquals($productQuantity, $_SESSION['products'][$productId], 'is the amount of a product changed?');
    }
    
    /**
     * Test the cart action change_amount with a 0 value.
     */
    public function test_handleCartActions_changeAmount_amountZero(){
        $db = new TestCrud();
        $productId = '1';
        $_POST['product_id'] = $productId;
        $_POST['action'] = 'change_amount';
        $_POST['amount'] = 0;
        $_SESSION['products'] = array($productId=>2);
        $_SESSION['userId'] = '1';
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        
        $productModel->handleCartActions();
        
        $this->assertArrayNotHasKey($productId, $_SESSION['products'], 'is product removed?');
    }
    
    /**
     * Test the cart action order.
     */
    public function test_handleCartActions_order(){
        $productId = 1;
        $db = new TestCrud();
        $db->arrayToReturn = array($productId=>$this->createProduct($productId));
        $_POST['product_id'] = $productId;
        $_POST['action'] = 'order';
        $_SESSION['products'] = array($productId=>2);
        $_SESSION['userId'] = '1';
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        
        $productModel->handleCartActions();
        
        $this->assertEquals(2,$productModel->orderId, 'is order id correct?');
        $this->assertCount(1, $productModel->productOrdersIds, 'number of productOrdersIds correct?');
        $this->assertEmpty($_SESSION['products'], 'is products removed from the session?');
        $this->assertEquals('Thank you for buying our products. &#128516; Your products will be delivered soon.', $productModel->cartMessage, 'is cart message correct?');
        $expected_bindValues = array(array('userId'=>$_SESSION['userId']), array("ids"=>array(1)), array('orderId'=>2, 'productId'=>$productId, 'price'=>359, 'amount'=>2));
        $this->assertSingleQueryExecuted($db, $expected_bindValues,3);
    }
    
    /**
     * Test the saveOrder function.
     */
    public function test_saveOrder(){
        $_SESSION['userId'] = '1';
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        
        $productModel->saveOrder();
        
        $this->assertEquals(2, $productModel->orderId, 'is orderId correct?');
        $this->assertSingleQueryExecuted($db, array('userId'=>$_SESSION['userId']));
    }
    
    /**
     * Test the saveProductOrders function
     */
    public function test_saveProductOrders(){
        $_SESSION['userId'] = '1';
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $newProduct = new Product();
        $newProduct = new ProductWithExtra($newProduct);
        $newProduct->name = 'Product1';
        $newProduct->product_id = 1;
        $newProduct->amount = 2;
        $newProduct->price = 434;
        $productModel->products = array(1=> $newProduct);
        $productModel->orderId = 2;
        
        $productModel->saveProductOrders();
        
        $this->assertCount(1, $productModel->productOrdersIds, 'is number of productOrdersIds correct?');
        $this->assertSingleQueryExecuted($db, array('orderId'=>$productModel->orderId, 'productId'=>$newProduct->product_id, 'price'=>$newProduct->price, 'amount'=>$newProduct->amount));
    }
    
    /**
     * Test the isAddButtonNeeded function.
     * 
     * @dataProvider addButtonDataProvider
     * @param string $page the page requested
     * @param boolean $expected the expected value of needAddButton
     */
    
    public function test_isAddButtonNeeded($page, $expected){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productModel->page = $page;
        $productModel->loggedIn = true;
        
        $productModel->isAddButtonNeeded();
        
        $this->assertEquals($expected, $productModel->needAddButton, 'is needAddButton as expected?');
    }
    
    public function addButtonDataProvider(){
        
        return [
            ['cart', false],
            ['shop',true]
        ];
    }
    /**
     * Test the isAmountInputNeeded function.
     * 
     * @dataProvider amountInputDataProvider
     * @param string $page the page requested
     * @param boolean $expected the expected value of needAmountInput
     */
    public function test_isAmountInputNeeded($page, $expected){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $productModel = new ProductModel($pageModel, $db);
        $productModel->page = $page;
        
        $productModel->isAmountInputNeeded();
        
        $this->assertEquals($expected, $productModel->needAmountInput, 'is needAmountInput as expected?');
    }
    
    public function amountInputDataProvider(){
        
        return [
            ['cart', true],
            ['shop',false]
        ];
    }
}
?>
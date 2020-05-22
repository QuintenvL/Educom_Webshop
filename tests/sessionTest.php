<?php
include_once __DIR__.'/../session.php';

use PHPUnit\Framework\TestCase;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class SessionTest extends TestCase{
    
    protected function setup():void{
        $_SESSION = array();
    }
    
    public function test_isLoggedIn_loggedIn(){
        $_SESSION['userName'] = 'user';
        
        $session = new Session();
        
        $result = $session->isLoggedIn();
        
        $this->assertEquals(true, $result);
    }
    
    public function test_isLoggedIn_loggedOut(){
        $session = new Session();
        
        $result = $session->isLoggedIn();
        
        $this->assertEquals(false, $result);
    }
    
    public function test_storeUserInSession(){
        $session = new Session();
        
        $session->storeUserInSession(1, 'user');
        
        $this->assertEquals(1, $_SESSION['userId'], 'userid correct?');
        $this->assertEquals('user', $_SESSION['userName'], 'userName correct?');
        $this->assertEquals(array(), $_SESSION['products'], 'products correct?');
    }
    
    public function test_logOut(){
        $session = new Session();
        $_SESSION['userName'] = 'user';
        
        $session->logOut();
        
        $this->assertEmpty($_SESSION);
    }
    
    public function test_getCartQuantity(){
        $_SESSION['products'] = array(1=>2, 2=>1);
        $session = new Session();
        
        $result = $session->getCartQuantity();
        
        $this->assertEquals(3,$result);
    }
    
    public function test_getUserName(){
        $_SESSION['userName'] = 'user';
        $session = new Session();
        
        $result = $session->getUserName();
        
        $this->assertEquals('user',$result);
    }
    
    public function test_getUserId(){
        $_SESSION['userId'] = 1;
        $session = new Session();
        
        $result = $session->getUserId();
        
        $this->assertEquals(1,$result);
    }
    
    public function test_addProductToCart_newProduct(){
        $_SESSION['products'] = array();
        $session = new Session();
        
        $session->addProductToCart(1);
        
        $this->assertEquals(array(1=>1), $_SESSION['products']);
    }
    
    public function test_addProductToCart_existingProduct(){
        $_SESSION['products'] = array(1=>1);
        $session = new Session();
        
        $session->addProductToCart(1);
        
        $this->assertEquals(array(1=>2), $_SESSION['products']);
    }
    
    public function test_getProductIds(){
        $_SESSION['products'] = array(1=>1, 2=>2);
        $session = new Session();
        
        $result = $session->getProductIds();
        
        $this->assertEquals(array(1,2), $result);
    }
    
    public function test_getProducts(){
        $_SESSION['products'] = array(1=>1, 2=>2);
        $session = new Session();
        
        $result = $session->getProducts();
        
        $this->assertEquals(array(1=>1, 2=>2), $result);
    }
    
    public function test_getProductQuantity(){
        $_SESSION['products'] = array(1=>1, 2=>2);
        $session = new Session();
        
        $result = $session->getProductQuantity(1);
        
        $this->assertEquals(1, $result);
    }
    
    public function test_updateProductInCart_nonZeroAmount(){
        $_SESSION['products'] = array(1=>1, 2=>2);
        $session = new Session();
        
        $session->updateProductInCart(1, 3);
        
        $this->assertEquals(array(1=>3, 2=>2), $_SESSION['products']);
    }
    
    public function test_updateProductInCart_zeroAmount(){
        $_SESSION['products'] = array(1=>1, 2=>2);
        $session = new Session();
        
        $session->updateProductInCart(1, 0);
        
        $this->assertEquals(array(2=>2), $_SESSION['products']);
    }
    
    public function test_clearCart(){
        $_SESSION['products'] = array(1=>1, 2=>2);
        $session = new Session();
        
        $session->clearCart();
        
        $this->assertEquals(array(), $_SESSION['products']);
    }
}

?>
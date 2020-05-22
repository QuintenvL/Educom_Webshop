<?php
class Session {
    /**
     * Check whether the user is logged in.
     * 
     * @return boolean true if the user variable is set, false otherwise.
     */
    public function isLoggedIn(){
        return isset($_SESSION['userName']);
    }
    /**
     * Store user information in the session.
     * Create also an empty array for the products variable.
     * 
     * @param string $userId the id of the user.
     * @param string $name the name of the user.
     */
    public function storeUserInSession($userId, $name){
        $_SESSION['userId'] = $userId;
        $_SESSION['userName'] = $name;
        $_SESSION['products'] = array();
    }
    /**
     * Log the user out.
     */
    public function logOut(){
        session_unset();
    }
    /**
     * Show the number of products in the shopping cart.
     * 
     * @return number the sum of the quantity of each product.
     */
    public function getCartQuantity(){
        return array_sum($_SESSION['products']);
    }
    /**
     * Get the user name stored in the session.
     * 
     * @return string the user name.
     */
    public function getUserName(){
        return $_SESSION['userName'];
    }
    /**
     * Get the user id stored in the session.
     * 
     * @return string the user id.
     */
    public function getUserId(){
        return $_SESSION['userId'];
    }
    /**
     * Add a product to the shopping cart with the given product id.
     * If the product is already in the cart, the quantity is increased.
     * 
     * @param string $id the product id 
     */
    public function addProductToCart($id){
        if (!empty($id)){
            if (isset($_SESSION['products'][$id])){
                $_SESSION['products'][$id] += 1;
            }
            else {
                $_SESSION['products'][$id] = 1;
            }
        }
    }
    /**
     * Get all the ids of the products in the session.
     * 
     * @return array ids of the products.
     */
    public function getProductIds(){
        return array_keys($_SESSION['products']);
    }
    /**
     * Get all the products from the session.
     * This includes information about the quantity.
     * 
     * @return array information about the stored products.
     */
    public function getProducts(){
        return $_SESSION['products'];
    }
    /**
     * Get the quantity of the given product id.
     * 
     * @param string $id the product id.
     * @return number the quantity of the product.
     */
    public function getProductQuantity($id){
        return $_SESSION['products'][$id];
    }
    /**
     * Updates the product quantity in the session.
     * If the quantity is smaller or equal to zero, the product will be removed.
     * 
     * @param string $id the product id. 
     * @param string $amount the new quantity of the product.
     */
    public function updateProductInCart($id, $quantity){
        if ($quantity <= '0'){
            unset($_SESSION['products'][$id]);
        }
        else {
            $_SESSION['products'][$id] = intval($quantity);
        }
    }
    /**
     * Empty the cart by unsetting the products variable in the session
     */
    public function clearCart(){
        $_SESSION['products'] = array();
    }
}

?>
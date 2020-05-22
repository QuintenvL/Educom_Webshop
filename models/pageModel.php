<?php
class PageModel {
    public $page;
    public $isPost;
    public $menuItems;
    public $loggedIn;
    public $pageHeader;
    public $exception;
    public $showPage;
    public $debugLog;
    public $session;
    public $request;
    
    /**
     * Construct the variables of the pageModel.
     * 
     * @param object|null $copy A model object with set pageModel variables. Null when creating PageModel for the first time.
     * @param object|null $request The request object needed for the getPostVar and getUrlVar functions. Null when constructing it with another model object.
     * @param string $file the file name of the debug file. By default it is debug.txt
     */
    public function __construct($copy = null, $request = null,  $file = 'debug.txt'){
        if ($copy != null){
            $this->page = $copy->page;
            $this->isPost = $copy->isPost;
            $this->menuItems = $copy->menuItems;  
            $this->pageHeader = $copy->pageHeader;
            $this->loggedIn = $copy->loggedIn;
            $this->exception = $copy->exception;
            $this->showPage = $copy->showPage;
            $this->debugLog = $copy->debugLog;
            $this->session = $copy->session;
            $this->request = $copy->request;
            $this->debugFile = $copy->debugFile;
        }
        else {
            $this->session = new Session();
            $this->request = $request;
            $this->debugFile = $file;
        }
    }
    /**
     * Write a debug message to a file.
     * 
     * @param string $message the message for in the debug file
     */
    public function debugLog($message){
        $today = date('d M Y H:i:s');
        $message = $today .' |||| '. $message;
        $debugFile = fopen($this->debugFile, 'a+');
        fwrite($debugFile, $message."\n");
        fclose($debugFile);
    }
    /**
     * Check in the session whether the user is logged in.
     */
    public function checkIfLoggedIn(){
        $this->loggedIn = $this->session->isLoggedIn();
    }
    /**
     * The requested page name will be gathered.
     * To avoid giving a XSS script back the $requested_page value is tested with testString.
     */
    public function getRequestedPage(){
        $this->isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        if ($this->isPost){
            $this->page = $this->getPostVar('page', 'home');
        }
        else {
//             var_dump(filter_input(INPUT_GET, 'page'));
            $this->page = $this->getUrlVar('page', 'home');
        }
    }
    /** 
     * Removes all useless spaces and the slashes.
     * Next it will convert all special characters to html unicode.
     * 
     * @param string $str The string that should be checked.
     * @return string A tested input
     */
    public function testString($str){
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }
    /**
     * Get back which page has been called by the POST Request.
     *
     * @param string $key the key to look for in the POST array.
     * @param mixed $default (optional)  return value if key isn't in POST array.
     * @return mixed the value of the key in POST array or the default value.
     */
    public function getPostVar($key, $default=''){
        $value = $this->request->postVar($key);
        /* This is a modern variant of $_POST[$key],
         see https://www.php.net/manual/en/function.filter-input.php
         for extra options */
        return isset($value) ? $this->testString($value) : $default;
    }
    /**
     * Get back which page has been called by the GET Request.
     * When no value is set, show the default page(home).
     *
     * @param string $key  the key to look for in the GET array
     * @param mixed $default (optional) return value if key isn't in GET array.
     * @return mixed the value of the key in GET array or the default value.
     */
    public function getUrlVar ($key, $default=''){
        $value = $this->request->urlVar($key);
        /* This is a modern variant of $_GET[$key],
         see https://www.php.net/manual/en/function.filter-input.php
         for extra options */
        return isset($value) ? $this->testString($value) : $default;
    }
    
    /**
     * Create all the information for the menu items.
     * This contains mostly the label and sometimes optional parts and classes.
     */
    public function fillMenuItems(){
        include_once __DIR__.'\..\objects\menuItem.php';
        $menuItems = array('home' => new MenuItem('Home'),
            'about' => new MenuItem('About'),
            'contact' => new MenuItem('Contact'),
            'top5' => new MenuItem('Top 5'),
            'shop' => new MenuItem('Shop')
        );
        if ($this->loggedIn){
            $menuItems['cart'] = new MenuItem('Shopping cart', $this->session->getCartQuantity(), 'small', 'fa-shopping-cart');
            $menuItems['logout'] = new MenuItem('Log out', $this->session->getUserName());
            $menuItems['logout']->setClass('text-light bg-warning font-weight-bold');
        }
        else {
            $menuItems['login'] = new MenuItem('Log in');
            $menuItems['register'] = new MenuItem('Register');
        }
        $this->menuItems = $this->addActiveClass($menuItems);
    }
    /**
     * Add a active class to the item when it is the same as the pageHeader.
     *
     * @param array $items all the menu items
     * @return array array of the menuItems with an active class for one of them.
     */
    private function addActiveClass($items){
        foreach (array_keys($items) as $name){
            if ($name == $this->page){
                $items[$name]->setClass('active mr-2');
            }
        }
        return $items;
    }
    /**
     * Set the page header to the given string.
     * PageHeader is shown at the top of the page.
     * 
     * @param string $str the value of the pageHeader.
     */
    public function setPageHeader($str){
        $this->pageHeader = $str;
    }
    /**
     * Set the page variable.
     * 
     * @param string $page the new page name.
     */
    public function setPage($page){
        $this->page = $page;
    }
    /**
     * Set the showPage variable.
     * Some pages don't need to be shown when the user is logged in or logged out.
     */
    public function shouldPageBeShown(){
        $loggedInPages = array('cart');
        $loggedOutPages = array('login', 'register');
        if ($this->loggedIn && in_array($this->page, $loggedOutPages)){
            $this->showPage = false;
        }
        else if (!$this->loggedIn && in_array($this->page, $loggedInPages)){
            $this->showPage = false;
        }
        else {
            $this->showPage = true;
        }
    }
    /**
     * Redirect the register request by changing the page name and whether the request is a post request.
     */
    public function changeRegisterRequest(){
        $this->page = 'login';
        $this->isPost = false;
        
    }

}


?>
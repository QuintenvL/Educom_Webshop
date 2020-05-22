<?php
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../request/testRequest.php';

use PHPUnit\Framework\TestCase;

class PageModelTest extends TestCase{
    /**
     * Setup the global variables to empty arrays before each test.
     */
    protected function setup():void {
        $_GET = array();
        $_POST = array();
        $_SESSION = array();
    }
    /**
     * Test the construction of a pagemodel by giving it a pageModel.
     */
    public function test_construct(){
        $firstModel = new PageModel(null, new TestRequest(),  'test.txt');
        $firstModel->page = 'home';
        $firstModel->isPost = false;
        $firstModel->loggedIn = true;
        
        $secondModel = new PageModel($firstModel);

        $this->assertEquals($firstModel->page, $secondModel->page, 'is page correct?');
        $this->assertEquals($firstModel->isPost, $secondModel->isPost, 'is isPost correct?');
        $this->assertEquals($firstModel->loggedIn, $secondModel->loggedIn, 'is loggedIn correct?');
    }
    
    /**
     * test if the debug is correctly logged.
     */
    public function test_debugLog(){
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->debugLog('test');
        
        $debugFile = fopen('test.txt', 'r');
        
        fseek($debugFile, -5, SEEK_END);
        $lastline = fgets($debugFile);
        
        fclose($debugFile);
        $lastline = substr($lastline, 0, 4);
        $this->assertEquals('test', $lastline);
    }
    /**
     * Test the getRequestedPage function for a get request.
     */
    public function test_getRequestedPage_Get(){
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['page'] = 'abcd';
        
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->getRequestedPage(); 
        
        $this->assertEquals('abcd', $model->page, 'is page correct?');

    }
    /**
     * Test the getRequestedPage function without a variable in the get request.
     */
    public function test_getRequestedPage_noPage(){
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->getRequestedPage();
        
        $this->assertEquals('home', $model->page, 'is page correct?');
    }
    /**
     * Test the getRequestedPage function for a post request
     */
    public function test_getRequestedPage_Post(){
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['page'] = '123';
        
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->getRequestedPage();
        
        $this->assertEquals('123', $model->page, 'is page correct?');
        
    }
    /**
     * Test the checkIfLoggedIn function when logged in.
     */
    public function test_checkIfLoggedIn_loggedIn(){
        $_SESSION['userName'] = 'test';
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->checkIfLoggedIn();
        
        $this->assertEquals(true, $model->loggedIn, 'is loggedIn correct?');
        
    }
    /**
     * * Test the checkIfLoggedIn function when logged out.
     */
    public function test_checkIfLoggedIn_loggedOut(){
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->checkIfLoggedIn();
        
        $this->assertEquals(false, $model->loggedIn, 'is loggedIn correct?');
    }
        

    /**
     * Test the testString function with different strings.
     * 
     * @dataProvider stringDataProvider
     * @param string $input the input string
     * @param string $output the expected output string
     */
    public function test_testString($input, $output){
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $result = $model->testString($input);
        $this->assertEquals($output, $result, 'is string correctly tested?');
        
    }
    
    public function stringDataProvider(){
        return array(
            array('  value  ', 'value'),
            array('\\value\\', 'value'),
            array('<value>', '&lt;value&gt;'),
            array('  "\\\"   ', '&quot;\&quot;')
        );
    }
    
    /**
     * Test the fillMenuItems function when logged out.
     */
    public function test_fillMenuItems_loggedOut(){
        include_once __DIR__.'\..\..\objects\menuItem.php';
        $homeMenuItem = new MenuItem('Home');
        $homeMenuItem->setClass('active mr-2');
        $expectedOutput = array('home'=>$homeMenuItem,
            'about' => new MenuItem('About'),
            'contact' => new MenuItem('Contact'),
            'top5' => new MenuItem('Top 5'),
            'shop' => new MenuItem('Shop'),
            'login' => new MenuItem('Log in'),
            'register' => new MenuItem('Register'));
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        $model->page = 'home';
        
        $model->fillMenuItems();
        
        $this->assertEquals($expectedOutput, $model->menuItems, 'are menuitems correct for logged in?');
    }
    
    /**
     * Test the fillMenuItems functions when logged in.
     */
    public function test_fillMenuItems_loggedIn(){
        $_SESSION['products'] = array('1'=>'1');
        $_SESSION['userName'] = 'Piet';
        include_once __DIR__.'\..\..\objects\menuItem.php';
        $homeMenuItem = new MenuItem('Home');
        $homeMenuItem->setClass('active mr-2');
        $logOutMenuItem = new MenuItem('Log out', 'Piet');
        $logOutMenuItem->setClass('text-light bg-warning font-weight-bold');
        $expectedOutput = array('home'=>$homeMenuItem,
            'about' => new MenuItem('About'),
            'contact' => new MenuItem('Contact'),
            'top5' => new MenuItem('Top 5'),
            'shop' => new MenuItem('Shop'),
            'cart'=> new MenuItem('Shopping cart', 1, 'small', 'fa-shopping-cart'),
            'logout'=>$logOutMenuItem);
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        $model->loggedIn = true;
        $model->page = 'home';
        
        $model->fillMenuItems();

        $this->assertEquals($expectedOutput,$model->menuItems, 'are menuitems correct for logged in?');
    }
    
    /**
     * Test the setting of the pageHeader.
     */
    public function test_setPageHeader(){
        $str = 'Header';
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        
        $model->setPageHeader($str);
        
        $this->assertEquals($str, $model->pageHeader, 'is page header correctly set?');
    }
    /**
     * Test if the page should be shown for different pages.
     * 
     * @dataProvider pageShowDataProvider
     * @param string $page the requested page
     * @param boolean $loggedIn whether the user is logged in or not
     * @param boolean $expected the expected value of showPage
     */
    public function test_shouldPageBeShown($page, $loggedIn, $expected){

        $model = new PageModel(null, new TestRequest(),  'test.txt');
        $model->page = $page;
        $model->loggedIn = $loggedIn;
        
        $model->shouldPageBeShown();
        
        $this->assertEquals($expected, $model->showPage, 'is should page be shown correct?');
    }
    /**
     * Data for the test of the shouldPageBeShown function.
     * 
     * @return array with data array(page:string, loggedIn:boolean, expected:boolean)
     */
    public function pageShowDataProvider(){
        
        return array(
            array('cart', false, false),
            array('cart', true, true),
            array('login', false, true),
            array('login', true, false),
            array('about', false, true),
            array('about', true, true)
        );
    }

    /**
     * Test for changing the reqister request.
     */
    public function test_changeRegisterRequest(){
        $model = new PageModel(null, new TestRequest(),  'test.txt');
        $model->page = 'register';
        $model->isPost = true;
        
        $model->changeRegisterRequest();

        $this->assertEquals(false, $model->isPost, 'is isPost correct?');
        $this->assertEquals('login', $model->page, 'is page correct?');
    }
}
?>
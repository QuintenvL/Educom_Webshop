<?php
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../models/userModel.php';
include_once __DIR__.'/../../models/formModel.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../testCrud.php';
include_once __DIR__.'/../../request/testRequest.php';
include_once __DIR__.'/../../validator.php';
include_once __DIR__.'/../../constants.php';

use PHPUnit\Framework\TestCase;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class UserModelTest extends TestCase{
    
    /**
     * Reset the array of the global variables.
     */
    protected function setup():void {
        $_GET = array();
        $_POST = array();
        $_SESSION = array();
    }

    /**
     * Combination of asserts to check the bindValues and Sql in the db.
     *
     * @param Crud $db the test crud.
     * @param array $expected_bindValues expected content of bindValues.
     */
    private function assertSingleQueryExecuted($db, $expected_bindValues){
        $this->assertCount(1, $db->sqlQueries, 'sqlQueries: correct length?');
        $this->assertCount(1, $db->bindValues, 'bindValues: correct length?');
        $this->assertEquals(array($expected_bindValues), $db->bindValues, 'is bindValues correct?');
    }
    
    /**
     * Test the construction of the userModel.
     */
    public function test_construct(){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel);
        $userModel = new UserModel($formModel, $db);
        
        $this->assertEquals(new User(), $userModel->user, 'correct new user?');
        
    }
    
    /**
     * Test the construction of the userModel by giving it an existing userModel.
     */
    public function test_construct_userModel(){        
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel);
        $startUserModel = new UserModel($formModel, $db);
        $startUserModel->user->name = 'Piet';
        $startUserModel->user->user_id = 1;
        
       
        $finalUserModel = new UserModel($startUserModel, $db);
        
        $this->assertEquals($startUserModel->user, $finalUserModel->user, 'user correctly copied?');
        $this->assertEquals('Piet', $finalUserModel->user->name, 'username correctly copied?');
        $this->assertEquals(1, $finalUserModel->user->user_id, 'userid correctly copied?');
    }
    
    /**
     * Test the loginUser function.
     */
    public function test_loginUser(){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel);
        $userModel = new UserModel($formModel, $db);
        $userModel->user->user_id = 1;
        $userModel->user->name = 'Piet';
        
        $userModel->loginUser();
        
        $this->assertEquals(true, $userModel->loggedIn, 'set logged in correct?');
        $this->assertEquals(1, $_SESSION['userId'], 'set user id in session?');
        $this->assertEquals('Piet', $_SESSION['userName'], 'set username in session?');
        $this->assertEquals(array(), $_SESSION['products'], 'set empty products array in session?');
        
    }
    
    /**
     * Test the logoutUser function.
     */
    public function test_logOutUser(){
        $_SESSION = array('userName'=>'Piet', 'userId'=>2, 'products'=>array());
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel);
        $userModel = new UserModel($formModel, $db);
        $userModel->user = new User(1, 'Piet');
        
        $userModel->loggedIn = true;
        
        $userModel->logOutUser();
        
        $this->assertEquals(false, $userModel->loggedIn, 'set logged in correct?');
        $this->assertEquals(array(), $_SESSION, 'is session empty?');
        $this->assertEmpty($userModel->user, 'is user removed?');
    }
    
    /**
     * Test the saveUser function.
     */
    public function test_saveUser(){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel);
        $userModel = new UserModel($formModel, $db);
        $userModel->user->user_id = null;
        $userModel->user->name = 'Piet';
        $userModel->user->email = 'piet@qmail.com';
        $userModel->user->password = 'Password';
        
        $userModel->saveUser();
        
        $this->assertEquals(2, $userModel->user->user_id, 'is userid correct?');
        $this->assertSingleQueryExecuted($db, array('name'=>'Piet', 'email'=>'piet@qmail.com', 'password'=>'Password'));
    }
    
    /**
     * Test the findUser function.
     */
    public function test_findUser(){
        $db = new TestCrud();
        $email = 'admin@qmail.com';
        $db->objToReturn = new User(2, null, $email);
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel);
        $userModel = new UserModel($formModel, $db);
        $userModel->user = null;
        
        $userModel->findUser($email);
        
        $this->assertEquals(2, $userModel->user->user_id, 'is user id correct?');
        $this->assertEquals($email, $userModel->user->email, 'is user mail correctly set?');
        $this->assertSingleQueryExecuted($db, array('email'=>$email));
    }
}
?>
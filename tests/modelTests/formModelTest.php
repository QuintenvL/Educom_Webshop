<?php
include_once __DIR__.'/../../models/pageModel.php';
include_once __DIR__.'/../../models/userModel.php';
include_once __DIR__.'/../../models/formModel.php';
include_once __DIR__.'/../testCrud.php';
include_once __DIR__.'/../../session.php';
include_once __DIR__.'/../../validator.php';
include_once __DIR__.'/../../constants.php';
include_once __DIR__.'/../../request/testRequest.php';
include_once __DIR__.'/../../objects/user.php';

use PHPUnit\Framework\TestCase;

class FormModelTest extends TestCase{
    /**
     * Test the setting of the formdata.
     * 
     * @dataProvider setFormDataProvider
     * @param string $page requested page
     * @param array $output expected form array
     */
    public function test_setFormData($page, $output){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel, $db);
        $formModel->page = $page;
        $formModel->formData = null;
        
        $formModel->setFormData();
        
        $this->assertEquals($output, $formModel->formData, 'is formdata correct?');
    }
    
    public function setFormDataProvider(){
        
        return array(
            array('contact', CONTACT_FORMDATA),
            array('login', LOGIN_FORMDATA),
            array('register', REGISTER_FORMDATA)
        );
    }
    /**
     * test the setting of input values.
     */
    public function test_setInputValues(){
        $db = new TestCrud();
        $_POST['name'] = 'Piet';
        $_POST['email'] = 'piet@qmail.com';
        $_POST['message'] = 'hello';
        $expectedOutput = array('name'=>array('value'=>'Piet'),
            'email'=>array('value'=>'piet@qmail.com'),
            'message'=>array('value'=>'hello')
        );
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel,$db);
        $formModel->formData = array('name'=>array(),
            'email'=>array(),
            'message'=>array()
        );
        
        $formModel->setInputValues();
        
        $this->assertEquals($expectedOutput, $formModel->formData, 'are the values in formdata correct?');
    }
    
    /**
     * Test the setting of the resultData.
     */
    public function test_setResultData(){
        $db = new TestCrud();
        $expectedOutput = array('Name'=>'Piet');
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel, $db);
        $formModel->formData = array('name'=>array('label'=>'Name', 'value'=>'Piet'));
        $formModel->resultData = null;
        
        $formModel->setResultData();

        $this->assertEquals($expectedOutput, $formModel->resultData, 'is resultdata correct?');
    }
    
    
    /**
     * Test the setting of the button name.
     * 
     * @dataProvider setButtonNameDataProvider
     * @param string $page the requested page
     * @param string $expected the expected buttonName
     */
    public function test_setButtonName($page, $expected){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel, $db);
        $formModel->page = $page;
        $formModel->buttonName = null;

        $formModel->setButtonName();

        $this->assertEquals($expected, $formModel->buttonName, 'is buttonName correct?');
    }
    
    public function setButtonNameDataProvider(){
        
        return array(
            array('contact', 'Submit'),
            array('login', 'Login'),
            array('register', 'Register')
        );
    }
    /**
     * Test the validation function.
     * This test checks whether the model varialbes are set correct.
     * The validataion are checked in validationTest.php
     * 
     * @dataProvider validateDataProvider
     * @param string $input the tested input field
     * @param string $value the input email value
     * @param array $validations all validations
     * @param boolean $valid the expected value of formModel->valid
     * @param boolean $isError the expected value of  formModel->isError
     * @param string|null $error the expected error message, null when the error is not expected.
     */
    public function test_validate($input, $value, $validations, $valid, $isError, $error=null){
        $db = new TestCrud();
        $pageModel = new PageModel(null, new TestRequest(),  'test.txt');
        $formModel = new FormModel($pageModel, $db);
        $formModel->formData = array($input=>array('label'=>$input, 'value'=>$value, 'validations'=>$validations));
        $formModel->user = $valid ||$input == 'password' ? new User(1, 'test', 'test@qmail.com', 'Password1!') : null;
        
        $formModel->validate();
        
        $this->assertEquals($valid, $formModel->valid, 'Valid?');
        $this->assertEquals($isError, $formModel->isError, 'IsError?');
        if ($valid){
            $this->assertArrayNotHasKey('error', $formModel->formData[$input], 'Is error set?');
        }
        else {
            $this->assertEquals($error, $formModel->formData[$input]['error'], 'Is error correct?');
        }
    }
    
    public function validateDataProvider(){
        
        return array(
            array('email','', array('isEmpty'), false, true, 'email is required.'),
            array('email','valid@qmail.com', array('isEmpty'), true, false),
            array('email','invalid', array('isEmpty', 'validEmail'), false, true, 'email contains an invalid email.'),
            array('email','valid@qmail.com', array('isEmpty', 'validEmail'), true, false)
        );
        
    }

}
?>
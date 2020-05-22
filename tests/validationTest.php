<?php
include_once __DIR__.'/../validator.php';
include_once __DIR__.'/../objects/user.php';
include_once __DIR__.'/../request/testRequest.php';
include_once __DIR__.'/../constants.php';

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase{
    /**
     * @dataProvider validationDataProvider
     * @param string $function the validation function name
     * @param string $value the input value
     * @param object|null $user a user object or null
     * @param string|null $expected the expected output
     */
    public function test_validation($function, $value, $user, $expected){
        $validator = new Validator();
        $label = 'Label';
        $_POST['controlPassword'] = 'Password1!';
        $request = new TestRequest();
        
        $result = $validator->$function($label, $value, $user, $request);
        
        $this->assertEquals($expected,$result);
    }
    
    public function validationDataProvider(){
        $label = 'Label';
        $user = new User(1, 'user', 'good@email.com', 'Password1!');
        return array(
            array('isEmpty', '', null, $label.' is required.'),
            array('isEmpty', 'value', null, null),
            array('lettersWhitespaceOnly', '4343', null, $label. ' may only contain letters and white spaces.'),
            array('lettersWhitespaceOnly', 'value', null, null),
            array('validEmail', 'value', null, $label. ' contains an invalid email.'),
            array('validEmail', 'value@qmail.com', null, null),
            array('registeredEmail', 'notregistered@email.com', null, $label.' is not registered.'),
            array('registeredEmail', 'good@email.com', $user, null),
            array('passwordOfUser', 'password', null, null),
            array('passwordOfUser', 'password', $user, $label.' is incorrect.'),
            array('passwordOfUser', 'Password1!', $user, null),
            array('arePasswordsTheSame', 'password', null, 'Passwords are not the same.'),
            array('arePasswordsTheSame', 'Password1!', null, null),
            array('isEmailAlreadyUsed', 'used@email.com', $user, $label.' is already used.'),
            array('isEmailAlreadyUsed', 'good@email.com', null, null),
            array('validPassword', 'Pa1!', null, $label.' is too short.'),
            array('validPassword', str_pad('Pa1!',  256, "Pa1!"), null, $label.' is too long.'),
            array('validPassword', 'Passw!', null, $label.' contains no number.'),
            array('validPassword', 'pass1!', null, $label.' contains no uppercase.'),
            array('validPassword', 'PASS1!', null, $label.' contains no lowercase.'),
            array('validPassword', 'Pa1Pa1', null, $label.' contains no symbol.'),
            array('validPassword', 'Password1!', null, null)
            
        );
        
    }
    
}

?>
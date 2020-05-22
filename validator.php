<?php
class Validator{
    /**
     * Give a error message if the value is empty.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function isEmpty($label, $value, $user, $request){
        if(empty($value)){
            return $label. ' is required.';
        }
        return null;
    }
    /**
     * Give a error message if the value contains not only letters and whitespaces.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function lettersWhitespaceOnly($label, $value, $user, $request){
        if (!preg_match("/^[a-zA-Z ]*$/",$value)){
            return $label. ' may only contain letters and white spaces.';
        }
        return null;
    }
    /**
     * Give a error message if the value is an invalid email.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function validEmail($label, $value, $user, $request){
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)){
            return "$label contains an invalid email.";
        }
        return null;
    }
    /**
     * Check is the email is registered.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function registeredEmail($label, $value, $user, $request){
        if (!$user){
            return $label.' is not registered.';
        }
        return null;
    }
    
    /**
     *  A login validation on the password.
     * $value will be compared with the stored password.
     * When there isn't a user the validation cannot be done.
     * If the passwords aren't the same an error is returned.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function passwordOfUser($label, $value, $user, $request){
        if (isset($user)){
            $storedPassword = $user->password;
            if (!password_verify($value, $storedPassword)){
                return "$label is incorrect.";
            }
        }
        return null;
    }
    /**
     *  A register password validation
     * Depending on which password is in the value (based on the label), the other password will be get.
     * These values ($value and $otherPassword) will be compared.
     * If they differ, an error will be returned.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function arePasswordsTheSame($label, $value, $user, $request){
        $otherPassword = strpos($label, "Repeat") !== false ? $request->postVar('password') : $request->postVar('controlPassword');
        if ($value !== $otherPassword){
            return 'Passwords are not the same.';
        }
        return null;
    }
    /** A validation whether the email has been used before.
     * If a user already exist with the given $value (in this case the email), an error will be returned.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.s
     */
    public static function isEmailAlreadyUsed($label, $value, $user, $request){
        if ($user){
            return "$label is already used.";
        }
        return null;
    }
    /**
     * Checks if a password is valid by checking multiple validations
     * With a do while loop each validation will be checked.
     * The loop stops if an error has been found.
     *
     * @param string $label  the label of the input field
     * @param string $value  the value of the input field
     * @param array $user user object retrieved by query on email address
     * @param object $request a class with function for getting variables from the request.
     * @return string the error message when invalid, or null when valid.
     */
    public static function validPassword($label, $value, $user, $request){
        $counter = 0;
        $validationArray = array('isTooShort',
            'isTooLong',
            'containsNumber',
            'containsUppercase',
            'containsLowercase',
            'containsSymbol');
        $numberOfPasswordValidations = count($validationArray);
        do {
            $validation = $validationArray[$counter];
            $error = self::$validation($label, $value);
            $counter++;
            if (!empty($error)){
                return $error;
            }
        } while ($counter < $numberOfPasswordValidations);
        return null;
    }
    /**
     *  PASSWORDVALIDATION: give error when password is too short.
     *
     * @param string  $label  the label of the input field.
     * @param string $value  the value of the input field.
     * @return string the error message when invalid, or null when valid.
     */
    private function isTooShort($label, $value) {
        if (strlen($value) <= MIN_LEN_PASSWORD) {
            return "$label is too short.";
        }
        return null;
    }
    /**
     * PASSWORDVALIDATION: give error when password is too long
     *
     * @param string  $label  the label of the input field.
     * @param string $value  the value of the input field.
     * @return string the error message when invalid, or null when valid.
     */
    private function isTooLong($label,$value) {
        if (strlen($value) > MAX_LEN_PASSWORD) {
            return "$label is too long.";
        }
        return null;
    }
    /**
     * PASSWORDVALIDATION: give error when the password doesn't contain a number:
     *
     * @param string  $label  the label of the input field.
     * @param string $value  the value of the input field.
     * @return string the error message when invalid, or null when valid.
     */
    private function containsNumber($label, $value){
        if (!preg_match("#[0-9]+#", $value)){
            return "$label contains no number.";
        }
        return null;
    }
    /**
     * PASSWORDVALIDATION: give error when the password doesn't contain an uppercase.
     *
     * @param string  $label  the label of the input field.
     * @param string $value  the value of the input field.
     * @return string the error message when invalid, or null when valid.
     */
    private function containsUppercase($label, $value){
        if (!preg_match("#[A-Z]+#", $value)){
            return "$label contains no uppercase.";
        }
        return null;
    }
    /**
     * PASSWORDVALIDATION: give error when the password doesn't contain a lowercase.
     * @param string  $label  the label of the input field.
     * @param string $value  the value of the input field.
     * @return string the error message when invalid, or null when valid.
     */
    private function containsLowercase($label, $value){
        if (!preg_match("#[a-z]+#", $value)){
            return "$label contains no lowercase.";
        }
        return null;
    }
    /**
     * PASSWORDVALIDATION: give error when the password doesn't contain a symbol.
     *
     * @param string  $label  the label of the input field.
     * @param string $value  the value of the input field.
     * @return string the error message when invalid, or null when valid.
     */
    private function containsSymbol($label, $value){
        if (!preg_match("#\W+#", $value)){
            return "$label contains no symbol.";
        }
        return null;
    }
}
?>
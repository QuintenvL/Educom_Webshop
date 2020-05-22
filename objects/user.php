<?php
class User{
    public $name;
    public $email;
    public $password;
    public $user_id;
    
    public function __construct($userId = null, $name = null, $email = null, $password = null){
        if (!empty($userId)){
            $this->name = $name;
            $this->email = $email;
            $this->password = password_hash($password, PASSWORD_BCRYPT);
            $this->user_id = $userId;
        }
    }
    
    public function getUserName(){
        return $this->name;
    }
}

?>
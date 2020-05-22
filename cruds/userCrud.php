<?php
class UserCrud{
    private $crud;
    public function __construct($crud){
        $this->crud = $crud;
    }
    /**
     * Create a new user in the database.
     * 
     * @param object $user the user object with information like name, email and password.
     * @return string The insert id of the query.
     */
    public function createUser($user){
        $sql = 'INSERT INTO users (name,email, password)
                VALUES (:name, :email, :password)';
        $param = array('name'=>$user->name, 'email'=>$user->email, 'password'=>$user->password);
        return $this->crud->createRow($sql, $param);
    }
    /**
     * Read the user by the given email.
     * 
     * @param string $email the emain of the searched for user.
     * @return object|null the founded user stored in a User object. When no user or multiple users are found, null will be returned.
     */
    public function readUserByEmail($email){
        $sql = 'SELECT * FROM users WHERE email = :email';
        $param = array('email'=>$email);
        include_once __DIR__.'/../objects/User.php';
        return $this->crud->readOneRow($sql, $param, 'User');
    }
}

?>
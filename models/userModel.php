<?php
include_once 'formModel.php';
class UserModel extends FormModel{
      public $user;
      /**
       * When the model already has a user stored (in case of the form), copy that user.
       * When there is formdata in the received model, create new variables which can be used to save the user.
       * 
       * @param object $model 
       */
      public function __construct($model, $crud){
          parent::__construct($model);
          $this->crud = $crud;
          if (isset($model->user)){
              $this->user = $model->user;
          }
          else {
              include_once __DIR__.'\..\objects\user.php';
              $this->user = new User();
          }
      }
      /**
       * Log in the user by setting the user in the session.
       * Also change the loggedIn variable of the page model.
       */
      public function loginUser(){
          $this->session->storeUserInSession($this->user->user_id, $this->user->name);
          $this->loggedIn = $this->session->isLoggedIn();
      }
      /**
       * Log the user out by asking the session to remove all its variables.
       * Second change the loggedIn variable of the page model.
       * Also clear the model variables.
       */
      public function logOutUser(){
          $this->session->logOut();
          $this->loggedIn = $this->session->isLoggedIn();
          $this->user = null;
      }
      /**
       * Save the user by giving the form data values to the database.
       */
      public function saveUser(){
          try {
              
              include_once __DIR__.'\..\cruds\userCrud.php';
              $userCrud = new UserCrud($this->crud);
              $userId = $userCrud->createUser($this->user);
              if ($userId == 0){
                  $this->debugLog('Failed to save the user');
              }
              else {
                  $this->user->user_id = $userId;
                  $this->debugLog('Successfully saved a user in the database with id: '.$userId);
              }
          }
          catch (PDOException $e) {
              $this->exception = DATABASE_ERROR;
              $this->debugLog('Saving the user failed with the following error: '. $e->getMessage());
              $this->debugLog = 'Saving the user failed with the following error: '. $e->getMessage();
          }
          
      }
      /**
       * Find a user by the given email.
       * 
       * @param string $email the email of the searched for user.
       */
      public function findUser($email){
          try {
              include_once __DIR__.'\..\cruds\userCrud.php';
              $userCrud = new UserCrud($this->crud);
              $this->user = $userCrud->readUserByEmail($email);
          }
          catch (PDOException $e){
              $this->exception = DATABASE_ERROR;
              $this->debugLog('Finding a user by email failed with the following error: '. $e->getMessage());
              $this->debugLog = 'Finding a user by email failed with the following error: '.$e->getMessage();
          }
      }
      
      public function createUser(){
          if (isset($this->formData)){
              $this->user = new User();
              $this->user->name =  $this->formData['name']['value'];
              $this->user->email = $this->formData['email']['value'];
              $this->user->password = password_hash($this->formData['password']['value'], PASSWORD_BCRYPT);
          }
      }

}
?>
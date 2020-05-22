<?php
include_once 'formController.php';

class UserController extends FormController{
   protected $model;
   private $crud;
   
   public function __construct($model, $crud){
       include_once 'models/userModel.php';
       $this->model = new UserModel($model, $crud);
       $this->crud = $crud;
   }
   
   public function getExtraInformation(){
       $this->model->findUser($this->model->formData['email']['value']);
   }
   
   public function contactForm(){
       $this->validateForm();
       if ($this->model->valid){
           $this->model->setResultData();
           $this->model->setPageHeader('Thanks');
           include_once 'views/thanksDoc.php';
           $view = new ThanksDoc($this->model);
           $view->show();
       }
       else{
           if ($this->model->exception){
               $this->model->setFormData();
           }
           include_once 'views/contactDoc.php';
           $view = new ContactDoc($this->model);
           $view->show();
       }
   }
   
   public function loginForm(){
       $this->validateForm();
       if ($this->model->valid){
           include_once 'userController.php';
           $controller = new UserController($this->model, $this->crud);
           $controller->login();
       }
       else{
           if ($this->model->exception){
               $this->model->setFormData();
           }
           include_once 'views/LoginDoc.php';
           $view = new LoginDoc($this->model);
           $view->show();
       }
   }
   
   public function registerForm(){
       $this->validateForm();
       if ($this->model->valid){
           $this->model->createUser();
           include_once 'userController.php';
           $controller = new UserController($this->model, $this->crud);
           $controller->register();
       }
       else{
           if ($this->model->exception){
               $this->model->setFormData();
           }
           include_once 'views/RegisterDoc.php';
           $view = new RegisterDoc($this->model);
           $view->show();
       }
   }
   
   /**
    * Perform actions to log in the user.
    */
   public function login(){
       $this->model->loginUser();
       $this->model->setPage('home');
       $this->model->setPageHeader($this->model->page);
       $this->model->fillMenuItems();
       include_once 'views/homeDoc.php';
       $view = new HomeDoc($this->model);
       $view ->show();
   }
   /**
    * Perform actions to log out the user.
    */
   public function logout(){
       $this->model->logOutUser();
       $this->model->setPage('home');
       $this->model->setPageHeader($this->model->page);
       $this->model->fillMenuItems();
       include_once 'views/homeDoc.php';
       $view = new HomeDoc($this->model);
       $view->show();
   }
   /**
    * Perform actions to register the user.
    */
   public function register(){
       $this->model->saveUser();
       
       if(!$this->model->exception){
           $this->model->changeRegisterRequest();
           $this->model->setPageHeader('login');
           $this->model->fillMenuItems();
       }
       include_once 'userController.php';
       $controller = new UserController($this->model, $this->crud);
       $controller->loginForm();
       
   }
   
}


?>
<?php
//==============================================
// CONSTANTS
//==============================================
define('CONTACT_FORMDATA', array ('name' => array('label' => 'Name','type'=>'text', 'value'=>'','validations' => array('isEmpty', 'lettersWhitespaceOnly')),
    'email' => array('label' => 'Email','type'=>'email','value'=>'', 'validations' => array('isEmpty', 'validEmail')),
    'comment' => array('label' => 'Comment','type'=>'textarea', 'value'=>'','validations' => array('isEmpty'))));

define ('LOGIN_FORMDATA', array ("email" => array( 'label' => 'Email', 'type' => 'email','value'=>'', 'validations' => array('isEmpty', 'validEmail', 'registeredEmail')),
    "password" => array( 'label' => 'Password', 'type' => 'password', 'value'=>'','validations' => array('isEmpty', 'validPassword', 'passwordOfUser'))));

define ('REGISTER_FORMDATA', array ("name" => array('label' => 'Name', 'type' => 'text', 'value'=>'','validations' => array('isEmpty', 'lettersWhitespaceOnly')),
    "email" => array( 'label' => 'Email', 'type' => 'email','value'=>'', 'validations' => array('isEmpty', 'validEmail', 'isEmailAlreadyUsed')),
    "password" => array( 'label' => 'Password', 'type' => 'password','value'=>'', 'validations' => array('isEmpty', 'validPassword', 'arePasswordsTheSame')),
    "controlPassword" => array('label' => 'Repeat password', 'type'=> 'password','value'=>'', 'validations' => array('isEmpty', 'validPassword', 'arePasswordsTheSame'))));

define ('MIN_LEN_PASSWORD', 5);
define ('MAX_LEN_PASSWORD', 255);


define('DATABASE_ERROR', 'Sorry we have issues with the database. Please try again later.');
define('PRODUCT_TOP_LIMIT', 5);
define('TOP_TIME_INTERVAL', '-7 day');
?>
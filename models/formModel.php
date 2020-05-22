<?php

include_once 'pageModel.php';
class FormModel extends PageModel {
    public $valid = false;
    public $formData;
    public $buttonName;
    public $resultData;
    public $isError=false;
    
    
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Set the formdata based on the page.
     * Formdata is defined in constants.
     */
    public function setFormData(){
        include_once __DIR__.'\..\constants.php';
        switch ($this->page) {
            case 'contact':
                $this->formData = CONTACT_FORMDATA; break;
            case 'login':
                $this->formData = LOGIN_FORMDATA; break;
            case 'register':
                $this->formData = REGISTER_FORMDATA; break;
        }
    }
    /**
     * Store the values of the input fields in the form data.
     */
    public function setInputValues(){
        foreach (array_keys($this->formData) as $inputName){
            $this->formData[$inputName]['value'] = $this->getPostVar($inputName);
        }
    }
    /**
     * Create a result data array with the input field name and it's value.
     */
    public function setResultData(){
        $this->resultData = array();
        foreach(array_keys($this->formData) as $name){
            $this->resultData[$this->formData[$name]["label"]] = $this->formData[$name]["value"];
        }
    }
    /**
     * Perform the validations of each input field.
     * The validations are stored in the validator class which contains static functions.
     */
    public function validate(){
        $user = isset($this->user)? $this->user: null;
        foreach ($this->formData as $name=>$info){
            foreach($info['validations'] as $validation){
                include_once __DIR__.'\..\validator.php';
                $error = Validator::$validation($info['label'], $info['value'], $user, $this->request);
                if (!empty($error)){
                    $this->valid = false;
                    $this->formData[$name]['error'] = $error;
                    $this->isError = true;
                    break;
                }
            }
        }
        if(!$this->isError){$this->valid = true;}
    }
    
    /**
     * Set the button name based on which page should be loaded.
     */
    public function setButtonName(){
        switch ($this->page) {
            case 'login':
                $this->buttonName =  'Login'; break;
            case 'register':
                $this->buttonName =  'Register'; break;
            default:
                $this->buttonName =  'Submit'; break;
        }
    }
    

    


}

?>
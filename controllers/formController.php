<?php
abstract class FormController{
    
    public function validateForm(){
        $this->model->setFormData();
        $this->model->setButtonName();
        
        if ($this->model->isPost){
            $this->model->setInputValues();
            $this->getExtraInformation();
            $this->model->validate();
        }
    }
    
    public abstract function getExtraInformation();

}
?>
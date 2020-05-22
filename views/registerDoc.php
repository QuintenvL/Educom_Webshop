<?php
include_once 'formDoc.php';
class RegisterDoc extends FormDoc{
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show the content of the register page. 
     * It contains a form and a list of the password requirements.
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        $this->formContent();
        $this->htmlElement('p', "Password requirements:", 'mt-2 lead ');
        $passwordRequirements = array('at least 1 lower case letter',
            'at least 1 upper case letter',
            'at least 1 number',
            'at least 1 symbol',
            'not longer than '. MAX_LEN_PASSWORD. ' characters',
            'not shorter than '. MIN_LEN_PASSWORD. ' characters');
        $this->list('ul', $passwordRequirements, 'text-secondary');
    }
}

?>
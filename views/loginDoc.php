<?php
include_once 'formDoc.php';
class LoginDoc extends FormDoc{
    public function __construct($data){
        parent::__construct($data);
    }
    /**
     * Show the content of the login page which contains a form.
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        $this->formContent();
    }
}

?>
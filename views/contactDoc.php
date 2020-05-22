<?php
include_once 'formDoc.php';
class ContactDoc extends FormDoc{
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * The main content of the body for the contact page
     * Contains a form
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        $this->htmlElement('h4', 'Please fill in the contact form.', 'text-center');
        $this->formContent();
    }
}

?>
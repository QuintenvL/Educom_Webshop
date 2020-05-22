<?php
include_once 'baseDoc.php';
class ThanksDoc extends BaseDoc{
    public function __construct($model){
        parent::__construct($model);
    }
    /**
     * Show the content of the thanks page. (show after successfully submitting the contact form).
     * 
     * {@inheritDoc}
     * @see BaseDoc::middleMainContent()
     */
    protected function middleMainContent(){
        $this->htmlElement('p', 'Thank you for submitting the form!', 'lead');
        $this->list('ul', $this->model->resultData);
    }
}
?>
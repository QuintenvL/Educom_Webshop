<?php
    require_once 'baseDoc.php';
    class ErrorDoc extends BaseDoc{
        public function __construct($model){
            parent::__construct($model);
        }
        /**
         * Show the content of the error page.
         * {@inheritDoc}
         * @see BaseDoc::middleMainContent()
         */
        protected function middleMainContent(){
            $this->htmlElement('p', 'Page['.$this->model->page.'] not found', 'text-center lead text-danger');
        }
    }

?>
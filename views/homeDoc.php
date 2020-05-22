<?php
    require_once 'baseDoc.php';
    class HomeDoc extends BaseDoc{
        public function __construct($model){
            parent::__construct($model);
        }
        /**
         * Show the content of the home page.
         * 
         * {@inheritDoc}
         * @see BaseDoc::middleMainContent()
         */
        protected function middleMainContent(){
            $this->htmlElementStart('div', 'jumbotron');
                $this->htmlElement('h2', 'Welcome to my first website!', 'text-secondary text-center');
            $this->htmlElementEnd('div');
        }
    }

?>
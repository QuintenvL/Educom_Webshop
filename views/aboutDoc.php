<?php
    require_once 'baseDoc.php';
    class AboutDoc extends BaseDoc{
        public function __construct($model){
            parent::__construct($model);
        }
        /**
         * Show all personal details for the about page.
         */
        protected function middleMainContent(){
            $this->htmlElementStart('div','py-2 jumbotron');
                $this->htmlElement('h4', 'About me');
                    $this->htmlElement('p','My name is Quinten van Langen and I\'m 23 years old.');
                    $this->htmlElement('p','I live in Nieuw Vennep together with my parents, brother and sister.');
                $this->htmlElement('h4', 'Hobbies');
                    $content = array("Gaming", "Jigsaw puzzles", "Building Lego sets");
                    $this->list("ul", $content);
            $this->htmlElementEnd('div');
        }
    }

?>
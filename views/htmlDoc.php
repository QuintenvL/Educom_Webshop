<?php

    Class HtmlDoc {
        /**
         * Begin with a html document.
         */
        private function beginDoc(){
            echo '<!doctype html>
                  <html>';
        }
        /**
         * Begin with a header element.
         */
        private function beginHeader(){
            echo ' <head>';
        }
        /**
         * Show the content of the header element.
         */
        protected function headerContent(){
            echo '';
        }
        /**
         * Close the header element.
         */
        private function endHeader(){
            echo '</head>';
        }
        /**
         * Begin with the body element.
         */
        private function beginBody(){
            echo ' <body class="bg">
                   <div class="container-fluid">';
        }
        /**
         * Show the content of the body element.
         */
        protected function bodyContent(){
            echo '';
        }
        /**
         * Close the body element.
         */
        private function endBody(){
            echo '</div>
                  </body>';
        }
        /**
         * Close the html document.
         */
        private function endDoc(){
            echo '</html>';
        }
        /**
         * SHow a complete html document.
         */
        public function show(){
            $this-> beginDoc();
            $this->beginHeader();
            $this->headerContent();
            $this->endHeader();
            $this->beginBody();
            $this->bodyContent();
            $this->endBody();
            $this->endDoc();
        }
    }
?>
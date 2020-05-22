<?php
    require_once 'elementsDoc.php';
    Class BaseDoc extends ElementsDoc{
        protected $model;
        
        public function __construct($model){
            $this->model = $model;
        }
        
        /**
         * Show the title of the webpage.
         */
        private function title(){
            $this->htmlElement('title', 'Opdracht 4.4 - '. $this->model->pageHeader);
        }
        /**
         * Show the meta information about the author.
         */
        private function metaAuthor(){
            echo '<meta  name = "author" content = "Quinten van Langen"/>';
        }
        /**
         * Include all necessary css links.
         */
        private function cssLinks(){
            echo '<meta name="viewport" content="width=device-width, initial-scale=1">
                  <link rel="stylesheet" href="/opdracht_4.4/styles/styles.css">
                  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
                  <link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" media="all">
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>';
        }
        /**
         * Show the header of the body.
         */
        private function bodyHeader(){
            $this-> htmlElement('h1', ucfirst($this->model->pageHeader), 'text-center d-none d-md-block');
        }
        /**
         * Show the menu navigation bar with the menulinks in the data.
         */
        private function mainMenu(){
            $this->htmlElementStart('nav', 'navbar navbar-expand-md');
                echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsible">';
                    $this->htmlElement('span', ' Menu', 'fas fa-bars');
                $this->htmlElementEnd('button');
                echo '<div class=" mt-2 collapse navbar-collapse" id="collapsible">';
                    $this->menuLinks();
                $this->htmlElementEnd('div');
            $this->htmlElementEnd('nav');
        }
        /**
         * Show all the menu links in a list
         *
         */
        private function menuLinks(){
            $menuLinks = array();
            foreach($this->model->menuItems as $link => $info) {
                
                array_push($menuLinks,$this->menuLink($link, $info));
            }
            $this->list("ul", $menuLinks, "nav nav-pills", 'nav-item');
        }
        /**
         * Show the link of a menu item.
         * By default the menu link contains a link to the page and it's label.
         * For the shopping cart and the logout link the label can contain extra elements, which will not be shown on smaller screens
         *
         *@param string $link The link name shown in the URL
         *@param string $info The information about the menuItem
         *@return string A link for in the menu item.
         */
        private function menuLink($link, $info){
            $label = $info->label;
            $class = 'nav-link '.$info->class;
            $icon = isset($info->faIcon)?'<i class="fas '. $info->faIcon.' d-none d-md-inline mr-1"></i> ': '' ;
            $optionalClass = isset($info->optionalClass)? $info->optionalClass: '';
            $optional = isset($info->optional) ? ' <span class="'.$optionalClass.' d-none d-md-inline">'.$info->optional.'</span>':'';
            return '<a class="'.$class.'" href="index.php?page='. $link. '">' . $icon.$label.$optional.'</a> ' ;
        }
        /**
         * Show the debug (for developing purposes)
         */
        private function debugLog(){
            if (isset($this->model->debugLog)){
                echo 'Exception: '. $this->model->debugLog;
            }
        }
        
        /**
         * Show the mainContent of the page.
         */
        private function mainContent(){
            if ($this->model->showPage){
                $this-> beginMainContent();
                $this-> middleMainContent();
                $this-> endMainContent();
            }
            else {
                $this->htmlElement('p', 'Page['.$this->model->page.'] not found', 'text-center lead text-danger');
            }
        }
        /**
         * Each page has a default padding.
         */
        private function beginMainContent(){
            $this->htmlElementStart('div', 'px-4');
        }
        /**
         * The middle of the main content is created by each specific document class.s
         */
        protected function middleMainContent(){
            echo '';
        }
        /**
         * Close the div of the main content.
         */
        private function endMainContent(){
            $this->htmlElementEnd('div');
        }
        /**
         * show the footer of the page.
         */
        private function bodyFooter(){
            $this->htmlElement('footer', '&copy; 2019 Quinten van Langen', 'bg-dark text-white text-right px-2 mt-2');
        }
        /**
         * Make the header contain the title, metaauthor and the css links.
         * 
         * {@inheritDoc}
         * @see HtmlDoc::headerContent()
         */
        protected function headerContent(){
            $this->title();
            $this->metaAuthor();
            $this->cssLinks();
        }
        /**
         * The body contains the header, menu, maincontent and the footer.
         * 
         * {@inheritDoc}
         * @see HtmlDoc::bodyContent()
         */
        protected function bodyContent(){
            $this->debugLog();
            $this->bodyHeader();
            $this->mainMenu();
            $this->mainContent();
            $this->bodyFooter();
        }
    }
?>
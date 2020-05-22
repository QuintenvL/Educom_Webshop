<?php
    class PageController{
        private $model;
        
        public function __construct($crud){
            include_once 'models/pageModel.php';
            include_once 'session.php';
            include_once 'request/pageRequest.php';
            $this->model = new PageModel(null, new PageRequest());
            $this->crud = $crud;
        }
        /**
         * Create the right data for the views
         */
        public function handleRequest(){
            $this->model->getRequestedPage();
            $this->model->setPageHeader($this->model->page);
            $this->model->checkIfLoggedIn();
            $this->model->fillMenuItems();
            $this->model->shouldPageBeShown();
            switch($this->model->page){
                case 'home':
                    include_once 'views/homeDoc.php';
                    $view = new HomeDoc($this->model);
                    $view-> show();
                    break;
                case 'about':
                    include_once 'views/aboutDoc.php';
                    $view = new AboutDoc($this->model);
                    $view-> show();
                    break;
                case 'contact':
                    include_once 'controllers/userController.php';
                    $controller = new UserController($this->model, $this->crud);
                    $controller->contactForm();
                    break;
                case 'login':
                    include_once 'controllers/userController.php';
                    $controller = new UserController($this->model, $this->crud);
                    $controller->loginForm();
                    break;
                case 'register':
                    include_once 'controllers/userController.php';
                    $controller = new UserController($this->model, $this->crud);
                    $controller->registerForm();
                    break;
                case 'logout':
                    include_once 'controllers/userController.php';
                    $controller = new UserController($this->model, $this->crud);
                    $controller->logout();
                    break;
                case 'shop':
                    include_once 'controllers/productController.php';
                    $controller = new ProductController($this->model, $this->crud);
                    $controller->shop();
                    break;
                case 'cart':
                    include_once 'controllers/productController.php';
                    $controller = new ProductController($this->model, $this->crud);
                    $controller->cart();
                    break;
                case 'product':
                    include_once 'controllers/productController.php';
                    $controller = new ProductController($this->model, $this->crud);
                    $controller->detail();
                    break;
                case 'top5':
                    include_once 'controllers/productController.php';
                    $controller = new ProductController($this->model, $this->crud);
                    $controller->top();
                    break;
                default:
                    include_once 'views/errorDoc.php';
                    $view = new ErrorDoc($this->model);
                    $view->show();
            }
        }
    }
?>
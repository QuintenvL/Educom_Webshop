<?php
    include_once 'baseDoc.php';
    abstract class FormDoc extends BaseDoc{
        protected $formData;
        public function __construct($model){
            parent::__construct($model);
            $this->formData = $this->model->formData;
        }
        /** 
         * Show the start of the form. 
         * The hidden input contains the page value, this way the correct page will be shown after submitting.
         */
        private function beginForm(){
            echo '<form action="index.php" method="post">
                  <input type="hidden" name="page" value="'.$this->model->page.'">';
        }
        /**
         * Show the content of a form
         * If an exception occurded, this will be shown before the form content.
         */
        protected function formContent(){
            if (isset($this->model->exception)){$this->htmlElement('p', $this->model->exception, 'lead text-danger text-center');}
            $this->beginForm();
            $this->formInputs();
            $this->endForm();
        }
        /**
         * Show the end of the form, which contains the submit button.
         *
         * @param string $class the class names for the submit button, by default a button with the primary color
         */
        private function endForm($class ='btn btn-primary font-weight-light'){
            echo '<button type="submit" class="'.$class.'">'.$this->model->buttonName.
            '</button></form>';
        }
        /**
         * Show all the inputs of the form.
         *  Each input field combines it's own label, input and error part in a div tag.
         *
         * @param array $metadata  the metadata of the input fields.
         */
        private function formInputs(){
            foreach ($this->formData as $name=>$array){
                $this->htmlElementStart('div', 'form-group');
                if (array_key_exists('label', $array)) {
                    $this->formLabel($name, $array['label']);
                }
                $value = $array['value'];
                $this->formInput($name,$array["type"],$value);
                if (array_key_exists('error', $array)){
                    $this-> formError($array["error"]);
                }
                $this->htmlElementEnd('div');
            }
        }
        /**
         * Show the label of the input field.
         *
         * @param string $name the name of the input field
         * @param string $label  the label for the input field starting with a uppercase character.
         */
        private function formLabel($name, $label){
            echo '<label for="'. $name .'">'. $label . ':</label>';
        }
        /**
         * A function to show the input depending on its type.
         * 
         * @param string $name  the name of the input field.
         * @param string $type  the type of the input field.
         * @param string $value  the value for in the input field.
         */
        private function formInput($name, $type, $value){
            if ($type == "textarea")
            {
                echo '<textarea class="form-control col-8" id="'. $name . '" name="'. $name .'">'. $value . '</textarea>';
            }
            else
            {
                echo '<input class="form-control col-8" id="'.$name.'" type="'.$type.'" name="'.$name.'" value="'.$value.'">';
            }
        }
        /** 
         * Show the error message next to the input field.
         * 
         * @param string  $error  The error message.
         */
        private function formError($error){
            echo '<span class="text-danger"> * '.$error.'</span>';
        }
    }

?>
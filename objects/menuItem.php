<?php

class MenuItem{
    public $label;
    public $class = 'border border-primary mr-2 mb-2 text-dark';
    public $faIcon;
    public $optional;
    public $optionalClass;
    
    public function __construct($label, $optional = null, $optionalClass = null, $faIcon = null){
        $this->label = $label;
        $this->optional = $optional;
        $this->optionalClass = $optionalClass;
        $this->faIcon = $faIcon;
    }
        
    public function setClass($class){
        $this->class = $class;
    }
    
}

?>
<?php
include_once 'requestInterface.php';

class PageRequest implements IRequest{
    public function postVar($key){
        return filter_input(INPUT_POST, $key);
    }
    public function urlVar($key){
        return filter_input(INPUT_GET, $key);
    }
}
?>
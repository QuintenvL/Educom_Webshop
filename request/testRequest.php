<?php
include_once 'requestInterface.php';

class TestRequest implements IRequest{
    public function postVar($key){
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }
    public function urlVar($key){
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
}
?>
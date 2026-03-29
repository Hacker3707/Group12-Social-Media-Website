<?php 
class AppController {

    protected function isPost(){
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet(){
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

}

?>
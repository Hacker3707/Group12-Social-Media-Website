<?php
class Category {
    private $CategoryID;
    private $CategoryName;

    public function __construct($CategoryID = null, $CategoryName = ""){
        $this->CategoryID = $CategoryID;
        $this->CategoryName = $CategoryName;
    }

    public function getCategoryID(){
        return $this->CategoryID;
    }

    public function setCategoryID($CategoryID){
        $this->CategoryID = $CategoryID;
    }

    public function getCategoryName(){
        return $this->CategoryName;
    }

    public function setCategoryName($CategoryName){
        $this->CategoryName = $CategoryName;
    }

    public function __toString(){
        return "Category(ID={$this->CategoryID}, Name={$this->CategoryName})";
    }
}
?>
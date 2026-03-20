<?php
class Category {
    private $categoryId;
    private $categoryName;


    public function __construct($categoryName = "", $categoryId = null){
        $this->categoryId = $categoryId; // AUTO_INCREMENT -> không bắt buộc
        $this->categoryName = $categoryName;
    }

    
    public function getCategoryId(){
        return $this->categoryId;
    }

    public function setCategoryId($categoryId){
        $this->categoryId = $categoryId;
    }

    public function getCategoryName(){
        return $this->categoryName;
    }

    public function setCategoryName($categoryName){
        $this->categoryName = $categoryName;
    }

    
    public function __toString(){
        return "Category(id={$this->categoryId}, name={$this->categoryName})";
    }
}
?>
<?php
class Category{
    private $categoryId;
    private $categoryName;
    private $description;

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

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function __construct(){
        $this->categoryId = "";
        $this->categoryName = "";
        $this->description = "";
    }

    public function __toString(){
        return "Category(id=$this->categoryId, name=$this->categoryName, description=$this->description)";
    }
}
?>
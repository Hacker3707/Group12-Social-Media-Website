<?php
include_once "MVC/Model/CategoryModel.php";
include_once "Entity/Category.php";

class CategoryService {

    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    // ================= CREATE =================
    public function createCategory($categoryName) {

        if (empty($categoryName)) {
            return false;
        }

        // Check trùng
        if ($this->categoryModel->existsByName($categoryName)) {
            return false;
        }

        return $this->categoryModel->insert($categoryName);
    }


    // ================= GET ALL =================
    public function getAllCategories() {

        $list = $this->categoryModel->getAll();

        return $list ? $list : [];
    }


    // ================= GET BY ID =================
    public function getCategoryById($categoryId) {

        return $this->categoryModel->getById($categoryId);
    }


    // ================= UPDATE =================
    public function updateCategory($categoryId, $categoryName) {

        if (empty($categoryName)) {
            return false;
        }

        // Check trùng (trừ chính nó)
        if ($this->categoryModel->existsByName($categoryName, $categoryId)) {
            return false;
        }

        return $this->categoryModel->update($categoryId, $categoryName);
    }


    // ================= DELETE =================
    public function deleteCategory($categoryId) {

        return $this->categoryModel->delete($categoryId);
    }
}
?>
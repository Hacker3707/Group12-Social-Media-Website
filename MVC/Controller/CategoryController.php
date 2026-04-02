<?php
include_once "MVC/Model/CategoryModel.php";

class CategoryController {

    private $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    // gọi từ index.php
    public function handleRequest() {

        if (!isset($_GET['action'])) {
            return;
        }

        $action = $_GET['action'];

        // gọi method động
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            echo "Invalid action";
        }
    }


    // ================= CREATE =================
    public function create() {

        if (isset($_POST['categoryName'])) {

            $categoryName = $_POST['categoryName'];

            $result = $this->model->createCategory($categoryName);

              header("Location: index.php?controller=category&action=list");
        exit;
        }
        
    }


    // ================= LIST =================
    public function list() {

        $categories = $this->model->getAll();

         include "MVC/View/category_view.php";
        }
    }


    // ================= DELETE =================
    public function delete() {

        if (isset($_GET['id'])) {

            $categoryId = (int)$_GET['id'];

            $result = $this->model->delete($categoryId);

            header("Location: index.php?controller=category&action=list");
        exit;
    }
    }
    // ================= EDIT (LOAD DATA) =================
    public function edit() {

        if (isset($_GET['id'])) {

            $categoryId = (int)$_GET['id'];

            $category = $this->model->getById($categoryId);

            if ($category == null) {
                echo "Category not found";
                return;
            }

           include "MVC/View/category_view.php";
        }
    }


    // ================= UPDATE =================
    public function update() {

        if (isset($_POST['categoryId']) && isset($_POST['categoryName'])) {

            $categoryId = (int)$_POST['categoryId'];
            $categoryName = $_POST['categoryName'];

            $result = $this->model->update($categoryId, $categoryName);

            header("Location: index.php?controller=category&action=list");
        exit;
        }
    }

?>
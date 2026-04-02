<?php
include_once "MVC/Service/CategoryService.php";

class CategoryController {

    private $service;

    public function __construct() {
        $this->service = new CategoryService();
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

            $result = $this->service->createCategory($categoryName);

            echo "<script>alert('$result'); window.location.href='index.php?controller=category&action=list';</script>";
        }
    }


    // ================= LIST =================
    public function list() {

        $categories = $this->service->getAllCategories();

        echo "<h2>Category List</h2>";

        foreach ($categories as $cat) {
            echo $cat . "<br>"; // dùng __toString()
        }
    }


    // ================= DELETE =================
    public function delete() {

        if (isset($_GET['id'])) {

            $categoryId = (int)$_GET['id'];

            $result = $this->service->deleteCategory($categoryId);

            echo "<script>alert('$result'); window.location.href='index.php?controller=category&action=list';</script>";
        }
    }


    // ================= EDIT (LOAD DATA) =================
    public function edit() {

        if (isset($_GET['id'])) {

            $categoryId = (int)$_GET['id'];

            $category = $this->service->getCategoryById($categoryId);

            if ($category == null) {
                echo "Category not found";
                return;
            }

            // form sửa
            echo "
                <h2>Edit Category</h2>
                <form method='POST' action='Group12-Social-Media-Website/index.php?controller=category&action=update'>
                    <input type='hidden' name='categoryId' value='{$category->getCategoryID()}'>
                    <input type='text' name='categoryName' value='{$category->getCategoryName()}'>
                    <button type='submit'>Update</button>
                </form>
            ";
        }
    }


    // ================= UPDATE =================
    public function update() {

        if (isset($_POST['categoryId']) && isset($_POST['categoryName'])) {

            $categoryId = (int)$_POST['categoryId'];
            $categoryName = $_POST['categoryName'];

            $result = $this->service->updateCategory($categoryId, $categoryName);

            echo "<script>alert('$result'); window.location.href='index.php?controller=category&action=list';</script>";
        }
    }
}
?>
<?php
include_once "MVC/Model/Category.php";
include_once "MVC/Module/db_module.php";

class CategoryService {

    // ================= CREATE =================
    public function createCategory($categoryName) {

        if (empty($categoryName)) {
            return "Category name is required";
        }

        $link = null;
        taoKetNoi($link);

        // Check trùng tên
        $checkQuery = "SELECT * FROM category 
                       WHERE CategoryName = '$categoryName'";

        $checkResult = chayTruyVanTraVeDL($link, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            giaiPhongKetNoi($link);
            return "Category already exists";
        }

        // Insert
        $query = "INSERT INTO category (CategoryName)
                  VALUES ('$categoryName')";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Created successfully" : "Create failed";
    }


    // ================= GET ALL =================
    public function getAllCategories() {

        $link = null;
        taoKetNoi($link);

        $query = "SELECT * FROM category";

        $result = chayTruyVanTraVeDL($link, $query);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Category(
                $row['CategoryID'],
                $row['CategoryName']
            );
        }

        giaiPhongKetNoi($link);

        return $list;
    }


    // ================= GET BY ID =================
    public function getCategoryById($categoryId) {

        $link = null;
        taoKetNoi($link);

        $query = "SELECT * FROM category 
                  WHERE CategoryID = $categoryId";

        $result = chayTruyVanTraVeDL($link, $query);

        if ($row = mysqli_fetch_assoc($result)) {

            $category = new Category(
                $row['CategoryID'],
                $row['CategoryName']
            );

            giaiPhongKetNoi($link);
            return $category;
        }

        giaiPhongKetNoi($link);
        return null;
    }


    // ================= UPDATE =================
    public function updateCategory($categoryId, $categoryName) {

        if (empty($categoryName)) {
            return "Category name is required";
        }

        $link = null;
        taoKetNoi($link);

        // Check trùng (trừ chính nó)
        $checkQuery = "SELECT * FROM category 
                       WHERE CategoryName = '$categoryName'
                       AND CategoryID != $categoryId";

        $checkResult = chayTruyVanTraVeDL($link, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            giaiPhongKetNoi($link);
            return "Category name already exists";
        }

        $query = "UPDATE category 
                  SET CategoryName = '$categoryName'
                  WHERE CategoryID = $categoryId";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Updated successfully" : "Update failed";
    }


    // ================= DELETE =================
    public function deleteCategory($categoryId) {

        $link = null;
        taoKetNoi($link);

        $query = "DELETE FROM category 
                  WHERE CategoryID = $categoryId";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Deleted successfully" : "Delete failed";
    }
}
?>
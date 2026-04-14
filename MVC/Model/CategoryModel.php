<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Category.php";

class CategoryModel extends AppModel {

    public function insert($categoryName) {

        $categoryName = mysqli_real_escape_string($this->link, $categoryName);

        $sql = "INSERT INTO category (CategoryName)
                VALUES ('$categoryName')";

        return $this->execute($sql);
    }

    public function getAll() {

        $sql = "SELECT * FROM category";

        $result = $this->query($sql);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Category(
                $row['CategoryID'],
                $row['CategoryName']
            );
        }

        return $list;
    }

    public function getById($categoryId) {

        $categoryId = (int)$categoryId;

        $sql = "SELECT * FROM category WHERE CategoryID = $categoryId";

        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return new Category(
                $row['CategoryID'],
                $row['CategoryName']
            );
        }

        return null;
    }

    public function update($categoryId, $categoryName) {

        $categoryId = (int)$categoryId;
        $categoryName = mysqli_real_escape_string($this->link, $categoryName);

        $sql = "UPDATE category 
                SET CategoryName = '$categoryName'
                WHERE CategoryID = $categoryId";

        return $this->execute($sql);
    }

    public function delete($categoryId) {

        $categoryId = (int)$categoryId;

        $sql = "DELETE FROM category WHERE CategoryID = $categoryId";

        return $this->execute($sql);
    }

    // Check trùng tên
    public function existsByName($categoryName, $excludeId = null) {

        $categoryName = mysqli_real_escape_string($this->link, $categoryName);

        $sql = "SELECT * FROM category WHERE CategoryName = '$categoryName'";

        if ($excludeId !== null) {
            $excludeId = (int)$excludeId;
            $sql .= " AND CategoryID != $excludeId";
        }

        $result = $this->query($sql);

        return mysqli_num_rows($result) > 0;
    }

    public function getAllWithPostCount() {

        $sql = "SELECT c.CategoryID, c.CategoryName, COUNT(p.PostID) as PostCount
                FROM category c
                LEFT JOIN post p ON c.CategoryID = p.CategoryID
                GROUP BY c.CategoryID, c.CategoryName
                ORDER BY c.CategoryName ASC";
        $result = $this->query($sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Category(
                $row['CategoryID'],
                $row['CategoryName'],
            );
        }
        return $list;
    }

    public function searchCategories($keyword) {

        $keyword = mysqli_real_escape_string($this->link, $keyword);

        $sql = "SELECT * FROM category WHERE CategoryName LIKE '%$keyword%'";

        $result = $this->query($sql);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Category(
                $row['CategoryID'],
                $row['CategoryName']
            );
        }

        return $list;
    }
}
?>
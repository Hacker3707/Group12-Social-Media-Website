<?php
include_once __DIR__ . "/AppModel.php";
include_once __DIR__ . "/../../Entity/Post.php";

class PostModel extends AppModel {

    public function insertPost(Post $post) {
        $groupId = $post->getGroupId() === null ? "NULL" : intval($post->getGroupId());
        $categoryId = $post->getCategoryId() === null ? "NULL" : intval($post->getCategoryId());
        $userId = intval($post->getUserId());
        $title = mysqli_real_escape_string($this->link,$post->getTitle());
        $content = mysqli_real_escape_string($this->link,$post->getContent());
        $price = $post->getPrice() === null ? "NULL" : intval($post->getPrice());
        $condition = mysqli_real_escape_string($this->link, $post->getCondition());
        $location = mysqli_real_escape_string($this->link, $post->getLocation());
        $brand = $post->getBrand() ? "'" . mysqli_real_escape_string($this->link, $post->getBrand()) . "'" : "NULL";
        $status = mysqli_real_escape_string($this->link, $post->getStatus());

        $sql = "INSERT INTO post 
                (UserID, GroupID, CategoryID, Title, Content, Price, ProductCondition, Location, Brand, PostStatus)
                VALUES 
                ($userId, $groupId, $categoryId, '$title', '$content', $price, '$condition', '$location', $brand, '$status')";

        return $this->execute($sql);
    }
 
    public function getAll() {
        // Đã thêm: u.AvatarFP
        $sql = "SELECT p.*, u.Username, u.AvatarFP, c.CategoryName
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                LEFT JOIN category c ON p.CategoryID = c.CategoryID
                ORDER BY p.CreatedAt DESC";

        $result = $this->query($sql);
        $posts = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $post = new Post(
                $row['PostID'], $row['UserID'], $row['GroupID'], $row['CategoryID'],
                $row['Title'], $row['Content'], $row['Price'], $row['ProductCondition'],
                $row['Location'], $row['Brand'], $row['PostStatus']
            );
            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);
            $post->setCategoryName($row['CategoryName'] ?? 'No Category');
            $post->setAvatar($row['AvatarFP']); // Gán Avatar

            $posts[] = $post;
        }
        return $posts;
    }

    public function fetchByField($field, $value) {
        $allowed = ['UserID','GroupID','CategoryID'];
        if (!in_array($field, $allowed)) return false;

        $value = mysqli_real_escape_string($this->link, $value);
        $data = [];

        // Đã thêm: u.AvatarFP
        $sql = "SELECT p.*, u.Username, u.AvatarFP, c.CategoryName
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                LEFT JOIN category c ON p.CategoryID = c.CategoryID
                WHERE p.$field = $value
                ORDER BY p.CreatedAt DESC";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $post = new Post(
                $row['PostID'], $row['UserID'], $row['GroupID'], $row['CategoryID'],
                $row['Title'], $row['Content'], $row['Price'], $row['ProductCondition'],
                $row['Location'], $row['Brand'], $row['PostStatus']
            );
            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);
            $post->setCategoryName($row['CategoryName'] ?? 'No Category');
            $post->setAvatar($row['AvatarFP']); // Gán Avatar

            $data[] = $post;
        }
        return $data;
    }

    public function delete($postId) {
        $postId = (int)$postId;
        $sql = "DELETE FROM post WHERE PostID = $postId";
        return $this->execute($sql);
    }

    public function update(Post $post) {
        $title = mysqli_real_escape_string($this->link, $post->getTitle());
        $content = mysqli_real_escape_string($this->link, $post->getContent());
        $condition = mysqli_real_escape_string($this->link, $post->getCondition());
        $location = mysqli_real_escape_string($this->link, $post->getLocation());
        $brand = $post->getBrand() ? "'" . mysqli_real_escape_string($this->link, $post->getBrand()) . "'" : "NULL";
        $status = mysqli_real_escape_string($this->link, $post->getStatus());
        $price = $post->getPrice() ? intval($post->getPrice()) : "NULL";

        $sql = "UPDATE post SET 
            Title = '$title', Content = '$content', Price = $price,
            ProductCondition = '$condition', Location = '$location', Brand = $brand, PostStatus = '$status'
            WHERE PostID = " . $post->getPostId();

        return $this->execute($sql);
    }

    public function getById($postId) {
        $postId = (int)$postId;
        // Đã thêm: u.AvatarFP
        $sql = "SELECT p.*, u.Username, u.AvatarFP 
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                WHERE p.PostID = $postId";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $post = new Post(
                $row['PostID'], $row['UserID'], $row['GroupID'], $row['CategoryID'],
                $row['Title'], $row['Content'], $row['Price'], $row['ProductCondition'],
                $row['Location'], $row['Brand'], $row['PostStatus']
            );
            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);
            $post->setAvatar($row['AvatarFP']); // Gán Avatar

            return $post;
        }   
        return false;
    }

    public function searchPosts($keyword) {
        $keyword = mysqli_real_escape_string($this->link, $keyword);
        // Đã thêm: u.AvatarFP
        $sql = "SELECT p.*, u.Username, u.AvatarFP, c.CategoryName
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                LEFT JOIN category c ON p.CategoryID = c.CategoryID
                WHERE p.Title LIKE '%$keyword%' OR p.Content LIKE '%$keyword%'
                ORDER BY p.CreatedAt DESC";

        $result = $this->query($sql);
        $posts = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $post = new Post(
                $row['PostID'], $row['UserID'], $row['GroupID'], $row['CategoryID'],
                $row['Title'], $row['Content'], $row['Price'], $row['ProductCondition'],
                $row['Location'], $row['Brand'], $row['PostStatus']
            );
            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);
            $post->setCategoryName($row['CategoryName'] ?? 'No Category');
            $post->setAvatar($row['AvatarFP']); // Gán Avatar

            $posts[] = $post;
        }
        return $posts;
    }

    public function getLastInsertId() {
        return parent::getLastInsertId();
    }
}
?>
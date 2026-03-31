<?php
include_once __DIR__ . "/AppModel.php";
include_once __DIR__ . "/../../Entity/Post.php";

class PostModel extends AppModel {

    // Thay thế cho hàm createPost (phần logic DB)
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

        // ❗ KHÔNG dùng stored procedure nữa (vì thiếu field mới)
        $sql = "INSERT INTO post 
                (UserID, GroupID, CategoryID, Title, Content, Price, ProductCondition, Location, Brand, PostStatus)
                VALUES 
                ($userId, $groupId, $categoryId, '$title', '$content', $price, '$condition', '$location', $brand, '$status')";

        return $this->execute($sql);
    }

    public function getAll() {

        
        $sql = "SELECT 
                    p.*,
                    u.Username
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                ORDER BY p.CreatedAt DESC";

        $result = $this->query($sql);
        $posts = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $post = new Post(
                $row['PostID'],
                $row['UserID'],
                $row['GroupID'],
                $row['CategoryID'],
                $row['Title'],
                $row['Content'],
                $row['Price'],
                $row['ProductCondition'],
                $row['Location'],
                $row['Brand'],
                $row['PostStatus']
            );

            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);

            $posts[] = $post;
        }

        return $posts;
    }
    


    // Thay thế cho getPostsByField
    public function fetchByField($field, $value) 
    {
        

        $allowed = ['UserID','GroupID','CategoryID'];
        if (!in_array($field, $allowed)) {
            return false;
        }

        $value = mysqli_real_escape_string($this->link, $value);
        $data = [];

        $sql = "SELECT p.*, u.Username 
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                WHERE p.$field = $value
                ORDER BY p.CreatedAt DESC";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {

            $post = new Post(
                $row['PostID'],
                $row['UserID'],
                $row['GroupID'],
                $row['CategoryID'],
                $row['Title'],
                $row['Content'],
                $row['Price'],
                $row['ProductCondition'],
                $row['Location'],
                $row['Brand'],
                $row['PostStatus']
            );

            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);

            $data[] = $post;
        }

        return $data;
    }

    public function delete($postId) {
        $postId = (int)$postId;
        $sql = "DELETE FROM post WHERE PostID = $postId";
        return $this->execute($sql);
    }

    public function update($postId, $title, $content) {
        $title = mysqli_real_escape_string($this->link, $title);
        $content = mysqli_real_escape_string($this->link, $content);
        $sql = "UPDATE post SET Title = '$title', Content = '$content' WHERE PostID = $postId";
        return $this->execute($sql);
    }

    public function getById($postId) {
        $postId = (int)$postId;
        $sql = "SELECT p.*, u.Username 
        FROM post p
        JOIN users u ON p.UserID = u.UserID
        WHERE p.PostID = $postId";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {

            $post = new Post(
                $row['PostID'],
                $row['UserID'],
                $row['GroupID'],
                $row['CategoryID'],
                $row['Title'],
                $row['Content'],
                $row['Price'],
                $row['ProductCondition'],
                $row['Location'],
                $row['Brand'],
                $row['PostStatus']
            );

            $post->setUsername($row['Username']);
            $post->setCreatedAt($row['CreatedAt']);

            return $post;
        }   

        return false;
    }
}
?>
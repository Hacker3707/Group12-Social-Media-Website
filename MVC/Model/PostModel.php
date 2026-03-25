<?php
include_once __DIR__ . "/AppModel.php";
include_once __DIR__ . "/../../Entity/Post.php";
class PostModel extends AppModel {

    // Thay thế cho hàm createPost (phần logic DB)
    public function insertPost($userId, $groupId, $categoryId, $title, $content) {
        $groupId = $groupId === null ? "NULL" : $groupId;
        $categoryId = $categoryId === null ? "NULL" : $categoryId;

        $title = mysqli_real_escape_string($this->link, $title);
        $content = mysqli_real_escape_string($this->link, $content);
        $sql = "CALL createPost($userId, $groupId, $categoryId, '$title', '$content')";
        $result = mysqli_query($this->link, $sql);

        if(!$result){
            echo mysqli_error($this->link);
            return false;
        }

        /* flush result set của procedure */
        while(mysqli_more_results($this->link)){
            mysqli_next_result($this->link);
        }

        return true;
    }

    public function getAll() {

        $sql = "SELECT 
                    p.PostID,
                    p.UserID,
                    p.GroupID,
                    p.CategoryID,
                    p.Title,
                    p.Content,
                    p.CreatedAt,
                    u.Username
                FROM post p
                JOIN users u ON p.UserID = u.UserID
                ORDER BY p.CreatedAt DESC";

        $result = $this->query($sql);

        $posts = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $posts[] = new Post(
                $row['PostID'],
                $row['UserID'],
                $row['GroupID'],
                $row['CategoryID'],
                $row['Username'],
                $row['Title'],
                $row['Content'],
                $row['CreatedAt'],
                []
            );

        }

    return $posts;
    }


    // Thay thế cho getPostsByField
    public function fetchByField($field, $value) 
    {
        $allowed = ['UserID','GroupID','CategoryID'];
        if (!in_array($field,$allowed)) {
            return false;
        }
        $value = mysqli_real_escape_string($this->link, $value);
        $data = array();
        $sql = "SELECT * FROM post WHERE $field = $value ORDER BY CreatedAt DESC";
        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, new Post(
                $row['PostID'], $row['UserID'],
                $row['GroupID'], $row['CategoryID'], 
                $row['Title'], $row['Content'],
                $row['CreatedAt'], []
            ));
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
        $sql = "SELECT * FROM post WHERE PostID = $postId";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return new Post(
                $row['PostID'], $row['UserID'],
                $row['GroupID'], $row['CategoryID'],
                $row['Title'], $row['Content'],
                $row['CreatedAt'], []
            );
        }

        return false;
    }
}
?>
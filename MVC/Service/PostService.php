<?php
include_once "MVC/Model/Post.php";
include_once "MVC/Model/Comment.php";
include_once "MVC/Model/Follow.php";
include_once "MVC/Model/Category.php";
include_once "MVC/Module/db_module.php";

class PostService {

    public function createPost($userId, $groupId, $categoryId, $title, $content, $mediaList = []) {

        $link = null;
        taoKetNoi($link);

        $query = "INSERT INTO post (UserID, GroupID, CategoryID, Title, Content) 
                VALUES ($userId, $groupId, $categoryId, '$title', '$content')";
        
        $result = chayTruyVanKhongTraVeDL($link, $query);

        if (!$result) {
            giaiPhongKetNoi($link);
            return "Failed to create post.";
        }

        $postId = mysqli_insert_id($link);

        foreach ($mediaList as $media) {

            $type = $media['type'];      // photo / video
            $path = $media['media_url']; // URL or file path to the media

            $mediaQuery = "INSERT INTO media (UserID, PostID, MediaType, FilePath)
                        VALUES ($userId, $postId, '$type', '$path')";

            chayTruyVanKhongTraVeDL($link, $mediaQuery);
        }

        giaiPhongKetNoi($link);

        return "Post created successfully.";
    }

    public function getPostById($postId) {

    }

    public function getPostsByUserId($userId) {
        $link = null;
        taoKetNoi($link);
        $query = "SELECT * FROM post WHERE UserID = $userId ORDER BY CreatedAt DESC";
        $result = chayTruyVanTraVeDL($link, $query);
        $posts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $postId = $row['PostID'];
            $mediaQuery = "SELECT * FROM media WHERE PostID = $postId";
            $mediaResult = chayTruyVanTraVeDL($link, $mediaQuery);
            $mediaList = [];
            while ($mediaRow = mysqli_fetch_assoc($mediaResult)) {
                $mediaList[] = [
                    'MediaID' => $mediaRow['MediaID'],
                    'MediaType' => $mediaRow['MediaType'],
                    'FilePath' => $mediaRow['FilePath']
                ];
            }
            $posts[] = new Post(
                $row['PostID'], $row['UserID'],
                $row['GroupID'], $row['CategoryID'], 
                $row['Title'], $row['Content'],
                $mediaList, $row['CreatedAt']
            );
        }
        giaiPhongKetNoi($link);
        return $posts;
    }

    public function getPostsByGroupId($groupId) {
       
    }

    public function getPostsByCategoryId($categoryId) {
        
    }

    public function addCommentToPost($postId, $userId, $content, $mediaList = []) {
        
    }
    
    public function followUser($followerId, $followingId) {
        
    }
    
    public function unfollowUser($followerId, $followingId) {
       
    }
}
?>
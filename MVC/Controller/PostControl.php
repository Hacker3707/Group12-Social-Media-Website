<?php
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/AppController.php";

class PostControl extends AppController {
    private $postModel;

    public function __construct() {
        $this->postModel = new PostModel();
    }

    public function createPost(){

        $userId = 1;
        $groupId = null;
        $categoryId = null;

        $title = $_POST['title'];
        $content = $_POST['content'];

        $mediaList = [];

        if(isset($_FILES['media']) && $_FILES['media']['error'] == 0){

            $uploadPath = "../../uploads/" . basename($_FILES['media']['name']);

            move_uploaded_file($_FILES['media']['tmp_name'], $uploadPath);

            $mediaList[] = $uploadPath;
        }

        $result = $this->postModel->insertPost(
            $userId,
            $groupId,
            $categoryId,
            $title,
            $content
        );

        echo $result ? "success" : "fail";
        exit;
    }

    // render page
    public function showHome(){

        $posts = $this->postModel->getAll() ?? [];

        include __DIR__ . "/../View/home.php";
    }

    public function PostAction(){

        $action = $_GET['action'] ?? "home";

        switch($action){

            case "createPost":
                $this->createPost();
                break;

            case "home":
                $this->showHome();
                break;

        }

    }

    public function getAllPosts() {
        $posts = $this->postModel->getAll() ?? [];
        include_once __DIR__ . "/../View/home.php";
    }

    public function getPostById($postId) {
        return $this->postModel->getById($postId);
    }

    public function getPostsByUserId($userId) {
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $posts = $this->postModel->fetchByField('UserID', $userId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByGroupId($groupId) {
        if (isset($_GET['group_id'])) {
            $groupId = $_GET['group_id'];
            $posts = $this->postModel->fetchByField('GroupID', $groupId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByCategoryId($categoryId) {
        if (isset($_GET['category_id'])) {
            $categoryId = $_GET['category_id'];
            $posts = $this->postModel->fetchByField('CategoryID', $categoryId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function deletePost($postId) {
        return $this->postModel->deletePost($postId);
    }

    public function updatePost($postId, $title, $content) {
        return $this->postModel->updatePost($postId, $title, $content);
    }
}
?>
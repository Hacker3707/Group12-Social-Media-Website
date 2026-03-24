<?php
include_once __DIR__ . "/../Service/PostService.php";

class PostControl {
    private $postService;

    public function __construct() {
        $this->postService = new PostService();
    }

    public function createPost($userId, $groupId, $categoryId, $title, $content, $mediaList = []) {
        return $this->postService->createPost($userId, $groupId, $categoryId, $title, $content, $mediaList);
    }

    public function getAllPosts() {
        $posts = $this->postService->getAllPosts() ?? [];
        include_once __DIR__ . "/../View/home.php";
    }

    public function getPostById($postId) {
        return $this->postService->getPostById($postId);
    }

    public function getPostsByUserId($userId) {
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $posts = $this->postService->getPostsByField('UserID', $userId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByGroupId($groupId) {
        if (isset($_GET['group_id'])) {
            $groupId = $_GET['group_id'];
            $posts = $this->postService->getPostsByField('GroupID', $groupId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByCategoryId($categoryId) {
        if (isset($_GET['category_id'])) {
            $categoryId = $_GET['category_id'];
            $posts = $this->postService->getPostsByCategoryId('CategoryID', $categoryId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function deletePost($postId) {
        return $this->postService->deletePost($postId);
    }

    public function updatePost($postId, $title, $content) {
        return $this->postService->updatePost($postId, $title, $content);
    }
}
?>
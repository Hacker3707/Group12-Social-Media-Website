<?php
include_once "MVC/Model/PostModel.php";
include_once "MVC/Model/Comment.php";
include_once "MVC/Model/Follow.php";
include_once "MVC/Model/Category.php";
include_once "MVC/Module/db_module.php";
include_once "MVC/Service/MediaService.php";

class PostService {

    private $postModel;
    private $mediaService;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->mediaService = new MediaService();
    }

    public function createPost($userId, $groupId, $categoryId, $title, $content, $mediaList = []) {

        $postId = $this->postModel->insertPost(
            $userId,
            $groupId,
            $categoryId,
            $title,
            $content
        );

        if (!$postId) {
            return "Failed to create post.";
        }

        foreach ($mediaList as $media) {
            $this->mediaService->createMediaForPost(
                $userId,
                $postId,
                $media->getMediaType(),
                $media->getFilePath()
            );
        }

        return "Post created successfully.";
    }   

    public function getPostById($postId) {
        
        $result = $this->postModel->getById($postId);
        $mediaList = $this->mediaService->getMediaByPostID($postId);
        if ($result) {
            $result->setMediaList($mediaList);
            return $result;
        }
        
        return null; // Post not found
    }

    private function getPostsByField($field, $value) {

        $posts = $this -> postModel -> fetchByField($field, $value);
        if (!$posts) {
            return [];
        }
        $postIds = [];
        foreach ($posts as $post) {
            $postIds[] = $post->getPostId();
        }

        if (count($postIds) > 0) {

            $postIdList = implode(",", $postIds);

            $mediaMap = $this->mediaService->getMediaByPostIDs($postIdList);

            foreach ($posts as $post) {

                $postId = $post->getPostId();

                if (isset($mediaMap[$postId])) {
                    $post->addToMediaList($mediaMap[$postId]);
                } else {
                    $post->setMediaList([]);
                }
            }
        }

        return $posts;
    }

    public function getPostsByUserId($userId) {
        return $this->getPostsByField("UserID", $userId);
    }

    public function getPostsByGroupId($groupId) {
        return $this->getPostsByField("GroupID", $groupId);
    }

    public function getPostsByCategoryId($categoryId) {
        return $this->getPostsByField("CategoryID", $categoryId);
    }
    
    public function deletePost($postId) {
        return $this->postModel->delete($postId);
    }

    public function updatePost($postId, $title, $content) {
        return $this->postModel->update($postId, $title, $content);
    }
}
?>
<?php
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/MediaService.php";

class PostService {

    private $postModel;
    private $mediaService;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->mediaService = new MediaService();
    }

     public function createPost(Post $post, $mediaList = []) {

        $result = $this->postModel->insertPost($post);

        if (!$result) {
            return false;
        }

        // ❗ lấy ID vừa insert
        $postId = mysqli_insert_id($this->postModel->getConnection());

        if (!empty($mediaList)) {

            foreach ($mediaList as $media) {

                $this->mediaService->createMediaForPost(
                    $post->getUserId(),
                    $postId,
                    $media->getMediaType(),
                    $media->getFilePath()
                );
            }
        }

        return true;
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

            $postIdList = implode(",", array_map('intval', $postIds));
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

    public function getAllPosts() {
        return $this->postModel->getAll();
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
        $result = $this->postModel->delete($postId);
        if ($result) {
            return true;
        }
        return false;
    }

   public function updatePost($postId, $title, $content, $price, $condition, $location, $brand, $status) {

    return $this->postModel->update(
        $postId,
        $title,
        $content,
        $price,
        $condition,
        $location,
        $brand,
        $status
    );
}
}

?>
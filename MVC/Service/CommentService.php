<?php
include_once "MVC/Model/CommentModel.php";
include_once "Entity/Comment.php";
class CommentService
{
    private $commentModel;
    private $mediaService;

    public function __construct() {
        $this->commentModel = new CommentModel();
        $this->mediaService = new MediaService();
    }

    public function addComment($userId, $postId, $content, $parentCommentId = null, $mediaList = [])
    {
        $commentId = $this->commentModel->createComment($userId, $postId, $content, $parentCommentId);
        if (!$commentId) {
            return "Failed to add comment.";
        }

        // Handle media attachments for the comment
        foreach ($mediaList as $media) {
            $this->mediaService->createMediaForComment(
                $userId,
                $commentId,
                $media->getMediaType(),
                $media->getFilePath()
            );
        }

        return "Comment added successfully.";   
    }

    public function deleteComment($commentId)
    {
        $result = $this->commentModel->deleteComment($commentId);

        if ($result) {
            return "Comment deleted successfully.";
        }

        return "Failed to delete comment.";
    }

    public function getCommentsByField($field, $value) {
        $comments = $this -> commentModel -> fetchByField($field, $value);
        if (!$comments) {
            return [];
        }
        $comIds = [];
        foreach ($comments as $comment) {
            $comIds[] = $comment->getCommentId();
        }

        if (count($comIds) > 0) {

            $commentIdList = implode(",", $comIds);

            $mediaMap = $this->mediaService->getMediaByCommentIDs($commentIdList);

            foreach ($comments as $comment) {

                $commentId = $comment->getCommentId();

                if (isset($mediaMap[$commentId])) {
                    $comment->addToMediaList($mediaMap[$commentId]);
                } else {
                    $comment->setMediaList([]);
                }
            }
        }

        return $comments;
    }

    public function getCommentsByUserId($userId) {
        return $this->getCommentsByField("UserID", $userId);
    }

    public function getCommentsByPostId($postId) {
        return $this->getCommentsByField("PostID", $postId);
    }

    public function getCommentsByCommentId($commentId) {
        return $this->getCommentsByField("CommentID", $commentId);
    }
    
}
?>
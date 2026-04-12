<?php
include_once __DIR__ . "/../Model/ReactionModel.php";

class ReactionController {

    private $reactionModel;

    public function __construct() {
        $this->reactionModel = new ReactionModel();
    }

    public function action_forReaction() {

        $postId = $_POST['postId'] ?? null;
        //$userId = $_POST['user_id'];
        $userId = $_SESSION['user_id'] ?? null;
        $commentId = $_POST['commentId'] ?? null;
        $type = $_POST['type'] ?? 'like'; // Default to 'like' if type is not provided

        $reaction = new Reaction(null, $postId, $commentId, $userId, $type);

        // Validate input
        if (empty($userId) || (empty($postId) && empty($commentId))) {
            http_response_code(400);
            echo "fail";
            return;
        }

        // Create reaction
        $result = $this->reactionModel->insertReaction($reaction);

        echo json_encode($result);
        exit;
        
    }

    public function removeReaction() {
        $reactionId = $_POST['reactionId'];

        if (empty($reactionId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing reaction ID']);
            return;
        }

        $result = $this->reactionModel->deleteReaction($reactionId);
        echo $result ? "success" : "fail";
        exit;
    }
}
?>
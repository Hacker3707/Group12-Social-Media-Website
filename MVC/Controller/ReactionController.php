<?php
include_once __DIR__ . "/../Model/ReactionModel.php";

class ReactionController {

    private $reactionModel;

    public function __construct() {
        $this->reactionModel = new ReactionModel();
    }

    public function addReaction() {

        $postId = $_POST['postId'];
        //$userId = $_POST['user_id'];
        $userId = $_SESSION['user_id'] ?? null;
        $type = $_POST['type'] ?? 'like'; // Default to 'like' if type is not provided

        $reaction = new Reaction(null, $postId, null, $userId, $type);

        // Validate input
        if (empty($postId) || empty($userId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        // Create reaction
        $result = $this->reactionModel->insertReaction($reaction);
        echo $result ? "success" : "fail";
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
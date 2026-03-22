<?php
include_once "MVC/Model/ReactionModel.php";
include_once "Entity/Reaction.php";

class ReactionService
{
    private $reactionModel;
    public function __construct() {
        $this->reactionModel = new ReactionModel();
    }

    public function addReactionToPost($userId, $postId, $reactionType)
    {
        $result = $this->reactionModel->insertReaction($userId, $postId, null, $reactionType);

        if (!$result) {
            return "There's something went wrong... Cannot react!";
        }

        return "Reacted successfully!";
    }

     public function addReactionToComment($userId, $commentId, $reactionType)
    {
        $result = $this->reactionModel->insertReaction($userId, null, $commentId, $reactionType);
        if (!$result)
        {
            return "There's something went wrong... Cannot react!";
        }
        return "Reacted successfully!";
    }

    public function removeReaction($reactionId)
    {
        $result = $this -> reactionModel -> deleteReaction($reactionId);
        if (!$result)
        {
            return "There's something went wrong... Cannot remove reaction!";
        }
        return "Removed reaction successfully!";
    }

    public function getReactionsForPost($postId)
    {
        return $this->reactionModel->selectReactionsForPost($postId);
    }

    public function getReactionsForComment($commentId)
    {
        return $this->reactionModel->selectReactionsForComment($commentId);
    }


}   
?>
<?php
// Bổ sung các tham số: $allowInteraction, $reactions_forComment, $isSameUser_reactPost_reactCmt
function renderComments($postId, $parentId, $commentTree, $level = 0,
    $allowInteraction = true,
    $reactions_forComment = [],
    $isSameUser_reactCmt = [],
    $postOwnerId = null,
    $commentLookup = []
){

    if (empty($commentLookup) && !empty($commentTree[$postId])) {
        foreach ($commentTree[$postId] as $commentGroup) {
            foreach ($commentGroup as $commentNode) {
                $commentLookup[$commentNode->getCommentId()] = $commentNode;
            }
        }
    }

    if(empty($commentTree[$postId][$parentId])) return;

    foreach($commentTree[$postId][$parentId] as $c){

        $commentId = $c->getCommentId();
        $isReplyToDeletedMessage = false;
        $parentCommentId = $c->getParentCommentId();

        if ($parentCommentId !== null && isset($commentLookup[$parentCommentId])) {
            $parentContent = trim((string)$commentLookup[$parentCommentId]->getContent());
            $isReplyToDeletedMessage = ($parentContent === 'Tin nhắn đã bị xóa');
        }

        // 1. COMMENT ITEM (LUÔN HIỂN THỊ)
        include 'comment_item.php';

        // 2. CHECK CÓ REPLY KHÔNG

        if(!empty($commentTree[$postId][$commentId])){
            echo '<button class="toggle-replies btn btn-sm btn-link"
                    data-comment-id="'.$commentId.'">
                    View replies
                </button>';
        }


            // 3. CONTAINER CHỈ ẨN REPLY
            echo '<div class="reply-container d-none" id="replies-'.$commentId.'">';

            if(!empty($commentTree[$postId][$commentId])){
                renderComments(
                    $postId,
                    $commentId,
                    $commentTree,
                    $level + 1,
                    $allowInteraction,
                    $reactions_forComment,
                    $isSameUser_reactCmt,
                    $postOwnerId,
                    $commentLookup
                );
            }

            echo '</div>';
            echo "<!-- parentId=$parentId count=" . count($commentTree[$postId][$parentId] ?? []) . " -->";
        

        echo '<hr style="margin: 10px 15px; color:#ddd;">';
    }
}
?>
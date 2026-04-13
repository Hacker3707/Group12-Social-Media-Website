<?php
// Bổ sung các tham số: $allowInteraction, $reactions_forComment, $isSameUser_reactCmt
function renderComments($postId, $parentId, $commentTree, $allowInteraction = true, $reactions_forComment = [], $isSameUser_reactCmt = []){

    if(empty($commentTree[$postId][$parentId])) return;

    foreach($commentTree[$postId][$parentId] as $c){

        // Lúc này file comment_item.php được include vào sẽ NHÌN THẤY toàn bộ các biến trên
        include 'comment_item.php';

        $commentId = $c->getCommentId();

        if(!empty($commentTree[$postId][$commentId])){

            echo '<button class="toggle-replies btn btn-sm btn-link"
                    data-commentid="'.$commentId.'">
                    View replies
                </button>';

            echo '<hr style="margin: 10px 15px; color:#ddd;">';

            echo '<div class="reply-container d-none"
                    id="replies-'.$commentId.'">';

            // Cực kỳ quan trọng: Phải truyền tiếp các biến này xuống các comment con (đệ quy)
            renderComments($postId, $commentId, $commentTree, $allowInteraction, $reactions_forComment, $isSameUser_reactCmt);

            echo '</div>';
        }
        else {
            echo '<hr style="margin: 10px 15px; color:#ddd;">';
        }

    }
}
?>
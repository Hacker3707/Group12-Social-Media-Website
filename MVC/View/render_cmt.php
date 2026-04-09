<?php
function renderComments($postId, $parentId, $commentTree){

    if(empty($commentTree[$postId][$parentId])) return;

    foreach($commentTree[$postId][$parentId] as $c){

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

            renderComments($postId, $commentId, $commentTree);

            echo '</div>';
        }
        else {

        echo '<hr style="margin: 10px 15px; color:#ddd;">';
        }

    }
}
?>
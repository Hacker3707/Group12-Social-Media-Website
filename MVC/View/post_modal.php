<?php include_once "render_cmt.php"?>

<div class="modal fade post-modal"
id="postModal<?= $post->getPostId() ?>"
tabindex="-1">

  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title">
                <img src="" alt="???" class="rounded-circle mr-2" width="40" height="40">
                <a href="index.php?controller=user&action=profile&id=<?= $post->getUserId() ?>">
                    <?= htmlspecialchars($post->getUsername()) ?>
                </a>
            </h5>

            <button type="button" class="close" data-dismiss="modal">
            &times;
            </button>
        </div>

        <div class="modal-body">
            <h3><?= htmlspecialchars($post->getTitle()) ?></h3>
            <p><?= htmlspecialchars($post->getContent()) ?></p>

            <div class="d-flex align-items-center mt-4">
                <small class="text-muted ">
                Posted at <?= $post->getCreatedAt() ?>
                </small>

                <?php if($isSameUser[$postId] ?? false): ?>

                    <button class="btn-forModal btn-sm btn-outline-primary like-btn ml-auto"
                        type="button"
                        data-postid="<?= $postId ?>">

                        <i class="bi bi-heart-fill"></i>
                        <span class="badge badge-light like-count">
                        <?= count($reactions_forPost[$postId] ?? []) ?>
                        </span>

                    </button>

                <?php else: ?>

                    <button class="btn-forModal btn-sm btn-outline-primary like-btn ml-auto"
                        type="button"
                        data-postid="<?= $postId ?>">

                        <i class="bi bi-heart"></i>
                        <span class="badge badge-light like-count">
                        <?= count($reactions_forPost[$postId] ?? []) ?>
                        </span>

                    </button>

                <?php endif; ?>

            </div>

        </div>

        <hr style="margin: 5px 15px; color: #ddd;">

        <div class="comment-list mt-3" style="padding: 0 15px; overflow-y: auto;">

            <?php if (!empty($comments[$post->getPostId()])): ?>
                <?php
                    renderComments($postId, null, $commentTree);
                ?>
                
            <?php else: ?>
                <div class="text-center mt-3 no-cmt">
                    <small class="text-muted d-block mb-2">No comments yet</small>
                    <img src="Materials/Picture/no-comment.jpg"
                        style="opacity:0.5;"
                        class="img-fluid crow">
                </div>
            <?php endif; ?>

        </div>

        <div class="border rounded p-2 mb-2 d-none reply-preview" id ="reply-preview">
            <div class="d-flex justify-content-between">
                <small>
                    Replying to <strong id="reply-user"></strong>
                </small>

                <button type="button" class="cancel-reply close">
                    &times;
                </button>
            </div>

            <div id="reply-content" class="text-muted small"></div>
        </div>

        <form class="comment-form mt-2" style="padding: 10px 15px;">

            <input type="hidden" name="parentId" id="parentId" class="parentId">

            <input type="hidden"
                name="postId"
                value="<?= $post->getPostId() ?>">

            <div class="input-group">
                <input type="text" id="commentContent"
                    name="commentContent"
                    class="form-control commentContent"
                    placeholder="Write a comment...">

                <div class="input-group-append">
                    <button class="btn btn-primary cmt-btn"
                            type="submit">
                        Comment
                    </button>
                </div>
            </div>

        </form>


    </div>
  </div>

</div>



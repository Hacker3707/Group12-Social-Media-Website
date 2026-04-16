<div class="comment-item" data-comment-id="<?= $c->getCommentId() ?>" style="margin-left: <?= $level * 10 ?>px;">

    <div class="d-flex justify-content-between align-items-start">

        <div class="d-flex align-items-center">
            <?php $cmtAvatar = $c->getAvatar() ? htmlspecialchars($c->getAvatar()) : 'https://via.placeholder.com/30'; ?>
            <img src="<?= $cmtAvatar ?>" class="rounded-circle mr-2 shadow-sm" style="width: 32px; height: 32px; object-fit: cover; border: 1px solid #eee;">
            <strong>
                <a href="index.php?controller=user&action=profile&id=<?= $c->getUserId() ?>" class="text-dark">
                    <?= htmlspecialchars($c->getUsername()) ?>
                </a>
            </strong>
        </div>

        <div class="btn-group dropdown mt-1">
            <button type="button"
                    class="btn btn-sm dropdown-toggle"
                    data-toggle="dropdown"
                    style="background-color: rgb(255, 255, 255); border:none; color: rgb(191, 191, 191);">
                <i class="bi bi-three-dots"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right">
                <?php
                    $currentUserId = (int)($_SESSION['user_id'] ?? 0);
                    $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
                    $isCommentOwner = ((int)$c->getUserId() === $currentUserId);
                    $isPostOwner = (isset($postOwnerId) && (int)$postOwnerId === $currentUserId);
                    $canDeleteComment = $isSystemAdmin || $isCommentOwner || $isPostOwner;
                ?>

                <?php if ($canDeleteComment): ?>
                    <button class="dropdown-item delete-btn-cmt"
                            type="button"
                            data-comment-id="<?= $c->getCommentId() ?>">
                        Delete
                    </button>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <p class="mb-1"><?= htmlspecialchars($c->getContent()) ?></p>

    <?php if (!empty($isReplyToDeletedMessage)): ?>
        <small class="text-muted d-block mb-1">Đã trả lời tin nhắn đã bị xóa</small>
    <?php endif; ?>

    <div class="d-flex mt-1 align-items-center">

        <small class="text-muted mr-3"><?= $c->getCreatedAt() ?></small>

        <?php if(isset($allowInteraction) && $allowInteraction): ?>
            
            <button class="btn-forModal btn-sm btn-outline-primary reply-btn"
                    type="button"
                    data-comment-id="<?= $c->getCommentId() ?>">
                Reply
            </button>

            <?php if($isSameUser_reactCmt[$c->getCommentId()] ?? false): ?>
                <button class="btn btn-sm btn-outline-primary like-btn-cmt ml-2"
                        type="button"
                        data-comment-id="<?= $c->getCommentId() ?>">
                    <i class="bi bi-heart-fill"></i>
                    <span class="badge badge-light like-count-cmt">
                        <?= count($reactions_forComment[$c->getCommentId()] ?? []) ?>
                    </span>
                </button>
            <?php else: ?>
                <button class="btn btn-sm btn-outline-primary like-btn-cmt ml-2"
                        type="button"
                        data-comment-id="<?= $c->getCommentId() ?>">
                    <i class="bi bi-heart"></i>
                    <span class="badge badge-light like-count-cmt">
                        <?= count($reactions_forComment[$c->getCommentId()] ?? []) ?>
                    </span>
                </button>
            <?php endif; ?>

        <?php else: ?>
            
            <span class="text-muted ml-1" style="font-size: 0.9em; cursor: not-allowed;" title="Vui lòng tham gia nhóm để tương tác">
                <i class="bi bi-heart"></i> <?= count($reactions_forComment[$c->getCommentId()] ?? []) ?>
            </span>

        <?php endif; ?>
        </div>

</div>

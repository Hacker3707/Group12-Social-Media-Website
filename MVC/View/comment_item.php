<div class="comment-item mb-2">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start">

        <strong>
            <a href="index.php?controller=user&action=profile&id=<?= $c->getUserId() ?>">
                <?= htmlspecialchars($c->getUsername()) ?>
            </a>
            <small>commented:</small>
        </strong>

        <div class="btn-group dropdown">
            <button type="button"
                    class="btn btn-sm dropdown-toggle"
                    data-toggle="dropdown"
                    style="background-color: rgb(186, 212, 230); border:none; color: white;">
                <i class="bi bi-three-dots"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button">Report</button>
                <button class="dropdown-item delete-btn-cmt"
                        type="button"
                        data-commentid="<?= $c->getCommentId() ?>">
                    Delete
                </button>
            </div>
        </div>

    </div>

    <!-- Content -->
    <p class="mb-1"><?= htmlspecialchars($c->getContent()) ?></p>

    

    <!-- Action buttons -->
    <div class="d-flex mt-1">

        <small class="text-muted align-items-left"><?= $c->getCreatedAt() ?></small>

        <button class="btn-forModal btn-sm btn-outline-primary reply-btn align-items-end"
                type="button"
                data-comment-id="<?= $c->getCommentId() ?>">
            Reply
        </button>

        <?php if($isSameUser_reactCmt[$c->getCommentId()] ?? false): ?>

            <button class="btn btn-sm btn-outline-primary like-btn-cmt ml-2 align-items-end"
                    type="button"
                    data-commentid="<?= $c->getCommentId() ?>">

                <i class="bi bi-heart-fill"></i>
                <span class="badge badge-light like-count-cmt">
                    <?= count($reactions_forComment[$c->getCommentId()] ?? []) ?>
                </span>

            </button>

        <?php else: ?>

            <button class="btn btn-sm btn-outline-primary like-btn-cmt ml-2"
                    type="button"
                    data-commentid="<?= $c->getCommentId() ?>">

                <i class="bi bi-heart"></i>
                <span class="badge badge-light like-count-cmt">
                    <?= count($reactions_forComment[$c->getCommentId()] ?? []) ?>
                </span>

            </button>

        <?php endif; ?>

    </div>

</div>
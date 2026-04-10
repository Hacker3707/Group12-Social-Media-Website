<?php include_once "render_cmt.php"?>

<?php 
    // 🔥 Lấy ID bài viết hiện tại
    $postId = $post->getPostId();
    
    // 🔥 XÁC ĐỊNH QUYỀN TƯƠNG TÁC
    $allowInteraction = true;
    if (isset($canInteract)) {
        if (is_array($canInteract)) {
            $allowInteraction = $canInteract[$postId] ?? true; // Dùng cho trang Newsfeed
        } else {
            $allowInteraction = $canInteract; // Dùng cho trang Chi tiết nhóm
        }
    }
?>

<div class="modal fade post-modal"
id="postModal<?= $postId ?>"
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

            <!-- ✅ HIỂN THỊ ẢNH / VIDEO CỦA POST -->
            <?php if (!empty($mediaForPost[$postId])): ?>
                <div class="post-media mb-3">
                    <?php foreach ($mediaForPost[$postId] as $media): ?>

                        <?php if ($media->getMediaType() === 'photo'): ?>
                            <img src="/<?= htmlspecialchars($media->getFilePath()) ?>"
                                 class="img-fluid rounded mb-2"
                                 style="max-height: 400px; object-fit: cover; width: 100%;"
                                 alt="Post image">

                        <?php elseif ($media->getMediaType() === 'video'): ?>
                            <video controls class="w-100 rounded mb-2" style="max-height: 400px;">
                                <source src="/<?= htmlspecialchars($media->getFilePath()) ?>">
                            </video>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ✅ THÔNG TIN PHỤ CỦA POST -->
            <div class="text-muted small mb-3">
                <?php if($post->getPrice() !== null): ?>
                    💰 Price: <?= number_format($post->getPrice()) ?> VND <br>
                <?php endif; ?>
                📦 Condition: <?= htmlspecialchars($post->getCondition()) ?> <br>
                📍 Location: <?= htmlspecialchars($post->getLocation()) ?> <br>
                <?php if($post->getBrand()): ?>
                    🏷️ Brand: <?= htmlspecialchars($post->getBrand()) ?> <br>
                <?php endif; ?>
                📌 Status: <?= htmlspecialchars($post->getStatus()) ?>
            </div>

            <div class="d-flex align-items-center mt-4">
                <small class="text-muted ">
                Posted at <?= $post->getCreatedAt() ?>
                </small>

                <?php if ($allowInteraction): ?>
                    <?php if($isSameUser[$postId] ?? false): ?>
                        <button class="btn-forModal btn-sm btn-outline-primary like-btn ml-auto" type="button" data-postid="<?= $postId ?>">
                            <i class="bi bi-heart-fill"></i>
                            <span class="badge badge-light like-count"><?= count($reactions_forPost[$postId] ?? []) ?></span>
                        </button>
                    <?php else: ?>
                        <button class="btn-forModal btn-sm btn-outline-primary like-btn ml-auto" type="button" data-postid="<?= $postId ?>">
                            <i class="bi bi-heart"></i>
                            <span class="badge badge-light like-count"><?= count($reactions_forPost[$postId] ?? []) ?></span>
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn-forModal btn-sm btn-outline-secondary ml-auto" style="cursor: not-allowed; opacity: 0.6;" title="Vui lòng tham gia nhóm để thả tim">
                        <i class="bi bi-heart"></i>
                        <span class="badge badge-light"><?= count($reactions_forPost[$postId] ?? []) ?></span>
                    </button>
                <?php endif; ?>
                </div>

        </div>

        <hr style="margin: 5px 15px; color: #ddd;">

        <div class="comment-list mt-3" style="padding: 0 15px; overflow-y: auto;">

            <?php if (!empty($comments[$postId])): ?>
                <?php
                    // CHÍNH LÀ CHỖ NÀY: Truyền $allowInteraction và các mảng đếm Like vào hàm
                    renderComments($postId, null, $commentTree, $allowInteraction, $reactions_forComment ?? [], $isSameUser_reactCmt ?? []);
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

        <?php if ($allowInteraction): ?>
            
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
                <input type="hidden" name="postId" value="<?= $postId ?>">

                <div class="input-group">
                    <input type="text" id="commentContent"
                        name="commentContent"
                        class="form-control commentContent"
                        placeholder="Write a comment...">

                    <div class="input-group-append">
                        <button class="btn btn-primary cmt-btn" type="submit">
                            Comment
                        </button>
                    </div>
                </div>
            </form>

        <?php else: ?>
            
            <div class="alert alert-secondary text-center border mx-3 mb-3 mt-2" style="border-radius: 10px;">
                <i class="fas fa-lock mr-1"></i> Vui lòng <strong>Tham gia nhóm</strong> để bình luận.
            </div>

        <?php endif; ?>
        </div>
  </div>

</div>
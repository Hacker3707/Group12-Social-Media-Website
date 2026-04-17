<?php
include 'confirm_alert.php';

if(empty($posts)){
    echo "No posts found";
    return;
}
?>

<?php foreach($posts as $post): ?>
<?php 
    $isProduct = $post->getPrice() !== null || $post->getCondition() || $post->getStatus();
    $postId = $post->getPostId();
    $canManagePost = (bool)($canDel_EditPost[$postId] ?? false);
    $groupName = trim((string)($post->getGroupName() ?? ''));
    $hasGroupName = ($groupName !== '' && strcasecmp($groupName, 'No Group') !== 0);
    
    // 🔥 XÁC ĐỊNH QUYỀN TƯƠNG TÁC CHO TỪNG BÀI VIẾT
    $allowInteraction = true;
    if (isset($canInteract)) {
        if (is_array($canInteract)) {
            $allowInteraction = $canInteract[$postId] ?? true; // Dùng cho trang Newsfeed
        } else {
            $allowInteraction = $canInteract; // Dùng cho trang Chi tiết nhóm
        }
    }

$isProduct = $post->getPrice() !== null 
          || $post->getCondition() 
          || $post->getStatus();
?>

<div class="post-item border rounded p-3 mb-3 bg-white">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-2">

    <div class="d-flex align-items-center flex-grow-1" style="min-width: 0;">
        
        <?php $avatarUrl = $post->getAvatar() ? htmlspecialchars($post->getAvatar()) : 'https://via.placeholder.com/40'; ?>
        <img src="<?= $avatarUrl ?>" alt="avatar" class="rounded-circle mr-2 shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">

            <a href="index.php?controller=user&action=profile&id=<?= $post->getUserId() ?>" style="max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle;">
                <strong><?= htmlspecialchars($post->getUsername()) ?></strong>
            </a>

            <div class="d-flex align-items-center flex-nowrap ml-2" style="min-width: 0; overflow: hidden;">
                <?php if ($post -> getCategoryName() !== 'No Category'): ?>
                <span style="margin: 0 5px; flex-shrink: 0;">›</span>
                <a href="index.php?controller=post&action=getPostsByCategoryId&category_id=<?= $post->getCategoryId() ?>">
                        <button class="btn btn-outline-secondary btn-sm" style="color: cornflowerblue; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;" title="<?= htmlspecialchars($post->getCategoryName() ?? '') ?>">
                            <?= htmlspecialchars($post->getCategoryName() ?? '') ?>
                        </button>
                </a>
                <?php endif; ?>

                <?php if ($hasGroupName):?>
                <span style="margin: 0 5px; flex-shrink: 0;">››</span>
                    <a href="index.php?controller=group&action=detail&id=<?= $post->getGroupId() ?>">
                        <button class="btn btn-outline-secondary btn-sm" style="color: cornflowerblue; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;" title="<?= htmlspecialchars($groupName) ?>">
                            <?= htmlspecialchars($groupName) ?>
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        
    </div>
    
    <div class="d-flex">

    <small class="text-muted align-items-end mt-2 mr-1"><?= $post->getCreatedAt() ?></small>

    <?php if ($canManagePost): ?>
    <!-- DROPDOWN -->
    <div class="btn-group dropleft ml-2">
        <button type="button"
                class="btn btn-secondary dropdown-toggle"
                data-toggle="dropdown"
                style="background-color: rgb(186, 212, 230); border:none;">
            <i class="bi bi-three-dots"></i>
        </button>

        <div class="dropdown-menu">
            <button class="dropdown-item edit-btn"
                    type="button"
                    data-postid="<?= $postId ?>">
                Edit
            </button>

            <button class="dropdown-item delete-btn"
                type="button"
                data-postid="<?= $postId ?>">
                Delete
            </button>
        </div>
    </div>
    <?php endif; ?>

    </div>
</div>

    <!-- CONTENT -->
    <h5><?= htmlspecialchars($post->getTitle()) ?></h5>
    <p><?= nl2br(htmlspecialchars($post->getContent())) ?></p>

    <!-- MEDIA (giữ từ code trên) -->
    <?php if (!empty($mediaForPost[$postId])): ?>
        <?php
            $postMediaItems = $mediaForPost[$postId];
            $postMediaCount = count($postMediaItems);
            $postPhotoCount = 0;
            foreach ($postMediaItems as $mediaItem) {
                if ($mediaItem->getMediaType() === 'photo') {
                    $postPhotoCount++;
                }
            }
            $useCarousel = ($postPhotoCount > 1 && $postPhotoCount === $postMediaCount);
            $carouselId = 'postMediaCarousel' . $postId;
        ?>
        <div class="mb-3 text-center" style="background-color: #f8f9fa; border-radius: 14px; border: 1px solid #e9ecef; overflow: hidden; max-height: 460px;">
            <?php if ($postMediaCount === 1): ?>
                <?php $media = $postMediaItems[0]; ?>
                <?php if ($media->getMediaType() === 'photo'): ?>
                    <img src="/<?= htmlspecialchars($media->getFilePath()) ?>"
                         class="img-fluid"
                         style="height: 460px; width: 100%; object-fit: contain; margin: 0 auto; display: block; background-color: #f8f9fa;">
                <?php elseif ($media->getMediaType() === 'video'): ?>
                    <video controls class="w-100" style="height: 460px; width: 100%; object-fit: contain; background-color: #000; outline: none;">
                        <source src="/<?= htmlspecialchars($media->getFilePath()) ?>">
                    </video>
                <?php endif; ?>
            <?php elseif ($useCarousel): ?>
                <div id="<?= $carouselId ?>" class="carousel slide" data-interval="false">
                    <div class="carousel-inner">
                        <?php foreach ($postMediaItems as $index => $media): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="/<?= htmlspecialchars($media->getFilePath()) ?>"
                                     class="d-block w-100"
                                     style="height: 460px; width: 100%; object-fit: contain; background-color: #f8f9fa;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="carousel-control-prev" href="#<?= $carouselId ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#<?= $carouselId ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($postMediaItems as $media): ?>
                    <?php if ($media->getMediaType() === 'photo'): ?>
                        <img src="/<?= htmlspecialchars($media->getFilePath()) ?>"
                             class="d-block w-100"
                             style="height: 460px; width: 100%; object-fit: contain; background-color: #f8f9fa;">
                    <?php elseif ($media->getMediaType() === 'video'): ?>
                        <video controls class="d-block w-100" style="height: 460px; width: 100%; object-fit: contain; background-color: #000; outline: none;">
                            <source src="/<?= htmlspecialchars($media->getFilePath()) ?>">
                        </video>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- BADGE -->
    <?php if($isProduct): ?>
    <div class="mt-2 d-flex flex-wrap" style="gap:6px;">
        <?php if($post->getPrice() !== null): ?>
            <span class="badge badge-success">
                💰 <?= number_format($post->getPrice()) ?> VND
            </span>
        <?php endif; ?>

        <?php if($post->getCondition()): ?>
            <span class="badge badge-info"><?= $post->getCondition() ?></span>
        <?php endif; ?>

        <?php if($post->getLocation()): ?>
            <span class="badge badge-secondary"><?= $post->getLocation() ?></span>
        <?php endif; ?>

        <?php if($post->getBrand()): ?>
            <span class="badge badge-dark"><?= $post->getBrand() ?></span>
        <?php endif; ?>
    </div>
    
    <div class="text-muted small mt-3 border-top pt-2">
        <?php if($post->getPrice() !== null): ?> 💰 Price: <?= number_format($post->getPrice()) ?> VND <br> <?php endif; ?>
        <?php if($post->getCondition()): ?> 📦 Condition: <?= htmlspecialchars($post->getCondition()) ?> <br> <?php endif; ?>
        <?php if($post->getLocation()): ?> 📍 Location: <?= htmlspecialchars($post->getLocation()) ?> <br> <?php endif; ?>
        <?php if($post->getBrand()): ?> 🏷️ Brand: <?= htmlspecialchars($post->getBrand()) ?> <br> <?php endif; ?>
        <?php if($post->getStatus()): ?> 📌 Status: <?= htmlspecialchars($post->getStatus()) ?> <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($allowInteraction): ?>

<div class="mt-3 d-flex align-items-center">

    <!-- LIKE -->
    <button class="btn btn-sm btn-outline-primary like-btn ml-auto"
            type="button"
            data-postid="<?= $postId ?>">

        <?php if($isSameUser_reactPost[$postId] ?? false): ?>
            <i class="bi bi-heart-fill"></i>
        <?php else: ?>
            <i class="bi bi-heart"></i>
        <?php endif; ?>

        <span class="badge badge-light like-count">
            <?= count($reactions_forPost[$postId] ?? []) ?>
        </span>
    </button>

     <!-- COMMENT -->

    <button class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal"
        data-target="#postModal<?= $postId ?>">
        Comment 
        <span class="badge badge-light badge-cmt">
            <?= count($comments[$postId] ?? []) ?>
        </span>
    </button>

</div>

<?php else: ?>

<div class="mt-3 d-flex align-items-center">
    <button class="btn btn-sm btn-outline-primary col-md-2 col-12"
        data-toggle="modal"
        data-target="#postModal<?= $postId ?>">
        View Post
    </button>

    <div class="alert alert-light text-muted border ml-3 mb-0 py-1 flex-grow-1 text-center">
        🔒 Vui lòng tham gia nhóm để tương tác
    </div>
</div>

<?php endif; ?>
    <?php include "MVC/View/post_modal.php"; ?>

</div>

<?php endforeach; ?>

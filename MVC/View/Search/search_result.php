<?php
$maxItems = 7; // Số lượng bài đăng tối đa trên một hàng
$displayPosts = array_slice($posts, 0, $maxItems);
$displayGroups = array_slice($groups, 0, $maxItems);
$displayCategories = array_slice($categories, 0, $maxItems);
$displayUsers = array_slice($users, 0, $maxItems);
?>

<div class="container mt-4">

    <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="font-weight-bold">Tìm kiếm người dùng</h3>
                <p class="text-muted">Tìm kiếm và theo dõi những người dùng có cùng sở thích với bạn.</p>
            </div>

            <?php if (empty($users)): ?>
                <div class="col-12 text-center mt-5">
                    <h5 class="text-muted">Không tìm thấy người dùng nào phù hợp.</h5>
                </div>
            <?php else: ?>
            
        </div>

        <div class="row">
            
                <?php foreach ($displayUsers as $user): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card group-card h-100">
                            <img src="https://via.placeholder.com/400x150/1877f2/ffffff?text=<?= urlencode(substr($user->getUsername(), 0, 1)) ?>" class="group-cover-sm">
                            <div class="card-body d-flex flex-column">
                                <h6 class="font-weight-bold text-truncate"><?= htmlspecialchars($user->getUsername()) ?></h6>
                                <p class="small text-muted mb-3 flex-grow-1"><?= htmlspecialchars($user->getBio() ?? 'Chưa có mô tả') ?></p>
                                <a href="index.php?controller=user&action=profile&id=<?= $user->getUserID() ?>" class="btn btn-light btn-block btn-sm font-weight-bold text-primary" style="background-color: #e7f3ff;">Xem hồ sơ</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(count($users) > $maxItems): ?>

            <div class="col-md-3 mb-4">
                <div class="card group-card h-100">
                    <a href="index.php?controller=search&action=searchUsers&searchResults=<?= urlencode($keyword ?? '') ?>" class="card h-100 d-flex align-items-center justify-content-center text-center">

                        <div>
                            <h2>+</h2>
                            <small>Xem thêm</small>
                        </div>

                    </a>
                </div>
            </div>

            <?php endif; ?>
        </div>
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="font-weight-bold">Khám phá các nhóm</h3>
                <p class="text-muted">Tìm kiếm và tham gia những cộng đồng có cùng sở thích với bạn.</p>
            </div>
            <?php if (empty($groups)): ?>
                <div class="col-12 text-center mt-5">
                    <h5 class="text-muted">Không tìm thấy nhóm nào phù hợp.</h5>
                </div>
            <?php else: ?>
        </div>

        <div class="row">
            
                <?php foreach ($displayGroups as $group): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card group-card h-100">
                            <img src="https://via.placeholder.com/400x150/1877f2/ffffff?text=<?= urlencode(substr($group['GroupName'], 0, 1)) ?>" class="group-cover-sm">
                            <div class="card-body d-flex flex-column">
                                <h6 class="font-weight-bold text-truncate"><?= htmlspecialchars($group['GroupName']) ?></h6>
                                <p class="small text-muted mb-3 flex-grow-1"><?= htmlspecialchars($group['Description'] ?? 'Chưa có mô tả') ?></p>
                                <a href="index.php?controller=group&action=detail&id=<?= $group['GroupID'] ?>" class="btn btn-light btn-block btn-sm font-weight-bold text-primary" style="background-color: #e7f3ff;">Xem nhóm</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(count($groups) > $maxItems): ?>

            <div class="col-md-3 mb-4">
                <div class="card group-card h-100">
                    <a href="index.php?controller=search&action=searchGroups&searchResults=<?= urlencode($keyword ?? '') ?>" class="card h-100 d-flex align-items-center justify-content-center text-center">

                        <div>
                            <h2>+</h2>
                            <small>Xem thêm</small>
                        </div>

                    </a>
                </div>
            </div>

            <?php endif; ?>
        </div>

        

        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="font-weight-bold">Tìm kiếm bài đăng</h3>
                <p class="text-muted">Tìm kiếm và theo dõi những người dùng có cùng sở thích với bạn.</p>
            </div>

            <?php if (empty($posts)): ?>
                <div class="col-12 text-center mt-5">
                    <h5 class="text-muted">Không tìm thấy bài đăng nào phù hợp.</h5>
                </div>
            <?php else: ?>
            
        </div>

        <div class="row">
            
                <?php foreach ($displayPosts as $post): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card group-card h-100">
                            <img src="https://via.placeholder.com/400x150/1877f2/ffffff?text=<?= urlencode(substr($post->getUsername(), 0, 1)) ?>" class="group-cover-sm">
                            <div class="card-body d-flex flex-column">
                                <h6 class="font-weight-bold text-truncate"><?= htmlspecialchars($post->getTitle()) ?></h6>
                                <p class="small text-muted mb-3 flex-grow-1 post-preview"><?= htmlspecialchars($post->getContent() ?? 'Chưa có mô tả') ?></p>
                                <a href="index.php?controller=post&action=detail&id=<?= $post->getPostID() ?>" class="btn btn-light btn-block btn-sm font-weight-bold text-primary" data-toggle="modal"
                                data-target="#postModal<?= $post->getPostId() ?>" style="background-color: #e7f3ff;">Xem nhanh</a>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade"
                    id="postModal<?= $post->getPostId() ?>"
                    tabindex="-1">

                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <?= htmlspecialchars($post->getTitle()) ?>
                                </h5>

                                <button type="button" class="close" data-dismiss="modal">
                                &times;
                                </button>
                            </div>

                                <div class="modal-body">
                                    <p><?= htmlspecialchars($post->getContent()) ?></p>

                                    <div class="d-flex align-items-center mt-4">
                                        <small class="text-muted ">
                                        Posted at <?= $post->getCreatedAt() ?>
                                        </small>

                                        <button id="btn-forModal" class="btn btn-sm btn-outline-primary like-btn ml-auto justify-content-end" type="button" data-postid="<?= $post->getPostId() ?>">
                                                Like <span class="badge badge-light like-count"><?= count($reactions[$post->getPostId()] ?? []) ?></span>
                                        </button>

                                    </div>

                                </div>


                            </div>
                    </div>

                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(count($posts) > $maxItems): ?>

            <div class="col-md-3 mb-4">
                <div class="card group-card h-100">
                    <a href="index.php?controller=search&action=searchPosts&searchResults=<?= urlencode($keyword ?? '') ?>" class="card h-100 d-flex align-items-center justify-content-center text-center">

                        <div>
                            <h2>+</h2>
                            <small>Xem thêm</small>
                        </div>

                    </a>
                </div>
            </div>

            <?php endif; ?>
        </div>

        <!-- Like Post Script -->
        <script>
            document.addEventListener("click", function(e){

            let btn = e.target.closest(".like-btn");
            if(!btn) return;

            let postId = btn.getAttribute("data-postid");

            let xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function(){

                if(xhr.readyState === 4 && xhr.status === 200){

                    if(xhr.responseText.trim() === "success"){

                        let badge = btn.querySelector(".like-count");
                        let count = parseInt(badge.textContent);

                        badge.textContent = count + 1;

                    }
                    else{
                        alert("Like failed");
                    }

                }

            };

            xhr.open("POST", "index.php?controller=reaction&action=addReaction", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(
                "postId=" + encodeURIComponent(postId) +
                "&type=like"
            );

        });
        </script>

        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="font-weight-bold">Tìm kiếm danh mục</h3>
                <p class="text-muted">Tìm kiếm và tham gia những cộng đồng có cùng sở thích với bạn.</p>
            </div>

            <?php if (empty($categories)): ?>
                <div class="col-12 text-center mt-5">
                    <h5 class="text-muted">Không tìm thấy danh mục nào phù hợp.</h5>
                </div>
            <?php else: ?>
            
        </div>


        <div class="row">
            <?php foreach ($displayCategories as $category): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card group-card h-100">
                            <img src="https://via.placeholder.com/400x150/1877f2/ffffff?text=<?= urlencode(substr($category->getCategoryName(), 0, 1)) ?>" class="group-cover-sm">
                            <div class="card-body d-flex flex-column">
                                <h6 class="font-weight-bold text-truncate"><?= htmlspecialchars($category->getCategoryName()) ?></h6>
                                <a href="index.php?controller=post&action=getPostsByCategoryId&category_id=<?= $category->getCategoryID() ?>" class="btn btn-light btn-block btn-sm font-weight-bold text-primary" style="background-color: #e7f3ff;">Xem các bài đăng liên quan</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if(count($categories) > $maxItems): ?>

            <div class="col-md-3 mb-4">
                <div class="card group-card h-100">
                    <a href="index.php?controller=search&action=searchCategories&searchResults=<?= urlencode($keyword ?? '') ?>" class="card h-100 d-flex align-items-center justify-content-center text-center">

                        <div>
                            <h2>+</h2>
                            <small>Xem thêm</small>
                        </div>

                    </a>
                </div>
            </div>

            <?php endif; ?>
        </div>

        

</div>
<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết Bài đăng | Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css"> 
</head>
<body style="background-color: #f0f2f5;">
    <div class="container-fluid" id="home-container">
        <div class="row" id="navbar">
            <div class="col-md-12 col-12 px-0">
                <?php include 'MVC/View/navbar.php'; ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-md-3 col-lg-2 col-12 " id="left-sidebar" style="font-size: 15px;">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>
            
            <div class="col-md-9 col-lg-10 col-12" id="main-content"  style="padding: 35px;">
                
                <a href="index.php?controller=post&action=list" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Quay lại Danh sách</a>

                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-body">
                        <h3 class="font-weight-bold text-dark"><?= htmlspecialchars($post->getTitle()) ?></h3>
                        <p class="text-muted mb-3">
                            <i class="fas fa-user text-primary"></i> <strong><?= htmlspecialchars($post->getUsername()) ?></strong> 
                            <span class="mx-2">|</span> 
                            <i class="fas fa-clock text-secondary"></i> <?= date('d/m/Y H:i', strtotime($post->getCreatedAt())) ?>
                        </p>
                        <hr>
                        <div class="post-content mt-3" style="font-size: 1.1rem; line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($post->getContent())) ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold text-danger"><i class="fas fa-comments"></i> Quản lý Bình luận (<?= count($comments) ?>)</h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 20%;">Người bình luận</th>
                                        <th style="width: 55%;">Nội dung</th>
                                        <th style="width: 15%;">Ngày giờ</th>
                                        <th style="width: 10%;" class="text-center">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($comments)): ?>
                                        <tr><td colspan="4" class="py-4 text-center text-muted">Bài viết này chưa có bình luận nào.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($comments as $cmt): ?>
                                        <tr>
                                            <td class="font-weight-bold text-primary"><?= htmlspecialchars($cmt->getUsername()) ?></td>
                                            <td><?= nl2br(htmlspecialchars($cmt->getContent())) ?></td>
                                            <td class="text-muted"><small><?= date('d/m/Y H:i', strtotime($cmt->getCreatedAt())) ?></small></td>
                                            <td class="text-center">
                                                <a href="index.php?controller=comment&action=adminDelete&id=<?= $cmt->getCommentId() ?>&post_id=<?= $post->getPostId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa bình luận này?')" title="Xóa bình luận">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row" id = "footer">
            <div class= "col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
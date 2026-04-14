<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($group['GroupName']) ?> | Passo</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">

    <style>
        body { background-color: #f0f2f5; }
        .group-cover { height: 400px; background: #ccc; border-radius: 0 0 8px 8px; overflow: hidden; }
        .group-header { background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .nav-tabs { border-bottom: none; }
        .nav-link { color: #65676b; font-weight: 600; padding: 15px 20px; }
        .nav-link.active { color: #1877f2 !important; border-bottom: 3px solid #1877f2 !important; border: none; }
    </style>
</head>
<body>

    <?php include 'MVC/View/navbar.php'; ?>

    <div class="group-header">
        <div class="container px-0">
            <div class="group-cover">
                <img src="https://picsum.photos/1200/400" class="w-100 h-100" style="object-fit: cover;">
            </div>
            <div class="p-4 relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="font-weight-bold mb-1"><?= htmlspecialchars($group['GroupName']) ?></h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-globe"></i> Nhóm <?= $group['Privacy'] == 'public' ? 'Công khai' : 'Riêng tư' ?> · 
                            <strong><?= $memberCount ?></strong> thành viên
                        </p>
                    </div>

                    <div class="d-flex align-items-center">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            
                            <?php if (isset($userRole) && $userRole === 'admin'): ?>
                                <a href="index.php?controller=group&action=manageMembers&id=<?= $group['GroupID'] ?>" class="btn btn-info font-weight-bold mr-2">
                                    <i class="fas fa-users-cog"></i> Quản lý thành viên
                                </a>
                                <a href="index.php?controller=group&action=edit&id=<?= $group['GroupID'] ?>" class="btn btn-secondary font-weight-bold mr-2">
                                    <i class="fas fa-pen"></i> Sửa nhóm
                                </a>
                                <a href="index.php?controller=group&action=deleteGroup&id=<?= $group['GroupID'] ?>" 
                                   class="btn btn-danger font-weight-bold mr-3" onclick="return confirm('CẢNH BÁO: Xóa nhóm vĩnh viễn?');">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            <?php endif; ?>

                            <form action="index.php?controller=group&action=toggleFollow" method="POST" class="mb-0">
                                <input type="hidden" name="group_id" value="<?= $group['GroupID'] ?>">
                                
                                <?php if (isset($joinStatus) && $joinStatus === 'approved'): ?>
                                    <button type="submit" class="btn btn-light font-weight-bold" style="background-color: #e4e6eb;">
                                        <i class="fas fa-check"></i> Đã tham gia
                                    </button>
                                <?php elseif (isset($joinStatus) && $joinStatus === 'pending'): ?>
                                    <button type="submit" class="btn btn-warning font-weight-bold">
                                        <i class="fas fa-clock"></i> Đang chờ duyệt
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-primary font-weight-bold">
                                        <i class="fas fa-plus"></i> Tham gia nhóm
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <hr class="mt-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#">Thảo luận</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Đáng chú ý</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Thành viên</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">File phương tiện</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 mb-3">
                    <h5 class="font-weight-bold">Giới thiệu</h5>
                    <p><?= nl2br(htmlspecialchars($group['Description'] ?? 'Chào mừng các bạn đến với cộng đồng ' . $group['GroupName'])) ?></p>
                    <button class="btn btn-light font-weight-bold btn-block">Xem thêm</button>
                </div>
            </div>
            
            <div class="col-md-8">
                <?php if (isset($joinStatus) && $joinStatus === 'approved'): ?>
                    <div class="card border-0 shadow-sm p-3 mb-3">
                        <div class="d-flex">
                            <img src="https://via.placeholder.com/40" class="rounded-circle mr-2">
                            <input type="text" class="form-control rounded-pill bg-light border-0" placeholder="Viết gì đó cho nhóm...">
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php 
                    // Nếu Controller không truyền $canViewPosts, mặc định là true để tránh lỗi
                    $allowView = isset($canViewPosts) ? $canViewPosts : true; 
                ?>

                <?php if ($allowView): ?>
                    <div class="card border-0 shadow-sm p-3 mb-5">
                        <div class="text-muted mb-2 font-weight-bold">Bài viết trong nhóm</div>
                        <?php include_once 'MVC/View/postview.php'; ?>
                    </div>
                <?php else: ?>
                    <div class="alert border-0 shadow-sm text-center py-5 mb-5" style="background-color: #fff; border-radius: 10px;">
                        <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                        <h4 class="font-weight-bold text-dark">Nhóm Riêng Tư</h4>
                        <p class="text-muted">Bạn phải tham gia nhóm này thì mới xem được các bài viết và bình luận.</p>
                    </div>
                <?php endif; ?>

                <?php 
                    $allowInteract = isset($canInteract) ? $canInteract : true; 
                    if ($allowInteract): 
                ?>
                    <div class="row" id="lower-bar">
                        <div class="col-md-1 col-12">
                            <a href="index.php?controller=group&action=showCreateForm&id=<?= $group['GroupID'] ?>" class="btn btn-primary shadow font-weight-bold" style="position: fixed; bottom: 30px; right: 30px; border-radius: 50px; padding: 10px 20px; z-index: 1000;">
                                <i class="fas fa-pen mr-1"></i> Create Post
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>
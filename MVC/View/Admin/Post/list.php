<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Bài đăng | Passo</title>
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
            <div class="col-md-2 col-12 d-none d-md-block" id="left-sidebar" style="">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>
            
            <div class="col-md-10 col-12" id="main-content">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 font-weight-bold text-info"><i class="fas fa-newspaper"></i> Quản lý Hệ thống Bài đăng</h4>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Người đăng</th>
                                        <th>Tiêu đề</th>
                                        <th>Ngày đăng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($posts)): ?>
                                        <tr><td colspan="5" class="py-4 text-muted">Chưa có bài viết nào!</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($posts as $p): ?>
                                        <tr>
                                            <td class="align-middle text-muted"><?= $p->getPostId() ?></td>
                                            <td class="align-middle font-weight-bold"><?= htmlspecialchars($p->getUsername()) ?></td>
                                            <td class="align-middle text-left" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= htmlspecialchars($p->getTitle()) ?>
                                            </td>
                                            <td class="align-middle text-muted"><?= date('d/m/Y H:i', strtotime($p->getCreatedAt())) ?></td>
                                            
                                            <td class="align-middle">
                                                <a href="index.php?controller=post&action=adminDetail&id=<?= $p->getPostId() ?>" class="btn btn-sm btn-info font-weight-bold text-white mr-1" title="Xem chi tiết & Bình luận">
                                                    <i class="fas fa-eye"></i> Xem bài
                                                </a>
                                                
                                                <a href="index.php?controller=post&action=adminDelete&id=<?= $p->getPostId() ?>" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('Xác nhận xóa bài viết này?')" title="Xóa bài viết">
                                                    <i class="fas fa-trash"></i> Xóa
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
<!DOCTYPE html>
<html>
<head>
    <title>Quản lý danh mục | Passo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">
    <style>
        body { background-color: #f0f2f5; }
        .table th { border-top: none; }
    </style>
</head>
<body>
    <div class="container-fluid" id="home-container">
        <div class="row" id="navbar">
            <div class="col-md-12 col-12 px-0">
                <?php include 'MVC/View/navbar.php'; ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-12 col-lg-2 mb-3 mb-lg-0" id="left-sidebar">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>

            <div class="col-12 col-lg-10" id="main-content">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
                        <h4 class="mb-2 mb-md-0 font-weight-bold text-success">
                            <i class="fas fa-tags"></i> Quản lý Danh mục
                        </h4>
                        <span class="badge badge-success px-3 py-2">
                            Tổng categories: <?= (int)($totalCategories ?? 0) ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 mb-3 mb-lg-0">
                                <h6 class="font-weight-bold text-dark">Thêm danh mục mới</h6>
                                <form action="index.php?controller=user&action=adminCreateCategory" method="POST" class="mt-3">
                                    <div class="input-group">
                                        <input type="text"
                                               name="categoryName"
                                               class="form-control"
                                               placeholder="Nhập tên category..."
                                               maxlength="100"
                                               required>
                                        <div class="input-group-append">
                                            <button class="btn btn-success font-weight-bold" type="submit">
                                                <i class="fas fa-plus"></i> Thêm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-7">
                                <h6 class="font-weight-bold text-dark">Danh sách categories hiện có</h6>
                                <div class="table-responsive mt-3">
                                    <table class="table table-sm table-hover mb-0 text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên danh mục</th>
                                                <th>Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($categories)): ?>
                                                <tr>
                                                    <td colspan="3" class="text-muted py-3">Chưa có category nào.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($categories as $cat): ?>
                                                    <tr>
                                                        <td class="align-middle text-muted"><?= $cat->getCategoryID() ?></td>
                                                        <td class="align-middle font-weight-bold text-dark">
                                                            <?= htmlspecialchars($cat->getCategoryName()) ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            <a href="index.php?controller=user&action=adminDeleteCategory&id=<?= $cat->getCategoryID() ?>"
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Bạn chắc chắn muốn xóa category này?')"
                                                               title="Xóa danh mục">
                                                                <i class="fas fa-trash"></i>
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
            </div>
        </div>

        <div class="row" id="footer">
            <div class="col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
    </div>

    <?php include_once "MVC/View/chat_widget.php"; ?>
</body>
</html>

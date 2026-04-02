<!DOCTYPE html>
<head>
    <title>Quản lý Nhóm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">
</head>
<body>
    <div class="container-fluid" id="home-container">
        <div class="row" id="upper-bar">
            <div class="col-md-12 col-12">
                <?php include 'MVC/View/navbar.php'; ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-md-2 col-12" id="left-sidebar">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>
            
            <div class="col-md-10 col-12" id="main-content">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 font-weight-bold text-primary">Danh sách Nhóm</h4>
                        <button class="btn btn-primary btn-sm">+ Tạo nhóm mới</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên nhóm</th>
                                        <th>Danh mục</th>
                                        <th>Quyền riêng tư</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($groups as $g): ?>
                                    <tr>
                                        <td class="align-middle"><?= $g->getGroupId() ?></td>
                                        <td class="align-middle font-weight-bold text-dark"><?= htmlspecialchars($g->getGroupName()) ?></td>
                                        <td class="align-middle"><?= $g->getCategoryId() ?></td>
                                        <td class="align-middle">
                                            <?php if($g->getPrivacy() == 'public'): ?>
                                                <span class="badge badge-success px-2 py-1">Công khai</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary px-2 py-1">Riêng tư</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="index.php?controller=group&action=viewMembers&id=<?= $g->getGroupId() ?>" class="btn btn-sm btn-info text-white">Thành viên</a>
                                            <a href="index.php?controller=group&action=edit&id=<?= $g->getGroupId() ?>" class="btn btn-sm btn-outline-primary ml-1">Sửa</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
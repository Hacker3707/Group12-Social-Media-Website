<!DOCTYPE html>
<head>
    <title>Quản lý người dùng</title>
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
                        <h4 class="mb-0 font-weight-bold text-primary">Danh sách người dùng</h4>
                        <button class="btn btn-primary btn-sm">+ Thêm người dùng</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td class="align-middle"><?= $u->getUserId() ?></td>
                                        <td class="align-middle font-weight-bold"><?= htmlspecialchars($u->getUsername()) ?></td>
                                        <td class="align-middle"><?= htmlspecialchars($u->getEmail()) ?></td>
                                        <td class="align-middle">
                                            <span class="badge badge-<?= $u->getUserRole() == 'admin' ? 'danger' : 'info' ?> px-2 py-1">
                                                <?= strtoupper($u->getUserRole()) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-<?= $u->getAccountStatus() == 'active' ? 'success' : 'secondary' ?> px-2 py-1">
                                                <?= ucfirst($u->getAccountStatus()) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="index.php?controller=user&action=edit&id=<?= $u->getUserId() ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                            <a href="index.php?controller=user&action=delete&id=<?= $u->getUserId() ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
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
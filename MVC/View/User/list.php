<!DOCTYPE html>
<html>
<head>
    <title>Quản lý người dùng | Passo</title>
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
        <div class="row" id="upper-bar">
            <div class="col-md-12 col-12 px-0">
                <?php include 'MVC/View/navbar.php'; ?>
            </div>
        </div>

        <div class="row mt-4 px-md-3" id="middle-content">
            <div class="col-md-3 col-lg-2 col-12 mb-3" id="left-sidebar">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>
            
            <div class="col-md-9 col-lg-10 col-12" id="main-content">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
                        <h4 class="mb-3 mb-md-0 font-weight-bold text-primary"><i class="fas fa-users-cog"></i> Quản lý Hệ thống Người dùng</h4>
                        
                        <form action="index.php" method="GET" class="form-inline m-0">
                            <input type="hidden" name="controller" value="user">
                            <input type="hidden" name="action" value="list">
                            <div class="input-group shadow-sm">
                                <input type="text" name="keyword" class="form-control border-right-0" placeholder="Tìm username, email..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary bg-white border-left-0" type="submit"><i class="fas fa-search text-primary"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                        <tr><td colspan="6" class="py-4 text-muted">Không tìm thấy người dùng nào!</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $u): ?>
                                        <tr>
                                            <td class="align-middle text-muted"><?= $u->getUserId() ?></td>
                                            <td class="align-middle font-weight-bold text-dark"><?= htmlspecialchars($u->getUsername()) ?></td>
                                            <td class="align-middle text-muted"><?= htmlspecialchars($u->getEmail()) ?></td>
                                            
                                            <td class="align-middle">
                                                <?php if ($u->canManageSystem()): ?>
                                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-crown"></i> ADMIN</span>
                                                <?php else: ?>
                                                    <span class="badge badge-info px-2 py-1">MEMBER</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="align-middle">
                                                <?php 
                                                    $status = $u->getAccountStatus();
                                                    if ($status === 'active') echo '<span class="badge badge-success px-2 py-1">Hoạt động</span>';
                                                    elseif ($status === 'banned') echo '<span class="badge badge-warning px-2 py-1">Bị Khóa</span>';
                                                    else echo '<span class="badge badge-secondary px-2 py-1">Đã Xóa</span>';
                                                ?>
                                            </td>
                                            
                                            <td class="align-middle">
                                                <a href="index.php?controller=user&action=edit&id=<?= $u->getUserId() ?>" class="btn btn-sm btn-light border font-weight-bold text-primary mr-1" title="Sửa hồ sơ">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                
                                                <?php if ($u->getUserId() != $_SESSION['user_id']): ?>
                                                    
                                                    <?php if ($u->getAccountStatus() !== 'banned'): ?>
                                                        <a href="index.php?controller=user&action=ban&id=<?= $u->getUserId() ?>" class="btn btn-sm btn-warning font-weight-bold text-dark mr-1" onclick="return confirm('Bạn có chắc chắn muốn KHÓA tài khoản này?')" title="Khóa tài khoản">
                                                            <i class="fas fa-ban"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?controller=user&action=unban&id=<?= $u->getUserId() ?>" class="btn btn-sm btn-success font-weight-bold mr-1" title="Mở khóa tài khoản">
                                                            <i class="fas fa-unlock"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <a href="index.php?controller=user&action=delete&id=<?= $u->getUserId() ?>" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('CẢNH BÁO: Xác nhận xóa người dùng này?')" title="Xóa tài khoản">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    
                                                <?php endif; ?>
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
</body>
</html>
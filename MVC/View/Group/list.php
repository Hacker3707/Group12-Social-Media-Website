<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Nhóm | Passo Admin</title>
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
            <div class="col-md-3 col-lg-2 col-12" id="left-sidebar">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>
            
            <div class="col-md-9 col-lg-10 col-12" id="main-content">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 font-weight-bold text-success"><i class="fas fa-layer-group"></i> Quản lý Hệ thống Nhóm</h4>
                        <a href="index.php?controller=group&action=create" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
                            <i class="fas fa-plus"></i> Tạo nhóm mới
                        </a>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên nhóm</th>
                                        <th>Quyền riêng tư</th>
                                        <th>Thành viên</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($groups)): ?>
                                        <tr><td colspan="5" class="py-4 text-muted">Chưa có nhóm nào được tạo!</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($groups as $g): ?>
                                        <tr>
                                            <td class="align-middle text-muted"><?= $g->getGroupId() ?></td>
                                            <td class="align-middle font-weight-bold text-dark text-left"><?= htmlspecialchars($g->getGroupName()) ?></td>
                                            
                                            <td class="align-middle">
                                                <?php if (strtolower($g->getPrivacy()) === 'private'): ?>
                                                    <span class="badge badge-secondary px-2 py-1"><i class="fas fa-lock"></i> Riêng tư</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-globe"></i> Công khai</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="align-middle">
                                                <a href="index.php?controller=group&action=viewMembers&id=<?= $g->getGroupId() ?>" class="btn btn-sm btn-light border font-weight-bold text-info" title="Xem thành viên">
                                                    <i class="fas fa-users"></i> Xem TV
                                                </a>
                                            </td>
                                            
                                            <td class="align-middle">
                                                <a href="index.php?controller=group&action=detail&id=<?= $g->getGroupId() ?>" class="btn btn-sm btn-info font-weight-bold text-white mr-1" title="Vào nhóm">
                                                    <i class="fas fa-sign-in-alt"></i> Vào
                                                </a>
                                                
                                                <a href="index.php?controller=group&action=edit&id=<?= $g->getGroupId() ?>" class="btn btn-sm btn-primary font-weight-bold text-white mr-1" title="Sửa thông tin nhóm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <a href="index.php?controller=group&action=deleteGroup&id=<?= $g->getGroupId() ?>" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('CẢNH BÁO: Xác nhận xóa nhóm này vĩnh viễn?')" title="Xóa nhóm">
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
        <div class="row" id = "footer">
            <div class= "col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
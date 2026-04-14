<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Thành viên | Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>body { background-color: #f0f2f5; } .member-row { border-bottom: 1px solid #eee; }</style>
</head>
<body>
    <?php include 'MVC/View/navbar.php'; ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm rounded-lg p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="font-weight-bold m-0">Quản lý Thành viên: <?= htmlspecialchars($group['GroupName']) ?></h3>
                <a href="index.php?controller=group&action=detail&id=<?= $group['GroupID'] ?>" class="btn btn-light border">Quay lại nhóm</a>
            </div>

            <?php if (!empty($pendingMembers)): ?>
                <div class="alert alert-warning p-3 mb-4 border-0 shadow-sm">
                    <h5 class="font-weight-bold text-dark mb-3">Yêu cầu tham gia (<?= count($pendingMembers) ?>)</h5>
                    
                    <?php foreach ($pendingMembers as $req): ?>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-warning">
                            <div class="d-flex align-items-center">
                                <?php $myAvatar = $_SESSION['avatar'] ?? 'https://via.placeholder.com/40'; ?>
                            <img src="<?= htmlspecialchars($myAvatar) ?>" class="rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ddd;">
                                <div>
                                    <h6 class="font-weight-bold m-0"><?= htmlspecialchars($req['Username']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($req['Email']) ?></small>
                                </div>
                            </div>
                            
                            <form action="index.php?controller=group&action=processMember" method="POST" class="m-0">
                                <input type="hidden" name="group_id" value="<?= $group['GroupID'] ?>">
                                <input type="hidden" name="target_user_id" value="<?= $req['UserID'] ?>">
                                
                                <button type="submit" name="action_type" value="approve" class="btn btn-sm btn-success font-weight-bold mr-1">Phê duyệt</button>
                                <button type="submit" name="action_type" value="reject" class="btn btn-sm btn-secondary font-weight-bold">Từ chối</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                <h5 class="font-weight-bold mt-4 mb-3">Danh sách thành viên hiện tại</h5>
            <?php endif; ?>
            <?php foreach ($members as $mem): ?>
                <div class="d-flex justify-content-between align-items-center py-3 member-row">
                    <div class="d-flex align-items-center">
                        <?php $myAvatar = $_SESSION['avatar'] ?? 'https://via.placeholder.com/40'; ?>
                            <img src="<?= htmlspecialchars($myAvatar) ?>" class="rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #ddd;">
                        <div>
                            <h6 class="font-weight-bold m-0 text-primary"><?= htmlspecialchars($mem['Username']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($mem['Email']) ?></small>
                            <?php if ($mem['Role'] === 'admin'): ?>
                                <span class="badge badge-primary ml-2">Quản trị viên</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($mem['UserID'] != $_SESSION['user_id']): ?>
                        <form action="index.php?controller=group&action=processMember" method="POST" class="m-0 d-flex">
                            <input type="hidden" name="group_id" value="<?= $group['GroupID'] ?>">
                            <input type="hidden" name="target_user_id" value="<?= $mem['UserID'] ?>">
                            
                            <?php if ($mem['Role'] === 'member'): ?>
                                <button type="submit" name="action_type" value="promote" class="btn btn-sm btn-outline-success mr-2 font-weight-bold">Thêm quyền quản trị viên</button>
                            <?php else: ?>
                                <button type="submit" name="action_type" value="demote" class="btn btn-sm btn-outline-warning mr-2 font-weight-bold" onclick="return confirm('Hủy quyền quản trị viên của người này?');">Hủy quyền quản trị viên</button>
                            <?php endif; ?>
                            
                            <button type="submit" name="action_type" value="kick" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('Xóa người này khỏi nhóm?');">Xóa khỏi nhóm</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</body>
</html>
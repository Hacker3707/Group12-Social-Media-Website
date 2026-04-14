<div class="list-group border-0 rounded-lg">

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="list-group-item bg-primary text-white font-weight-bold text-uppercase border-0 py-3">
            <i class="fas fa-user-shield mr-1"></i> Admin Dashboard
        </div>

        <a href="index.php?controller=user&action=list" class="list-group-item list-group-item-action border-0 font-weight-bold text-dark py-3">
            <i class="fas fa-users mr-2 text-primary" style="width: 15px;"></i> Quản lý Người dùng
        </a>

        <a href="index.php?controller=group&action=list" class="list-group-item list-group-item-action border-0 font-weight-bold text-dark py-3">
            <i class="fas fa-layer-group mr-2 text-success" style="width: 15px;"></i> Quản lý Nhóm
        </a>

        <a href="index.php?controller=post&action=list" class="list-group-item list-group-item-action border-0 font-weight-bold text-dark py-3">
            <i class="fas fa-newspaper mr-2 text-info" style="width: 15px;"></i> Quản lý Bài đăng
        </a>

        <a href="index.php?controller=chat&action=inbox" class="list-group-item list-group-item-action border-0 font-weight-bold text-dark py-3">
            <i class="fas fa-comments mr-2 text-warning" style="width: 25px;"></i> Tin nhắn
        </a>

    <?php else: ?>

        <a href="index.php?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?? 0 ?>" class="list-group-item list-group-item-action border-0 font-weight-bold py-3">
            <i class="fas fa-user mr-2 text-primary" style="width: 25px;"></i> Profile
        </a>

        <a href="index.php?controller=group&action=myGroups" class="list-group-item list-group-item-action border-0 font-weight-bold py-3">
            <i class="fas fa-users mr-2 text-info" style="width: 25px;"></i> Groups
        </a>

        <a href="index.php?controller=chat&action=inbox" class="list-group-item list-group-item-action border-0 font-weight-bold py-3">
            <i class="fas fa-comments mr-2 text-warning" style="width: 25px;"></i> Tin nhắn
        </a>

        <a href="#" class="list-group-item list-group-item-action border-0 font-weight-bold py-3">
            <i class="fas fa-cog mr-2 text-secondary" style="width: 25px;"></i> Setting
        </a>
    <?php endif; ?>

</div>
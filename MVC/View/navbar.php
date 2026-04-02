<?php
// 1. Lấy thông tin controller và action hiện tại từ URL
$currentController = $_GET['controller'] ?? '';
$currentAction     = $_GET['action'] ?? '';

// 2. NẾU KHÔNG PHẢI là trang Đăng ký hoặc Đăng nhập thì mới in thẻ <nav> ra màn hình
if (!($currentController === 'user' && ($currentAction === 'register' || $currentAction === 'login'))): 
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">
    <img src="Materials/Picture/Passo.png" alt="Logo" width="150" height="50" class="d-inline-block align-top">
  </a>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/Group12-Social-Media-Website/index.php?controller=post&action=showHome">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Categories</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled">
          <button type="button" class="btn btn-primary">
            Notifications <span class="badge badge-light">?</span>
          </button>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?controller=group&action=create">
          <button type="button" class="btn btn-light rounded-circle shadow-sm" title="Tạo nhóm mới">
            <i class="fas fa-plus">Create Group</i>
          </button>
        </a>
      </li>
    </ul>
    
    <form class="form-inline my-2 my-lg-0" action="index.php" method="GET">
      <input type="hidden" name="controller" value="group">
      <input type="hidden" name="action" value="discover">
    
      <input class="form-control mr-sm-2" type="search" name="q" placeholder="Tìm kiếm nhóm..." aria-label="Search" id="search-form">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
    </form>

    <div class="ml-3 d-flex align-items-center">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    Chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="index.php?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>">Hồ sơ cá nhân</a>
                    
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-info" href="index.php?controller=user&action=list">Quản lý hệ thống</a>
                    <?php endif; ?>
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="index.php?controller=user&action=logout">Đăng xuất</a>
                </div>
            </div>
        <?php else: ?>
            <a href="index.php?controller=user&action=login" class="btn btn-outline-primary mr-2">Login</a>
            <a href="index.php?controller=user&action=register" class="btn btn-primary">Register</a>
        <?php endif; ?>
    </div>

  </div>
</nav>

<?php 
// 3. Đóng khối IF lại
endif; 
?>
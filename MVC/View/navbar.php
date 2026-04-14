<?php
// BẮT ĐẦU: KHU VỰC HIỂN THỊ THÔNG BÁO (FLASH MESSAGE)
// Đoạn code này được đặt ở đầu hoặc cuối file dùng chung để luôn chạy và kiểm tra thông báo
if (isset($_SESSION['flash_message'])): ?>
    <script>
        alert("<?= htmlspecialchars($_SESSION['flash_message']) ?>");
    </script>
    <?php unset($_SESSION['flash_message']); // Hiển thị xong thì xóa luôn để không bị lặp lại ?>
<?php endif; 
// KẾT THÚC: KHU VỰC HIỂN THỊ THÔNG BÁO

// 1. Lấy thông tin controller và action hiện tại từ URL
$currentController = $_GET['controller'] ?? '';
$currentAction     = $_GET['action'] ?? '';

// 2. NẾU KHÔNG PHẢI là trang Đăng ký hoặc Đăng nhập thì mới in thẻ <nav> ra màn hình
if (!($currentController === 'user' && ($currentAction === 'register' || $currentAction === 'login'))): 
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <a class="navbar-brand" href="index.php" style="margin-right: 65px;">
    <img src="Materials/Picture/Passo.png" alt="Logo" width="150" height="50" class="d-inline-block align-top">
  </a>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    
    <ul class="navbar-nav mr-auto align-items-center">
      <li class="nav-item active">
        <a class="nav-link" href="index.php?controller=post&action=showHome">Home <span class="sr-only">(current)</span></a>
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
     <li class="nav-item dropdown">
  <button id="notiBtn" class="btn btn-primary font-weight-bold position-relative">
    🔔 Notifications
    <span id="notiCount" class="badge badge-light ml-1">0</span>
  </button>

 <div id="notiDropdown" class="dropdown-menu dropdown-menu-right p-2"
     style="width:320px; max-height:400px; overflow:auto;">
       
    <div id="notiList" class="text-center text-muted">
      Đang tải...
    </div>
  </div>
</li>
      <li class="nav-item ml-2">
        <a class="nav-link p-0" href="index.php?controller=group&action=create">
          <button type="button" class="btn btn-outline-secondary rounded-pill font-italic" title="Tạo nhóm mới">
            Create Group
          </button>
        </a>
      </li>
    </ul>
    
    <form class="form-inline my-2 my-lg-0" action="index.php" method="GET">
      <input type="hidden" name="controller" value="search">
      <input type="hidden" name="action" value="find">
    
      <input class="form-control mr-sm-2"
      type="search"
      name="searchResults"
      placeholder="Search..."
      value="<?= htmlspecialchars($keyword ?? '') ?>">

      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>

    <div class="ml-4 d-flex align-items-center">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    Chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="/Group12-Social-Media-Website/ndex.php?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>">Hồ sơ cá nhân</a>
                    
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-info" href="/Group12-Social-Media-Website/index.php?controller=user&action=list">Quản lý hệ thống</a>
                    <?php endif; ?>
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="/Group12-Social-Media-Website/index.php?controller=user&action=logout">Đăng xuất</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/Group12-Social-Media-Website/index.php?controller=user&action=login" class="btn btn-outline-primary mr-2">Login</a>
            <a href="/Group12-Social-Media-Website/index.php?controller=user&action=register" class="btn btn-primary">Register</a>
        <?php endif; ?>
    </div>

  </div>
</nav>

<?php 
// 3. Đóng khối IF lại
endif; 
?>
<script>
$(document).ready(function(){

    function loadNotifications(){
        $("#notiList").load("/Group12-Social-Media-Website/index.php?controller=notification&action=get");
    }

    function loadCount(){
        $.get("/Group12-Social-Media-Website/index.php?controller=notification&action=count", function(res){
            $("#notiCount").text(res.count);
        });
    }

    // load lần đầu
    loadNotifications();
    loadCount();

    // 🔥 auto refresh
    setInterval(function(){
        loadNotifications();
        loadCount();
    }, 5000);

    // mở dropdown
    $("#notiBtn").click(function(e){
    e.stopPropagation();
    $("#notiDropdown").toggleClass("show");
});

$(document).click(function(){
    $("#notiDropdown").removeClass("show");
});
    // mark read
    $(document).on("click", ".noti-item", function(){

        let id = $(this).data("id");

        $.post("/Group12-Social-Media-Website/index.php?controller=notification&action=markRead", {
            notification_id: id
        });

        $(this).removeClass("font-weight-bold");
    });

});
</script>
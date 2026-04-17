<!DOCTYPE html>
<html>
<head>
    <title>Quên mật khẩu - Passo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="text-center mb-4">
                            <a href="index.php">
                                <img src="Materials/Picture/Passo.png" alt="Passo Logo" style="max-height: 60px;">
                            </a>
                        </div>

                        <h4 class="text-center mb-4 text-primary font-weight-bold">Tìm lại tài khoản của bạn</h4>
                        <p class="text-muted text-center mb-4">Vui lòng nhập tên đăng nhập và email của bạn để tìm kiếm tài khoản và đặt mật khẩu mới.</p>
                        
                        <form action="index.php?controller=user&action=processReset" method="POST">
                            <div class="form-group">
                                <label class="font-weight-bold">Tên đăng nhập</label>
                                <input type="text" name="username" class="form-control form-control-lg" placeholder="Nhập username của bạn" required autofocus>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Địa chỉ Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Nhập email đã đăng ký" required>
                            </div>

                            <hr class="my-4">

                            <div class="form-group">
                                <label class="font-weight-bold text-success">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control form-control-lg" placeholder="Nhập mật khẩu mới" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block btn-lg mt-4 font-weight-bold">Đổi mật khẩu</button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <a href="index.php?controller=user&action=login" class="text-secondary font-weight-bold">Hủy và quay lại Đăng nhập</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php if (isset($_SESSION['flash_message'])): ?>
        <script>
            alert("<?= htmlspecialchars($_SESSION['flash_message']) ?>");
        </script>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>
    
</html>
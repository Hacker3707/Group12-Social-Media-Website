<!DOCTYPE html>
<html>
<head>
    <title>Passo - Đăng nhập hoặc Đăng ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; } 
        /* Căn chỉnh logo hình ảnh */
        .fb-logo-img { width: 300px; margin-left: -20px; margin-bottom: 10px; }
        .fb-tagline { font-size: 1.75rem; font-weight: 400; line-height: 32px; width: 500px; color: #1c1e21; }
        .login-card { border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1); padding: 20px; background: #fff; }
        .btn-login { background-color: #1877f2; font-size: 1.25rem; font-weight: bold; padding: 10px; border: none; }
        .btn-register { background-color: #42b72a; font-size: 1.1rem; font-weight: bold; padding: 10px 20px; border-radius: 6px; color: white; border: none; display: inline-block; }
        .btn-register:hover { background-color: #36a420; color: white; text-decoration: none; }
    </style>
</head>
<body>

    <div class="container d-flex align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-between">
            
            <div class="col-md-6 d-flex flex-column justify-content-center pb-5">
                <img src="Materials/Picture/Passo.png" alt="Passo Logo" class="fb-logo-img">
                <h2 class="fb-tagline">Chào mừng đến với Passo, nơi đồ second hand tìm được chủ mới dễ dàng hơn.</h2>
            </div>
            
            <div class="col-md-5">
                <div class="login-card">
                    <form action="index.php?controller=user&action=authenticate" method="POST">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="Email hoặc tên đăng nhập" required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Mật khẩu" required>
                        </div>
                        <button type="submit" class="btn btn-login btn-primary btn-block shadow-none">Đăng nhập</button>
                    </form>
                    
                    <div class="text-center mt-3 mb-3">
                        <a href="index.php?controller=user&action=forgotPassword" style="text-decoration: none; color: #1877f2; font-size: 14px;">Quên mật khẩu?</a>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center mt-4 mb-2">
                        <a href="index.php?controller=user&action=register" class="btn btn-register">Tạo tài khoản mới</a>
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
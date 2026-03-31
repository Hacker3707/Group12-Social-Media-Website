<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'MVC/View/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4 text-primary">Đăng nhập</h3>
                        
                        <form action="index.php?controller=user&action=authenticate" method="POST">
                            <div class="form-group">
                                <label class="font-weight-bold">Tên đăng nhập</label>
                                <input type="text" name="username" class="form-control" placeholder="Nhập username" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block mt-4">Đăng nhập</button>
                        </form>

                        <div class="text-center mt-3">
                            <small>Chưa có tài khoản? <a href="index.php?controller=user&action=register">Đăng ký</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
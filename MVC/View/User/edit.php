<!DOCTYPE html>
<head>
    <title>Sửa người dùng</title>
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
                <div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 font-weight-bold text-primary">Chỉnh sửa hồ sơ</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?controller=user&action=update">
                            <input type="hidden" name="userId" value="<?= $user['UserID'] ?>">
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['Username']) ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['Phone']) ?>">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Tiểu sử (Bio)</label>
                                <textarea name="bio" class="form-control" rows="4"><?= htmlspecialchars($user['Bio']) ?></textarea>
                            </div>
                            
                            <hr>
                            <div class="text-right">
                                <a href="index.php?controller=user&action=list" class="btn btn-light mr-2">Hủy bỏ</a>
                                <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
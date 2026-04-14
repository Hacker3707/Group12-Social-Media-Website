<!DOCTYPE html>
<html>
<head>
    <title>Chỉnh sửa hồ sơ - Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">
    
    <style>
        body { background-color: #f0f2f5; } /* Màu nền xám nhạt đặc trưng của FB */
        .settings-card { border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); border: none; }
        .card-header-custom { background-color: #fff; border-bottom: 1px solid #ddd; padding: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        
        /* Chỉnh sửa ô nhập liệu */
        .form-control { background-color: #f2f3f5; border: 1px solid #ccd0d5; border-radius: 6px; }
        .form-control:focus { background-color: #fff; box-shadow: 0 0 0 2px #e7f3ff; border-color: #1877f2; }
        
        /* Chỉnh sửa nút bấm */
        .btn-save { background-color: #1877f2; color: white; font-weight: bold; border-radius: 6px; padding: 8px 20px; border: none;}
        .btn-save:hover { background-color: #166fe5; color: white; }
        .btn-cancel { background-color: #e4e6eb; color: #050505; font-weight: bold; border-radius: 6px; padding: 8px 20px; text-decoration: none;}
        .btn-cancel:hover { background-color: #d8dadf; color: #050505; text-decoration: none; }
    </style>
</head>
<body>
    <?php include 'MVC/View/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card settings-card">
                    <div class="card-header-custom">
                        <h3 class="mb-0 font-weight-bold">Chỉnh sửa chi tiết công khai</h3>
                        <p class="text-muted mb-0">Tùy chỉnh các thông tin hiển thị trên trang cá nhân của bạn.</p>
                    </div>
                    <div class="card-body p-4 bg-white" style="border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                        
                        <form method="POST" action="index.php?controller=user&action=update" enctype="multipart/form-data">
                            <input type="hidden" name="userId" value="<?= $user['UserID'] ?>">
                            
                            <div class="form-group row align-items-center mb-4">
                                <label class="col-sm-3 col-form-label font-weight-bold text-md-right">Ảnh đại diện</label>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <img src="<?= !empty($user['AvatarFP']) ? htmlspecialchars($user['AvatarFP']) : 'https://via.placeholder.com/80' ?>" 
                                         alt="Avatar" 
                                         class="rounded-circle mr-3 shadow-sm" 
                                         style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e4e6eb;">
                                    
                                    <div>
                                        <input type="file" name="avatar" class="form-control-file" accept="image/jpeg, image/png, image/jpg, image/gif">
                                        <small class="form-text text-muted mt-1">Hỗ trợ JPG, PNG, GIF. Ảnh sẽ được tự động cắt vuông.</small>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-4">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 col-form-label font-weight-bold text-md-right">Tên đăng nhập</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['Username']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 col-form-label font-weight-bold text-md-right">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" required>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 col-form-label font-weight-bold text-md-right">Số điện thoại</label>
                                <div class="col-sm-9">
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['Phone'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold text-md-right">Tiểu sử (Bio)</label>
                                <div class="col-sm-9">
                                    <textarea name="bio" class="form-control" rows="4" placeholder="Mô tả về bản thân bạn..."><?= htmlspecialchars($user['Bio'] ?? '') ?></textarea>
                                    <small class="form-text text-muted mt-2">Chi tiết này sẽ hiển thị công khai trên trang cá nhân của bạn.</small>
                                </div>
                            </div>
                            
                            <hr class="mt-4 mb-4">

                            <div class="d-flex justify-content-end">
                                <a href="index.php?controller=user&action=profile&id=<?= $user['UserID'] ?>" class="btn btn-cancel mr-2">Hủy</a>
                                <button type="submit" class="btn btn-save">Lưu thay đổi</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($user['Username']) ?> | Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .cover-photo { height: 350px; background-color: #ced4da; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; overflow: hidden; position: relative; }
        /* Giả lập ảnh bìa nếu chưa có */
        .cover-photo img { width: 100%; height: 100%; object-fit: cover; }
        
        .profile-header { background: #fff; padding-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        
        .profile-name { font-size: 2rem; font-weight: 700; margin-bottom: 0; }
        .profile-bio { color: #65676b; font-size: 1.1rem; }
        
        .content-section { margin-top: 20px; }
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-title { font-weight: 700; font-size: 1.25rem; }

        .avatar-container {
            width: 168px;
            height: 168px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            margin-top: -100px;
            margin-bottom: 15px;
            margin-left: auto;
            margin-right: auto;
            background-color: #fff;
        }

        .avatar-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            border: 4px solid #fff;
        }
    </style>
</head>
<body>
    <div class="row" id = "navbar">
        <div class= "col-md-12 col-12">
            <?php include 'MVC/View/navbar.php'; ?>
        </div>
    </div>

    <div class="profile-header">
        <div class="container">
            <div class="cover-photo">
                <img src="https://via.placeholder.com/1200x350/cccccc/ffffff?text=Cover+Photo" alt="Cover">
            </div>
            
            <div class="avatar-container">
                <img class="img-fluid" src="<?= !empty($user['AvatarFP']) ? $user['AvatarFP'] : 'https://via.placeholder.com/168/007bff/ffffff?text='.strtoupper(substr($user['Username'], 0, 1)) ?>" alt="Avatar">
            </div>
            
            <div class="text-center">
                <h1 class="profile-name"><?= htmlspecialchars($user['Username']) ?></h1>
                <p class="profile-bio"><?= !empty($user['Bio']) ? htmlspecialchars($user['Bio']) : 'Chưa có tiểu sử.' ?></p>
                
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['UserID']): ?>
                    <a href="index.php?controller=user&action=edit&id=<?= $user['UserID'] ?>" class="btn btn-light font-weight-bold mt-2"><i class="fas fa-pen"></i> Chỉnh sửa trang cá nhân</a>
                <?php else: ?>
                    <button class="btn btn-primary font-weight-bold mt-2">Thêm bạn bè</button>
                    <button class="btn btn-light font-weight-bold mt-2 ml-2">Nhắn tin</button>
                <?php endif; ?>
            </div>
            
            <hr class="mt-4 mb-0">
            <ul class="nav nav-pills justify-content-center mt-2 font-weight-bold text-muted">
                <li class="nav-item"><a class="nav-link active" href="#">Bài viết</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#">Giới thiệu</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#">Bạn bè</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#">Ảnh</a></li>
            </ul>
        </div>
    </div>

    <div class="container content-section">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-custom p-3">
                    <h5 class="card-title">Giới thiệu</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Điện thoại:</strong> <?= !empty($user['Phone']) ? htmlspecialchars($user['Phone']) : 'Đang cập nhật' ?></li>
                        <li class="mb-2"><strong>Trạng thái:</strong> <?= ucfirst($user['AccountStatus']) ?></li>
                    </ul>
                </div>
                
                <div class="card card-custom p-3">
                    <h5 class="card-title">Ảnh</h5>
                    <div class="row no-gutters">
                        <div class="col-4 p-1"><div style="height: 100px; background: #ddd; border-radius: 8px;"></div></div>
                        <div class="col-4 p-1"><div style="height: 100px; background: #ddd; border-radius: 8px;"></div></div>
                        <div class="col-4 p-1"><div style="height: 100px; background: #ddd; border-radius: 8px;"></div></div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['UserID']): ?>
                <div class="card card-custom p-3 mb-3">
                    <div class="d-flex align-items-center">
                        <img src="<?= !empty($user['AvatarFP']) ? $user['AvatarFP'] : 'https://via.placeholder.com/40' ?>" class="rounded-circle mr-2" width="40" height="40">
                        <input type="text" class="form-control rounded-pill bg-light" placeholder="<?= htmlspecialchars($user['Username']) ?> ơi, bạn đang nghĩ gì thế?" style="border: none; cursor: pointer;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="card card-custom p-3 text-center">
                    <h5 class="text-muted mt-3 mb-3">Chưa có bài viết nào để hiển thị.</h5>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>
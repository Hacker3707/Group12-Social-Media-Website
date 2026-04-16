<!DOCTYPE html>
<html>
<head>
    <title>Nhóm của bạn | Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .group-card { border-radius: 8px; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: 0.2s; }
        .group-card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .group-cover-sm { height: 120px; border-top-left-radius: 8px; border-top-right-radius: 8px; object-fit: cover; width: 100%; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">

</head>
<body>
    <?php include 'MVC/View/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-weight-bold">Nhóm của bạn</h2>
            <a href="index.php?controller=group&action=create" class="btn btn-primary font-weight-bold">+ Tạo nhóm mới</a>
        </div>

        <div class="row">
            <?php if (empty($myGroups)): ?>
                <div class="col-12 text-center mt-5">
                    <h4 class="text-muted">Bạn chưa tham gia nhóm nào.</h4>
                    <p>Hãy khám phá các nhóm hoặc tự tạo một nhóm cho riêng mình nhé!</p>
                </div>
            <?php else: ?>
                <?php foreach ($myGroups as $group): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card group-card h-100">
                            <img src="https://via.placeholder.com/400x150/1877f2/ffffff?text=<?= urlencode(substr($group['GroupName'], 0, 1)) ?>" class="group-cover-sm" alt="Cover">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font-weight-bold text-truncate" title="<?= htmlspecialchars($group['GroupName']) ?>">
                                    <?= htmlspecialchars($group['GroupName']) ?>
                                </h5>
                                
                                <p class="card-text text-muted mb-3 flex-grow-1">
                                    <small><i class="fas fa-globe"></i> Nhóm <?= $group['Privacy'] === 'public' ? 'Công khai' : 'Riêng tư' ?></small>
                                </p>
                                
                                <a href="index.php?controller=group&action=detail&id=<?= $group['GroupID'] ?>" class="btn btn-light btn-block font-weight-bold text-primary mt-auto" style="background-color: #e7f3ff;">
                                    Xem nhóm
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
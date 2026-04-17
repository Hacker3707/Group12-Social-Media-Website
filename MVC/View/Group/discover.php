<!DOCTYPE html>
<html>
<head>
    <title>Khám phá nhóm | Passo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .group-card { border-radius: 8px; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .group-cover-sm { height: 120px; border-top-left-radius: 8px; border-top-right-radius: 8px; object-fit: cover; width: 100%; }
    </style>
</head>
<body>
    <?php include 'MVC/View/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <h2 class="font-weight-bold">Khám phá các nhóm</h2>
                <p class="text-muted">Tìm kiếm và tham gia những cộng đồng có cùng sở thích với bạn.</p>
            </div>
            
            <div class="col-12 col-md-6">
                <form action="index.php" method="GET" class="d-flex flex-column flex-sm-row">
                    <input type="hidden" name="controller" value="group">
                    <input type="hidden" name="action" value="discover">
                    <input type="text" name="q" class="form-control mr-sm-2 mb-2 mb-sm-0" placeholder="Nhập tên nhóm bạn muốn tìm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <div class="row">
            <?php if (empty($groups)): ?>
                <div class="col-12 text-center mt-5">
                    <h5 class="text-muted">Không tìm thấy nhóm nào phù hợp.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($groups as $group): ?>
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="card group-card h-100">
                            <img src="https://via.placeholder.com/400x150/1877f2/ffffff?text=<?= urlencode(substr($group['GroupName'], 0, 1)) ?>" class="group-cover-sm">
                            <div class="card-body d-flex flex-column">
                                <h6 class="font-weight-bold text-truncate"><?= htmlspecialchars($group['GroupName']) ?></h6>
                                <p class="small text-muted mb-3 flex-grow-1"><?= htmlspecialchars($group['Description'] ?? 'Chưa có mô tả') ?></p>
                                <a href="index.php?controller=group&action=detail&id=<?= $group['GroupID'] ?>" class="btn btn-light btn-block btn-sm font-weight-bold text-primary" style="background-color: #e7f3ff;">Xem nhóm</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<head>
    <title>Thành viên Nhóm</title>
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 font-weight-bold text-primary">Thành viên nhóm #<?= $_GET['id'] ?></h4>
                        <a href="index.php?controller=group&action=list" class="btn btn-outline-secondary btn-sm">Quay lại</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($members)): ?>
                            <div class="p-4 text-center text-muted">
                                <h5>Nhóm này chưa có thành viên nào.</h5>
                            </div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($members as $m): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle mr-3" style="width: 40px; height: 40px;"></div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold"><?= htmlspecialchars($m['Username']) ?></h6>
                                            <small class="text-muted">ID: <?= $m['UserID'] ?></small>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge badge-<?= ($m['Role'] == 'admin') ? 'danger' : 'light border' ?> mr-3 p-2">
                                            <?= strtoupper($m['Role']) ?>
                                        </span>
                                        <a href="index.php?controller=group&action=removeUser&groupId=<?= $_GET['id'] ?>&userId=<?= $m['UserID'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Trục xuất thành viên này?')">Khai trừ</a>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
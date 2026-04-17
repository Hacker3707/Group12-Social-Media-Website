<!DOCTYPE html>
<html>
<head>
    <title>Chỉnh sửa Nhóm | Passo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>body { background-color: #f0f2f5; }</style>
</head>
<body>
    <?php include 'MVC/View/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card p-4 border-0 shadow-sm rounded-lg">
                    <h3 class="font-weight-bold mb-4">Chỉnh sửa thông tin nhóm</h3>
                    <form action="index.php?controller=group&action=update" method="POST">
                        <input type="hidden" name="groupId" value="<?= $group['GroupID'] ?>">
                        <div class="form-group">
                            <label class="font-weight-bold">Tên nhóm</label>
                            <input type="text" name="group_name" class="form-control" value="<?= htmlspecialchars($group['GroupName']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả nhóm</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($group['Description'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Quyền riêng tư</label>
                            <select class="form-control" name="privacy">
                                <option value="public" <?= $group['Privacy'] === 'public' ? 'selected' : '' ?>>Công khai</option>
                                <option value="private" <?= $group['Privacy'] === 'private' ? 'selected' : '' ?>>Riêng tư</option>
                            </select>
                        </div>
                        <div class="mt-4 d-flex flex-column flex-sm-row">
                            <a href="index.php?controller=group&action=detail&id=<?= $group['GroupID'] ?>" class="btn btn-light mr-sm-2 mb-2 mb-sm-0">Hủy</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
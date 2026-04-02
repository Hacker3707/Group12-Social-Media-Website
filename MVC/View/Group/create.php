<!DOCTYPE html>
<html>
<head>
    <title>Tạo nhóm mới - Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .create-card { border-radius: 8px; border: none; box-shadow: 0 12px 28px rgba(0,0,0,0.1); }
        .form-control-f { background-color: #f0f2f5; border: 1px solid #ddd; border-radius: 6px; padding: 12px; }
    </style>
</head>
<body>
    <?php include 'MVC/View/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card create-card p-4">
                    <h2 class="font-weight-bold mb-4">Tạo nhóm</h2>
                    <form action="index.php?controller=group&action=store" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Tên nhóm</label>
                            <input type="text" name="group_name" class="form-control-f w-100" placeholder="Nhập tên nhóm của bạn" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Quyền riêng tư</label>
                            <select class="form-control-f w-100" name="privacy">
                                <option value="public">Công khai</option>
                                <option value="private">Riêng tư</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold mt-4 py-2">Tạo nhóm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
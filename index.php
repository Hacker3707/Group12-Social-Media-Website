<?php
session_start();

// 1. Kiểm tra trạng thái đăng nhập (đã có session user_id chưa?)
$isLoggedIn = isset($_SESSION['user_id']);

// 2. Quyết định trang mặc định nếu URL trống
// Nếu đã login -> về trang chủ (post/showHome)
// Nếu chưa login -> bắt buộc ra trang đăng nhập (user/login)
$defaultController = $isLoggedIn ? 'post' : 'user';
$defaultAction     = $isLoggedIn ? 'showHome' : 'login';

// 3. Lấy tham số từ URL, nếu URL không có thì dùng giá trị mặc định ở trên
$controllerName = $_GET['controller'] ?? $defaultController;
$actionName     = $_GET['action']     ?? $defaultAction;

// 4. Khởi tạo và chạy Controller
$controllerClassName = ucfirst($controllerName)."Controller";

// Thêm đoạn kiểm tra file tồn tại để web không bị sập (Fatal Error) nếu gõ sai URL
$controllerPath = "MVC/Controller/$controllerClassName.php";

if (file_exists($controllerPath)) {
    include_once $controllerPath;
    
    $controller = new $controllerClassName();
    
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        die("Lỗi: Action '$actionName' không tồn tại!");
    }
} else {
    die("Lỗi: Controller '$controllerClassName' không tồn tại!");
}
?>
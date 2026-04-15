<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$sessionTimeout = 1800; // 30 minutes idle timeout
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
ini_set('session.gc_maxlifetime', (string)$sessionTimeout);
session_set_cookie_params([
    'lifetime' => $sessionTimeout,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (isset($_SESSION['last_activity']) && (time() - (int)$_SESSION['last_activity']) > $sessionTimeout) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
    }
    session_destroy();
    session_start();
}

$_SESSION['last_activity'] = time();

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
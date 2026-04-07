<?php
include_once __DIR__ . "/User.php";

class Admin extends User {
    
    // Hàm khởi tạo của Admin sẽ tự động gán cứng userRole là 'admin'
    public function __construct(
        ?int $userId, string $username, string $email, string $accountPassword, 
        string $accountStatus = 'active', ?string $avatarFp = null, ?string $phone = null, ?string $bio = null
    ) {
        parent::__construct($userId, $username, $email, $accountPassword, 'admin', $accountStatus, $avatarFp, $phone, $bio);
    }

    // Khai báo đặc quyền: Admin CÓ quyền quản lý hệ thống
    public function canManageSystem(): bool {
        return true; 
    }
}
?>
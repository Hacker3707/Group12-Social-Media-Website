<?php
include_once __DIR__ . "/User.php";

class Member extends User {
    
    // Hàm khởi tạo của Member sẽ tự động gán cứng userRole là 'user'
    public function __construct(
        ?int $userId, string $username, string $email, string $accountPassword, 
        string $accountStatus = 'active', ?string $avatarFp = null, ?string $phone = null, ?string $bio = null
    ) {
        parent::__construct($userId, $username, $email, $accountPassword, 'user', $accountStatus, $avatarFp, $phone, $bio);
    }

    // Khai báo đặc quyền: Member KHÔNG CÓ quyền quản lý hệ thống
    public function canManageSystem(): bool {
        return false; 
    }
}
?>
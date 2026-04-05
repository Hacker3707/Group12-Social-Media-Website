<?php
// abstract: Lớp trừu tượng, không thể dùng lệnh "new User()"
abstract class User {
    // protected: Cho phép các lớp con (Admin, Member) sử dụng các biến này
    protected ?int $userId;
    protected string $username;
    protected string $email;
    protected string $accountPassword; // Sửa theo đúng tên cột CSDL của bạn
    protected ?string $avatarFp;       // Sửa theo đúng tên cột CSDL của bạn
    protected ?string $phone;
    protected ?string $bio;
    protected string $userRole;
    protected string $accountStatus;

    public function __construct(
        ?int $userId, string $username, string $email, string $accountPassword, 
        string $userRole, string $accountStatus = 'active', 
        ?string $avatarFp = null, ?string $phone = null, ?string $bio = null
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->accountPassword = $accountPassword;
        $this->userRole = $userRole;
        $this->accountStatus = $accountStatus;
        $this->avatarFp = $avatarFp;
        $this->phone = $phone;
        $this->bio = $bio;
    }

    // ================= GETTERS =================
    public function getUserId(): ?int { return $this->userId; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getAccountPassword(): string { return $this->accountPassword; }
    public function getUserRole(): string { return $this->userRole; }
    public function getAccountStatus(): string { return $this->accountStatus; }
    public function getAvatarFp(): ?string { return $this->avatarFp; }
    public function getPhone(): ?string { return $this->phone; }
    public function getBio(): ?string { return $this->bio; }

    // ================= ABSTRACT METHODS =================
    // Mọi lớp con BẮT BUỘC phải code nội dung cho hàm này
    abstract public function canManageSystem(): bool;
}
?>
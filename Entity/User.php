<?php
class User
{
    private ?int $userId;
    private string $username;
    private string $email;
    private ?string $password;
    private ?string $avatarFp;
    private ?string $phone;
    private ?string $bio;
    private string $userRole;
    private string $accountStatus;
    private ?string $createdAt;

    public function __construct(
        ?int $userId = null,
        string $username = '',
        string $email = '',
        string $userRole = 'user',
        string $accountStatus = 'active'
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->userRole = $userRole;
        $this->accountStatus = $accountStatus;
    }

    // ================= GETTERS =================
    public function getUserId(): ?int { return $this->userId; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getUserRole(): string { return $this->userRole; }
    
    // ĐÂY LÀ HÀM BỊ THIẾU GÂY RA LỖI:
    public function getAccountStatus(): string { return $this->accountStatus; }

    // Các hàm cho Bio và Phone (nếu cần dùng dưới dạng Object)
    public function getBio(): ?string { return $this->bio; }
    public function getPhone(): ?string { return $this->phone; }

    // ================= SETTERS =================
    public function setUsername(string $username): void { $this->username = $username; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setUserRole(string $role): void { $this->userRole = $role; }
    public function setAccountStatus(string $status): void { $this->accountStatus = $status; }
    public function setBio(?string $bio): void { $this->bio = $bio; }
    public function setPhone(?string $phone): void { $this->phone = $phone; }
}
?>
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

    // Getters & Setters
    public function getUserId(): ?int { return $this->userId; }
    
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getUserRole(): string { return $this->userRole; }
    public function setUserRole(string $role): void { $this->userRole = $role; }

    // Bạn có thể thêm các method logic nghiệp vụ nhẹ nhàng
    public function isActive(): bool {
        return $this->accountStatus === 'active';
    }
}
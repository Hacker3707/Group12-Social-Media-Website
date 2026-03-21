<?php
class GroupMember
{
    private int $userId;
    private int $groupId;
    private string $role;
    // Có thể chứa thêm đối tượng User hoặc Group nếu cần mapping sâu hơn
    private ?User $user = null; 

    public function __construct(int $userId = 0, int $groupId = 0, string $role = 'member')
    {
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->role = $role;
    }

    // Getters & Setters
    public function getUserId(): int { return $this->userId; }
    public function getGroupId(): int { return $this->groupId; }
    
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }

    // Hỗ trợ lưu trữ thông tin User đi kèm khi truy vấn join
    public function setUser(User $user): void { $this->user = $user; }
    public function getUser(): ?User { return $this->user; }
}
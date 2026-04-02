<?php
class Group
{
    private ?int $groupId;
    private ?int $categoryId = null; // Thêm giá trị mặc định là null
    private string $groupName;
    private string $description = ''; // Thêm giá trị mặc định là chuỗi rỗng
    private string $privacy; // public hoặc private
    private ?string $createdAt = null; // Thêm giá trị mặc định là null

    public function __construct(
        ?int $groupId = null,
        string $groupName = '',
        string $privacy = 'public'
    ) {
        $this->groupId = $groupId;
        $this->groupName = $groupName;
        $this->privacy = $privacy;
    }

    // ================= GETTERS & SETTERS =================

    // Group ID
    public function getGroupId(): ?int { return $this->groupId; }
    public function setGroupId(?int $groupId): void { $this->groupId = $groupId; }

    // Category ID (Đây là hàm sửa lỗi "Call to undefined method")
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function setCategoryId(?int $categoryId): void { $this->categoryId = $categoryId; }

    // Group Name
    public function getGroupName(): string { return $this->groupName; }
    public function setGroupName(string $name): void { $this->groupName = $name; }

    // Description
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $desc): void { $this->description = $desc; }

    // Privacy
    public function getPrivacy(): string { return $this->privacy; }
    public function setPrivacy(string $privacy): void { $this->privacy = $privacy; }

    // Created At
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
}
?>
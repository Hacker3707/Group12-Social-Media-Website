<?php
class Group
{
    private ?int $groupId;
    private ?int $categoryId;
    private string $groupName;
    private string $description;
    private string $privacy; // public hoặc private
    private ?string $createdAt;

    public function __construct(
        ?int $groupId = null,
        string $groupName = '',
        string $privacy = 'public'
    ) {
        $this->groupId = $groupId;
        $this->groupName = $groupName;
        $this->privacy = $privacy;
    }

    // Getters & Setters
    public function getGroupId(): ?int { return $this->groupId; }

    public function getGroupName(): string { return $this->groupName; }
    public function setGroupName(string $name): void { $this->groupName = $name; }

    public function getPrivacy(): string { return $this->privacy; }
    public function setPrivacy(string $privacy): void { $this->privacy = $privacy; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $desc): void { $this->description = $desc; }
}
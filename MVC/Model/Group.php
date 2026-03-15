<?php
class GroupModel extends Model
{
    private ?int $groupId = null;
    private int $categoryId;
    private string $groupName;

    public function __construct(?int $categoryId = null, ?string $groupName = null)
    {
        parent::__construct();
        $this->categoryId = $categoryId ?? 0;
        $this->groupName = $groupName ?? '';
    }

    public function all(): array
    {
        $sql = 'SELECT g.*, c.CategoryName
                FROM `group` g
                LEFT JOIN category c ON g.CategoryID = c.CategoryID
                ORDER BY g.GroupID DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function create(): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `group` (CategoryID, GroupName) VALUES (:categoryId, :groupName)'
        );
        return $stmt->execute([
            'categoryId' => $this->categoryId,
            'groupName' => $this->groupName,
        ]);
    }

    public function addMember(int $groupId, int $userId, string $role = 'member'): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO group_member (UserID, GroupID, Role) VALUES (:userId, :groupId, :role)'
        );
        return $stmt->execute([
            'userId' => $userId,
            'groupId' => $groupId,
            'role' => $role,
        ]);
    }
}
<?php
class GroupMember extends Model
{
    public function getMembers(int $groupId): array
    {
        $stmt = $this->db->prepare(
            'SELECT gm.*, u.Username, u.Email
             FROM group_member gm
             INNER JOIN users u ON gm.UserID = u.UserID
             WHERE gm.GroupID = :groupId'
        );
        $stmt->execute(['groupId' => $groupId]);
        return $stmt->fetchAll();
    }
}
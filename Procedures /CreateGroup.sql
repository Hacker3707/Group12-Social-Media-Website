DELIMITER $$

CREATE PROCEDURE CreateGroup(
    IN p_group_name VARCHAR(100),
    IN p_description VARCHAR(255),
    IN p_creator_id INT
)
BEGIN
    DECLARE new_group_id INT;

    -- Tạo nhóm mới
    INSERT INTO Groups(group_name, description, creator_id, created_at)
    VALUES (p_group_name, p_description, p_creator_id, NOW());

    -- Lấy ID nhóm vừa tạo
    SET new_group_id = LAST_INSERT_ID();

    -- Thêm người tạo vào GroupMembers với vai trò Admin
    INSERT INTO GroupMembers(group_id, user_id, role, joined_at)
    VALUES (new_group_id, p_creator_id, 'Admin', NOW());

END$$

DELIMITER ;

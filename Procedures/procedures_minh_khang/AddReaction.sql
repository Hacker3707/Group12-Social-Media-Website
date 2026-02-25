DELIMITER $$

CREATE PROCEDURE sp_CreateGroup(
    IN p_name VARCHAR(100),
    IN p_description TEXT,
    IN p_created_by INT
)
BEGIN
    DECLARE new_group_id INT;

    -- Kiểm tra nhóm đã tồn tại chưa
    IF EXISTS (
        SELECT 1 FROM groups WHERE name = p_name
    ) THEN
    
        SELECT 'Group name already exists' AS message;
        
    ELSE
    
        -- Tạo nhóm mới
        INSERT INTO groups(name, description, created_by, created_at)
        VALUES(p_name, p_description, p_created_by, NOW());

        -- Lấy ID nhóm vừa tạo
        SET new_group_id = LAST_INSERT_ID();

        -- Thêm người tạo làm admin
        INSERT INTO group_members(group_id, user_id, role)
        VALUES(new_group_id, p_created_by, 'admin');

        SELECT 'Group created successfully' AS message;
        
    END IF;

END $$

DELIMITER ;

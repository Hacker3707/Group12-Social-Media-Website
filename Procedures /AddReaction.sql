DELIMITER $$

CREATE PROCEDURE AddReaction(
    IN p_user_id INT,
    IN p_post_id INT,
    IN p_reaction_type VARCHAR(20)
)
BEGIN
    DECLARE reaction_count INT;

    -- Kiểm tra user đã reaction chưa
    SELECT COUNT(*) INTO reaction_count
    FROM Reactions
    WHERE user_id = p_user_id AND post_id = p_post_id;

    IF reaction_count = 0 THEN
        -- Nếu chưa có reaction thì thêm mới
        INSERT INTO Reactions(user_id, post_id, reaction_type, created_at)
        VALUES (p_user_id, p_post_id, p_reaction_type, NOW());
    ELSE
        -- Nếu đã có thì cập nhật lại loại reaction
        UPDATE Reactions
        SET reaction_type = p_reaction_type,
            created_at = NOW()
        WHERE user_id = p_user_id AND post_id = p_post_id;
    END IF;

END$$

DELIMITER ;

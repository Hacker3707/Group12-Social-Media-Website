DELIMITER $$

CREATE PROCEDURE sp_AddReaction(
    IN p_user_id INT,
    IN p_post_id INT,
    IN p_type VARCHAR(20)
)
BEGIN
    IF EXISTS (
        SELECT 1 FROM reactions
        WHERE user_id = p_user_id AND post_id = p_post_id
    ) THEN
    
        UPDATE reactions
        SET type = p_type,
            created_at = NOW()
        WHERE user_id = p_user_id AND post_id = p_post_id;
        
    ELSE
    
        INSERT INTO reactions(user_id, post_id, type, created_at)
        VALUES(p_user_id, p_post_id, p_type, NOW());
        
    END IF;

END $$

DELIMITER ;

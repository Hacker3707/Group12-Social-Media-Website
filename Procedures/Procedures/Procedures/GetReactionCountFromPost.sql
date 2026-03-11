DELIMITER $$

CREATE PROCEDURE GetReactionCountFromPost (
    IN p_post_id INT
)
BEGIN
    SELECT 
        post_id,
        COUNT(*) AS reaction_count
    FROM Reaction
    WHERE post_id = p_post_id
    GROUP BY post_id;
END$$

DELIMITER ;

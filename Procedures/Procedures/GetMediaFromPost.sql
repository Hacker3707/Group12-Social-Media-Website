DELIMITER $$

CREATE PROCEDURE GetMediaFromPost (
    IN p_post_id INT
)
BEGIN
    SELECT *
    FROM Media
    WHERE post_id = p_post_id;
END$$

DELIMITER ;

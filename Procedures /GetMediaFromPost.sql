DELIMITER $$

CREATE PROCEDURE getMediaFromPost (IN p_postId INT)
BEGIN
    SELECT *
    FROM Media
    WHERE postId = p_postId;
END $$

DELIMITER ;

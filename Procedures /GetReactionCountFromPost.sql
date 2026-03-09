DELIMITER $$

CREATE PROCEDURE getReactionCountFromPost (IN p_postId INT)
BEGIN
    SELECT COUNT(*) AS reactionCount
    FROM Reaction
    WHERE postId = p_postId;
END $$

DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS getReactionCountFromPost$$
CREATE PROCEDURE getReactionCountFromPost(IN postId INT)
BEGIN
    SELECT COUNT(*) AS reaction_count
    FROM reaction
    WHERE post_id = postId;
END$$

DELIMITER ;

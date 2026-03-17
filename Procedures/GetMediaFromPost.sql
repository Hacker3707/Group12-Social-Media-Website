DELIMITER $$

DROP PROCEDURE IF EXISTS getMediaFromPost$$
CREATE PROCEDURE getMediaFromPost(IN postId INT)
BEGIN
    SELECT m.*
    FROM media m
    JOIN post_media pm ON m.media_id = pm.media_id
    WHERE pm.post_id = postId;
END$$

DELIMITER ;

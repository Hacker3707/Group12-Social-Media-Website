DELIMITER $$

CREATE PROCEDURE loadAllCmtfromPost(
    IN p_postID INT
)
BEGIN

SELECT 
    c.CommentID,
    c.Content,
    c.CreatedAt,

    u.UserID,
    u.Username

FROM comment c

JOIN users u
    ON c.UserID = u.UserID

WHERE c.PostID = p_postID

ORDER BY c.CreatedAt ASC;

END $$

DELIMITER ;
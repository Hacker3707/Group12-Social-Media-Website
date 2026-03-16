DELIMITER $$

CREATE PROCEDURE loadAllCmtfromPost(
    IN p_postID INT
)
BEGIN

SELECT 
    c.commentID,
    c.content,
    c.createdAt,

    u.userID,
    u.username

FROM COMMENT c

JOIN USER u
    ON c.authorID = u.userID

WHERE c.postID = p_postID

ORDER BY c.createdAt ASC;

END $$

DELIMITER ;
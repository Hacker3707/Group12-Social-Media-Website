DELIMITER $$

CREATE PROCEDURE GetCommentsByPost(
    IN postID_in INT(11)
)
BEGIN
    SELECT c.CommentID,
           c.Content,
           c.CreatedAt,
           u.Username
    FROM comment c
    JOIN users u ON c.UserID = u.UserID
    WHERE c.PostID = postID_in
    ORDER BY c.CreatedAt DESC;
END $$

DELIMITER ;

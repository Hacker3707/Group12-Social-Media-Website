DELIMITER //

CREATE PROCEDURE addComment(
    IN p_PostID INT,
    IN p_UserID INT,
    IN p_Content TEXT
)
BEGIN

    DECLARE newCommentID INT;
    DECLARE post_count INT;

    SELECT COUNT(*) INTO post_count
    FROM post
    WHERE PostID = p_PostID;

    INSERT INTO comment (CommentID, PostID, UserID, Content)
    VALUES (newCommentID, p_PostID, p_UserID, p_Content);

END //

DELIMITER ;

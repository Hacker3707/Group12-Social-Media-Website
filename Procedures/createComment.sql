DELIMITER //

CREATE PROCEDURE createComment(
    IN p_PostID INT,
    IN p_UserID INT,
    IN p_Content TEXT,
    IN p_ParentCommentID INT
)
BEGIN

    DECLARE post_count INT;

    SELECT COUNT(*) INTO post_count
    FROM post
    WHERE PostID = p_PostID;

    IF post_count = 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Post does not exist';
    END IF;

    INSERT INTO comment (PostID, UserID, Content, CommentParentID)
    VALUES (p_PostID, p_UserID, p_Content, p_ParentCommentID);

END //

DELIMITER ;
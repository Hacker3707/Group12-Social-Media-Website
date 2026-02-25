DELIMITER //

CREATE PROCEDURE AddComment(
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

    IF post_count > 0 THEN


        SELECT IFNULL(MAX(CommentID),0) + 1 INTO newCommentID
        FROM comment;

        INSERT INTO comment (CommentID, PostID, UserID, Content)
        VALUES (newCommentID, p_PostID, p_UserID, p_Content);

    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Post does not exist';
    END IF;

END //

DELIMITER ;
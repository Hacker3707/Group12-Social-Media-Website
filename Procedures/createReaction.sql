DELIMITER //

CREATE PROCEDURE createReaction(
    IN p_post_id INT,
    IN p_user_id INT,
    IN p_comment_id INT,
    IN p_type VARCHAR(50)
)
BEGIN

    DECLARE reacted INT DEFAULT 0;

    -- VALIDATE
    IF p_post_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM post WHERE PostID = p_post_id) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Post does not exist';
        END IF;
    END IF;

    IF p_comment_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM comment WHERE CommentID = p_comment_id) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Comment does not exist';
        END IF;
    END IF;

    -- TOGGLE
    IF EXISTS (
        SELECT 1 FROM reaction
        WHERE UserID = p_user_id
          AND (PostID = p_post_id OR CommentID = p_comment_id)
    ) THEN

        DELETE FROM reaction
        WHERE UserID = p_user_id
          AND (PostID = p_post_id OR CommentID = p_comment_id);

        SET reacted = 0;

    ELSE

        INSERT INTO reaction(PostID, UserID, CommentID, ReactionType)
        VALUES (p_post_id, p_user_id, p_comment_id, p_type);

        SET reacted = 1;

    END IF;

    -- COUNT LẠI từ DB
    SELECT 
        reacted,
        (
            SELECT COUNT(*) FROM reaction 
            WHERE (PostID = p_post_id OR CommentID = p_comment_id)
        ) AS total;

END //

DELIMITER ;
DELIMITER //

CREATE PROCEDURE createReaction(
    IN p_post_id INT,
    IN p_user_id INT,
    IN p_comment_id INT,
    IN p_type VARCHAR(50)
)
BEGIN

    -- Check if post exists
    IF NOT EXISTS (
        SELECT 1 FROM post WHERE PostID = p_post_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Post does not exist';
    END IF;

    -- Check comment if provided
    IF p_comment_id IS NOT NULL THEN
        IF NOT EXISTS (
            SELECT 1 FROM comment 
            WHERE CommentID = p_comment_id
            AND PostID = p_post_id
        ) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Comment does not exist for this post';
        END IF;
    END IF;

    -- Insert reaction
    INSERT INTO reaction(PostID, UserID, CommentID, ReactionType)
    VALUES (p_post_id, p_user_id, p_comment_id, p_type);

    SELECT LAST_INSERT_ID() AS id;

END //

DELIMITER ;
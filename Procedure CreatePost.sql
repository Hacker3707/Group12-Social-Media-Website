DELIMITER //

CREATE PROCEDURE CreatePost(
    IN p_UserID INT,
    IN p_Content TEXT
)
BEGIN
    DECLARE newPostID INT;

    SELECT IFNULL(MAX(PostID),0) + 1 INTO newPostID
    FROM post;

    INSERT INTO post (PostID, UserID, Content)
    VALUES (newPostID, p_UserID, p_Content);
END //

DELIMITER ;
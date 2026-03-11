DELIMITER $$

CREATE PROCEDURE getPostFromFollowedUsers (
    IN p_UserID INT
)
BEGIN
    SELECT Post.PostID, Post.Content, Post.UserID
    FROM Post
    JOIN Follow
        ON Post.UserID = Follow.FollowingID
    WHERE Follow.FollowerID = p_UserID
    ORDER BY Post.PostID DESC;
END $$

DELIMITER ;
DELIMITER $$

CREATE PROCEDURE GetFollowers(
     IN userID_in INT
)
     
BEGIN
     SELECT u.UserID,
            u.Username
     FROM users u
     JOIN follow f ON f.FollowerID = u.UserID
     WHERE f.FollowingID = userID_in;
END $$

DELIMITER ;

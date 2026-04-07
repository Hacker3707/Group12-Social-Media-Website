CREATE PROCEDURE GetFollowing(IN userID_in INT)
BEGIN
    SELECT u.UserID, u.Username
    FROM users u
    JOIN follow f ON f.FollowingID = u.UserID
    WHERE f.FollowerID = userID_in;
END
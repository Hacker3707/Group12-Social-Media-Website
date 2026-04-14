DELIMITER //

CREATE PROCEDURE UnfollowUser(
    IN p_FollowerID INT,
    IN p_FollowingID INT
)
BEGIN
    DELETE FROM Follow
    WHERE FollowerID = p_FollowerID
    AND FollowingID = p_FollowingID;
END //

DELIMITER ;
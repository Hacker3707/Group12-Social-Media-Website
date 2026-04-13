DELIMITER //

CREATE PROCEDURE CountFollowers(IN userID_in INT)
BEGIN
    SELECT COUNT(*) as total 
    FROM follow 
    WHERE FollowingID = userID_in;
END //

DELIMITER ;
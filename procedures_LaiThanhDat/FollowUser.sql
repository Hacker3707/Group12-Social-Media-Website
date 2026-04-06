DELIMITER $$

CREATE PROCEDURE FollowUser(
    IN p_FollowerID INT,
    IN p_FollowingID INT
)
BEGIN
    DECLARE follow_count INT;

    IF p_FollowerID = p_FollowingID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot follow yourself';
    ELSE

        SELECT COUNT(*) INTO follow_count
        FROM Follow
        WHERE FollowerID = p_FollowerID
        AND FollowingID = p_FollowingID;

        IF follow_count > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Already followed';
        ELSE
            INSERT INTO Follow (FollowerID, FollowingID)
            VALUES (p_FollowerID, p_FollowingID);
        END IF;

    END IF;
END $$

DELIMITER ;
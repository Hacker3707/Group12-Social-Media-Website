DELIMITER $$

CREATE PROCEDURE getNotificationsForUser (
    IN p_UserID INT
)
BEGIN
    SELECT NotificationID, Content
    FROM Notification
    WHERE UserID = p_UserID
    ORDER BY NotificationID DESC;
END $$

DELIMITER ;
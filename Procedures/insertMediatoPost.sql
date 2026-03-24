// DELIMITER

CREATE PROCEDURE insertMediaToPost(
    IN p_UserID INT,
    IN p_PostID INT,
    IN p_MediaType ENUM('photo', 'video'),
    IN p_MediaURL VARCHAR(255),
)
BEGIN
    INSERT INTO media(UserID, PostID, MediaType, MediaURL)
    VALUES (p_UserID, p_PostID, p_MediaType, p_MediaURL);
END //

DELIMITER ;
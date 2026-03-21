DELIMITER //

CREATE PROCEDURE createPost(
    IN p_UserID INT,
    IN p_GroupID INT,
    IN p_CategoryID INT,
    IN p_Title VARCHAR(255),
    IN p_Content TEXT
)
BEGIN
    INSERT INTO post(UserID, GroupID, CategoryID, Title, Content)
    VALUES (p_UserID, p_GroupID, p_CategoryID, p_Title, p_Content);
END //

DELIMITER ;
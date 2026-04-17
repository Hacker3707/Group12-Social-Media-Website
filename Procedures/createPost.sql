DELIMITER $$

CREATE PROCEDURE createPost (
    IN p_UserID INT,
    IN p_GroupID INT,
    IN p_CategoryID INT,
    IN p_Title VARCHAR(255),
    IN p_Content TEXT,
    IN p_Price DECIMAL(10,2),
    IN p_ProductCondition VARCHAR(50),
    IN p_Location VARCHAR(255),
    IN p_Brand VARCHAR(255),
    IN p_PostStatus VARCHAR(50)
)
BEGIN
    INSERT INTO post 
    (UserID, GroupID, CategoryID, Title, Content, Price, ProductCondition, Location, Brand, PostStatus)
    VALUES 
    (p_UserID, p_GroupID, p_CategoryID, p_Title, p_Content, p_Price, p_ProductCondition, p_Location, p_Brand, p_PostStatus);
END$$

DELIMITER ;
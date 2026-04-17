DELIMITER $$

CREATE PROCEDURE insert_group (
    IN p_name VARCHAR(255),
    IN p_desc VARCHAR(150),
    IN p_privacy VARCHAR(10),
    IN p_categoryId INT
)
BEGIN
    INSERT INTO `groups` (GroupName, Description, Privacy, CategoryID)
    VALUES (p_name, p_desc, p_privacy, p_categoryId);

    SELECT LAST_INSERT_ID() AS new_id;
END $$

DELIMITER ;
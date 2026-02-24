BEGIN
    DECLARE user_count INT;

    SELECT COUNT(*) INTO user_count
    FROM Users
    WHERE Email = p_Email;

    IF user_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email already exists';
    ELSE
        INSERT INTO Users (Username, Email)
        VALUES (p_Username, p_Email);

        SELECT LAST_INSERT_ID() AS NewUserID;
    END IF;
END
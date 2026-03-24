DELIMITER //

CREATE PROCEDURE removeReaction(
    IN p_ReactionID INT
)
BEGIN

    IF NOT EXISTS (
        SELECT 1 FROM reaction WHERE ReactionID = p_ReactionID
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Reaction does not exist';
    END IF;

    DELETE FROM reaction WHERE ReactionID = p_ReactionID;

END //

DELIMITER;
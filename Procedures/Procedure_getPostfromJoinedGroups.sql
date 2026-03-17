DELIMITER $$

CREATE PROCEDURE getPostfromJoinedGroups(
    IN p_userID INT
)
BEGIN

SELECT 
    p.PostID,
    p.Content,
    p.CreatedAt,

    u.UserID,
    u.Username,

    g.GroupID,
    g.GroupName

FROM group_member gm

JOIN groups g 
    ON gm.GroupID = g.GroupID

JOIN post p 
    ON p.GroupID = g.GroupID

JOIN users u 
    ON p.UserID = u.UserID

WHERE gm.UserID = p_userID

ORDER BY p.CreatedAt DESC;

END $$

DELIMITER ;
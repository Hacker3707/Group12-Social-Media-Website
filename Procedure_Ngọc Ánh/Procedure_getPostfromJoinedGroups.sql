DELIMITER $$

CREATE PROCEDURE getPostfromJoinedGroups(
    IN p_userID INT
)
BEGIN

SELECT 
    p.postID,
    p.title,
    p.content,
    p.createdAt,

    u.userID AS authorID,
    u.username,

    g.groupID,
    g.name AS groupName

FROM GROUPMEMBER gm

JOIN `GROUP` g 
    ON gm.groupID = g.groupID

JOIN POST p 
    ON p.groupID = g.groupID

JOIN USER u 
    ON p.authorID = u.userID

WHERE gm.userID = p_userID

ORDER BY p.createdAt DESC;

END $$

DELIMITER ;
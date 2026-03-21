<?php

class Media
{
    protected $conn;
    protected $table = "media";

    // Khớp đúng bảng media trong SQL:
    // MediaID | PostID | UserID | MediaType
    public $MediaID;
    public $PostID;
    public $UserID;
    public $MediaType;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả media của một bài post
    public function getMediaByPost($PostID)
    {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE PostID = :PostID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":PostID", $PostID);
        $stmt->execute();

        return $stmt;
    }

    // Lấy tất cả media do một user upload
    public function getMediaByUser($UserID)
    {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE UserID = :UserID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":UserID", $UserID);
        $stmt->execute();

        return $stmt;
    }

    // Thêm media mới
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (PostID, UserID, MediaType)
                  VALUES (:PostID, :UserID, :MediaType)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":PostID",    $this->PostID);
        $stmt->bindParam(":UserID",    $this->UserID);
        $stmt->bindParam(":MediaType", $this->MediaType);

        if ($stmt->execute()) {
            $this->MediaID = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Xoá media theo MediaID
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . "
                  WHERE MediaID = :MediaID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":MediaID", $this->MediaID);

        return $stmt->execute();
    }
}

?>

<?php

class Media
{
    protected $conn;
    protected $table = "media";

    // Khớp đúng 7 cột bảng media trên server:
    public $MediaID;
    public $UserID;
    public $MediaType; // enum('photo','video')
    public $FilePath;
    public $CreatedAt;
    public $CommentID;
    public $PostID;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function __toString()
    {
        return "Media(MediaID=$this->MediaID, UserID=$this->UserID, " .
               "MediaType=$this->MediaType, FilePath=$this->FilePath, " .
               "CreatedAt=$this->CreatedAt, CommentID=$this->CommentID, " .
               "PostID=$this->PostID)";
    }
}

?>
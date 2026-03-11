<?php

class Media
{
    protected $conn;
    protected $table = "media";

    public $id;
    public $post_id;
    public $media_url;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getMediaByPost($post_id)
    {
        $query = "SELECT * FROM media WHERE post_id = :post_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":post_id", $post_id);
        $stmt->execute();

        return $stmt;
    }
}

?>

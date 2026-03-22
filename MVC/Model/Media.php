<?php

class Media
{
    protected $conn;
    protected $table = "media";

  public $media_id;
  public $user_id;
  public $file_path;
  public $created_at;
  public $media_type;

    public function __construct($db)
    {
        $this->conn = $db;
    }

   public function getMediaByPost($post_id)
{
    $query = "SELECT m.* 
              FROM media m
              JOIN post_media pm ON m.MediaID = pm.MediaID
              WHERE pm.PostID = :post_id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->execute();

    return $stmt;
}
}

?>

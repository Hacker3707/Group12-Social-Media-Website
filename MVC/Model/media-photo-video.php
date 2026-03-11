<?php

class MediaPhotoVideo
{
    private $conn;
    private $table = "media";

    public $id;
    public $post_id;
    public $media_type;
    public $media_url;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createMedia()
    {
        $query = "INSERT INTO media(post_id, media_type, media_url)
                  VALUES(:post_id, :media_type, :media_url)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":post_id", $this->post_id);
        $stmt->bindParam(":media_type", $this->media_type);
        $stmt->bindParam(":media_url", $this->media_url);

        return $stmt->execute();
    }

}
?>

<?php
require_once "Media.php";

class Photo extends Media
{
    public $resolution;
    public $size;

    public function createPhoto()
    {
        $query = "INSERT INTO media(post_id, media_url, created_at)
                  VALUES(:post_id, :media_url, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":post_id", $this->post_id);
        $stmt->bindParam(":media_url", $this->media_url);

        return $stmt->execute();
    }
}

?>

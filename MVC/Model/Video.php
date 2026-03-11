<?php

require_once "Media.php";

class Video extends Media
{
    public $duration;
    public $format;

    public function createVideo()
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

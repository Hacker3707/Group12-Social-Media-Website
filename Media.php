<?php

class Media
{
    protected $media_id;
    protected $post_id;
    protected $media_url;
    protected $created_at;

    public function __construct($media_id, $post_id, $media_url, $created_at)
    {
        $this->media_id = $media_id;
        $this->post_id = $post_id;
        $this->media_url = $media_url;
        $this->created_at = $created_at;
    }

    public function getMediaUrl()
    {
        return $this->media_url;
    }
}

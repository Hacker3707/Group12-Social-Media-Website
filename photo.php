<?php

require_once "Media.php";

class Photo extends Media
{
    private $resolution;

    public function __construct($media_id,$post_id,$media_url,$created_at,$resolution)
    {
        parent::__construct($media_id,$post_id,$media_url,$created_at);
        $this->resolution = $resolution;
    }

    public function getResolution()
    {
        return $this->resolution;
    }
}

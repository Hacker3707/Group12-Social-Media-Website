<?php
class Notification{
    private $notificationId;
    private $userId;
    private $content;
    private $createdAt;
    private $isRead;

    public function getNotificationId(){
        return $this->notificationId;
    }

    public function setNotificationId($notificationId){
        $this->notificationId = $notificationId;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function getContent(){
        return $this->content;
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    public function getIsRead(){
        return $this->isRead;
    }

    public function setIsRead($isRead){
        $this->isRead = $isRead;
    }

    public function __construct(){
        $this->notificationId = "";
        $this->userId = "";
        $this->content = "";
        $this->createdAt = date("Y-m-d H:i:s");
        $this->isRead = false;
    }

    public function __toString(){
        return "Notification(id=$this->notificationId, userId=$this->userId, content=$this->content, createdAt=$this->createdAt, isRead=$this->isRead)";
    }
}
?>
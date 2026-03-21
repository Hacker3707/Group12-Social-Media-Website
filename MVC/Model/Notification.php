<?php
class Notification {
    private $NotificationID;
    private $UserID;
    private $SenderID;
    private $Content;
    private $NotiType;
    private $CreatedAt;
    private $IsRead;

    // ===== Constructor =====
    public function __construct(
        $UserID = null,
        $Content = "",
        $NotiType = null,
        $SenderID = null,
        $IsRead = false,
        $CreatedAt = null,
        $NotificationID = null
    ){
        $this->NotificationID = $NotificationID;
        $this->UserID = $UserID;
        $this->SenderID = $SenderID;
        $this->Content = $Content;
        $this->NotiType = $NotiType;
        $this->IsRead = $IsRead;
        $this->CreatedAt = $CreatedAt;
    }

    // ===== Getter & Setter =====

    public function getNotificationID(){
        return $this->NotificationID;
    }

    public function setNotificationID($NotificationID){
        $this->NotificationID = $NotificationID;
    }

    public function getUserID(){
        return $this->UserID;
    }

    public function setUserID($UserID){
        $this->UserID = $UserID;
    }

    public function getSenderID(){
        return $this->SenderID;
    }

    public function setSenderID($SenderID){
        $this->SenderID = $SenderID;
    }

    public function getContent(){
        return $this->Content;
    }

    public function setContent($Content){
        $this->Content = $Content;
    }

    public function getNotiType(){
        return $this->NotiType;
    }

    public function setNotiType($NotiType){
        $this->NotiType = $NotiType;
    }

    public function getCreatedAt(){
        return $this->CreatedAt;
    }

    public function setCreatedAt($CreatedAt){
        $this->CreatedAt = $CreatedAt;
    }

    public function getIsRead(){
        return $this->IsRead;
    }

    public function setIsRead($IsRead){
        $this->IsRead = $IsRead;
    }

    // ===== Business Logic =====
    public function markAsRead(){
        $this->IsRead = true;
    }

    public function isReadStatus(){
        return $this->IsRead;
    }

    // ===== To String =====
    public function __toString(){
        return "Notification(
            id={$this->NotificationID},
            userId={$this->UserID},
            senderId={$this->SenderID},
            content={$this->Content},
            type={$this->NotiType},
            createdAt={$this->CreatedAt},
            isRead={$this->IsRead}
        )";
    }
}
?>
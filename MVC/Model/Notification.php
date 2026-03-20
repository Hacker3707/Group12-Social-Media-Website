<?php
class Notification {
    private $notificationId;
    private $userId;
    private $senderId;
    private $content;
    private $createdAt;
    private $isRead;

    // ===== Constructor =====
    public function __construct(
        $userId = null,
        $content = "",
        $senderId = null,
        $isRead = false,
        $createdAt = null,
        $notificationId = null
    ){
        $this->notificationId = $notificationId;
        $this->userId = $userId;
        $this->senderId = $senderId;
        $this->content = $content;
        $this->isRead = $isRead;
        $this->createdAt = $createdAt;
    }

    // ===== Getter & Setter =====
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

    public function getSenderId(){
        return $this->senderId;
    }

    public function setSenderId($senderId){
        $this->senderId = $senderId;
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

    

    // Đánh dấu đã đọc
    public function markAsRead(){
        $this->isRead = true;
    }

    // Kiểm tra đã đọc chưa
    public function isRead(){
        return $this->isRead;
    }

    // ===== To String =====
    public function __toString(){
        return "Notification(
            id={$this->notificationId},
            userId={$this->userId},
            senderId={$this->senderId},
            content={$this->content},
            createdAt={$this->createdAt},
            isRead={$this->isRead}
        )";
    }
}
?>
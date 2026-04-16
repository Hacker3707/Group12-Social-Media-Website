<?php
class Chat {
    private ?int $conversationId;
    private ?int $messageId;
    private ?int $user1Id;
    private ?int $user2Id;
    private ?int $senderId;
    private ?string $content;
    private ?string $imagePath;
    private ?string $reaction;
    private ?int $isRead;
    private ?string $createdAt;

    private ?int $otherUserId;
    private ?string $username;
    private ?string $avatarFp;
    private ?string $otherUsername;
    private ?string $otherAvatar;
    private ?string $lastMessage;
    private ?string $lastImage;
    private ?string $lastMessageAt;
    private ?int $unreadCount;

    public function __construct(
        ?int $conversationId = null,
        ?int $messageId = null,
        ?int $user1Id = null,
        ?int $user2Id = null,
        ?int $senderId = null,
        ?string $content = null,
        ?string $imagePath = null,
        ?string $reaction = null,
        ?int $isRead = null,
        ?string $createdAt = null,
        ?int $otherUserId = null,
        ?string $username = null,
        ?string $avatarFp = null,
        ?string $otherUsername = null,
        ?string $otherAvatar = null,
        ?string $lastMessage = null,
        ?string $lastImage = null,
        ?string $lastMessageAt = null,
        ?int $unreadCount = null
    ) {
        $this->conversationId = $conversationId;
        $this->messageId = $messageId;
        $this->user1Id = $user1Id;
        $this->user2Id = $user2Id;
        $this->senderId = $senderId;
        $this->content = $content;
        $this->imagePath = $imagePath;
        $this->reaction = $reaction;
        $this->isRead = $isRead;
        $this->createdAt = $createdAt;
        $this->otherUserId = $otherUserId;
        $this->username = $username;
        $this->avatarFp = $avatarFp;
        $this->otherUsername = $otherUsername;
        $this->otherAvatar = $otherAvatar;
        $this->lastMessage = $lastMessage;
        $this->lastImage = $lastImage;
        $this->lastMessageAt = $lastMessageAt;
        $this->unreadCount = $unreadCount;
    }

    public function getConversationId(): ?int { return $this->conversationId; }
    public function setConversationId(?int $conversationId): void { $this->conversationId = $conversationId; }

    public function getMessageId(): ?int { return $this->messageId; }
    public function setMessageId(?int $messageId): void { $this->messageId = $messageId; }

    public function getUser1Id(): ?int { return $this->user1Id; }
    public function setUser1Id(?int $user1Id): void { $this->user1Id = $user1Id; }

    public function getUser2Id(): ?int { return $this->user2Id; }
    public function setUser2Id(?int $user2Id): void { $this->user2Id = $user2Id; }

    public function getSenderId(): ?int { return $this->senderId; }
    public function setSenderId(?int $senderId): void { $this->senderId = $senderId; }

    public function getContent(): ?string { return $this->content; }
    public function setContent(?string $content): void { $this->content = $content; }

    public function getImagePath(): ?string { return $this->imagePath; }
    public function setImagePath(?string $imagePath): void { $this->imagePath = $imagePath; }

    public function getReaction(): ?string { return $this->reaction; }
    public function setReaction(?string $reaction): void { $this->reaction = $reaction; }

    public function getIsRead(): ?int { return $this->isRead; }
    public function setIsRead(?int $isRead): void { $this->isRead = $isRead; }

    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }

    public function getOtherUserId(): ?int { return $this->otherUserId; }
    public function setOtherUserId(?int $otherUserId): void { $this->otherUserId = $otherUserId; }

    public function getUsername(): ?string { return $this->username; }
    public function setUsername(?string $username): void { $this->username = $username; }

    public function getAvatarFp(): ?string { return $this->avatarFp; }
    public function setAvatarFp(?string $avatarFp): void { $this->avatarFp = $avatarFp; }

    public function getOtherUsername(): ?string { return $this->otherUsername; }
    public function setOtherUsername(?string $otherUsername): void { $this->otherUsername = $otherUsername; }

    public function getOtherAvatar(): ?string { return $this->otherAvatar; }
    public function setOtherAvatar(?string $otherAvatar): void { $this->otherAvatar = $otherAvatar; }

    public function getLastMessage(): ?string { return $this->lastMessage; }
    public function setLastMessage(?string $lastMessage): void { $this->lastMessage = $lastMessage; }

    public function getLastImage(): ?string { return $this->lastImage; }
    public function setLastImage(?string $lastImage): void { $this->lastImage = $lastImage; }

    public function getLastMessageAt(): ?string { return $this->lastMessageAt; }
    public function setLastMessageAt(?string $lastMessageAt): void { $this->lastMessageAt = $lastMessageAt; }

    public function getUnreadCount(): ?int { return $this->unreadCount; }
    public function setUnreadCount(?int $unreadCount): void { $this->unreadCount = $unreadCount; }

    public function __toString(): string {
        return 'Chat(ConversationID=' . ($this->conversationId ?? 'null') . ', MessageID=' . ($this->messageId ?? 'null') . ')';
    }
}
?>
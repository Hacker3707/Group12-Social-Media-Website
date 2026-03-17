<?php
class User extends Model
{
    private ?int $userId = null;
    private string $username;
    private string $email;

    public function __construct(?string $username = null, ?string $email = null)
    {
        parent::__construct();
        $this->username = $username ?? '';
        $this->email = $email ?? '';
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY UserID DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE UserID = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE Email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (Username, Email) VALUES (:username, :email)');
        return $stmt->execute([
            'username' => $this->username,
            'email' => $this->email,
        ]);
    }

    public function followers(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT u.* FROM users u
             INNER JOIN follow f ON f.FollowerID = u.UserID
             WHERE f.FollowingID = :userId'
        );
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }
}
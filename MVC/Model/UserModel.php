<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/User.php";
include_once "Entity/Admin.php";
include_once "Entity/Member.php";

class UserModel extends AppModel {

    // ================= NHÀ MÁY ĐÚC ĐỐI TƯỢNG (FACTORY) =================
    private function mapToUserObject($row) {
        if ($row['UserRole'] === 'admin') {
            return new Admin(
                $row['UserID'], $row['Username'], $row['Email'], $row['AccountPassword'],
                $row['AccountStatus'], $row['AvatarFP'], $row['Phone'], $row['Bio']
            );
        } else {
            return new Member(
                $row['UserID'], $row['Username'], $row['Email'], $row['AccountPassword'],
                $row['AccountStatus'], $row['AvatarFP'], $row['Phone'], $row['Bio']
            );
        }
    }

    // ================= XÁC THỰC ĐĂNG NHẬP =================
    public function checkLogin($username, $password) {
        $username = mysqli_real_escape_string($this->link, $username);
        $password = mysqli_real_escape_string($this->link, $password);

        // Chỉ cho phép tài khoản 'active' đăng nhập (chặn bị ban hoặc deleted)
        $sql = "SELECT * FROM users WHERE Username = '$username' AND AccountPassword = '$password' AND AccountStatus = 'active'";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row; 
        }
        return null; 
    }

    // ================= CREATE =================
    public function insert(User $user, $password) {
        $username = mysqli_real_escape_string($this->link, $user->getUsername());
        $email = mysqli_real_escape_string($this->link, $user->getEmail());
        $password = mysqli_real_escape_string($this->link, $password);
        $role = $user->getUserRole();

        $sql = "INSERT INTO users (Username, Email, AccountPassword, UserRole) 
                VALUES ('$username', '$email', '$password', '$role')";
        
        return $this->execute($sql);
    }

    // ================= LIST (Dành cho Admin) =================
    public function getAll() {
        // Ẩn những người đã bị xóa mềm ('deleted') khỏi danh sách hiển thị
        $sql = "SELECT * FROM users WHERE AccountStatus != 'deleted' ORDER BY UserID DESC";
        $result = $this->query($sql);
        $list = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $this->mapToUserObject($row);
            }
        }
        return $list;
    }

    // ================= SEARCH =================
    public function searchUsers($keyword) {
        $keyword = mysqli_real_escape_string($this->link, $keyword);
        $sql = "SELECT * FROM users WHERE AccountStatus != 'deleted' AND (Username LIKE '%$keyword%' OR Email LIKE '%$keyword%') ORDER BY UserID DESC";
        $result = $this->query($sql);
        $list = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $this->mapToUserObject($row);
            }
        }
        return $list;
    }

    // ================= GET BY ID =================
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM users WHERE UserID = $id";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row; // Trả về mảng cho Profile/Edit dễ dùng
        }
        return null;
    }

    // ================= UPDATE =================
    public function update($userId, $username, $email, $bio, $phone) {
        $userId = (int)$userId;
        $username = mysqli_real_escape_string($this->link, $username);
        $email = mysqli_real_escape_string($this->link, $email);
        $bio = mysqli_real_escape_string($this->link, $bio);
        $phone = mysqli_real_escape_string($this->link, $phone);

        $sql = "UPDATE users 
                SET Username = '$username', 
                    Email = '$email', 
                    Bio = '$bio', 
                    Phone = '$phone'
                WHERE UserID = $userId";

        return $this->execute($sql);
    }

    // ================= DELETE (SOFT DELETE) =================
    public function delete($userId) {
        $userId = (int)$userId;
        $sql = "UPDATE users SET AccountStatus = 'deleted' WHERE UserID = $userId";
        return $this->execute($sql);
    }

    // ================= BAN / UNBAN (MỚI THÊM) =================
    public function banUser($userId) {
        $userId = (int)$userId;
        $sql = "UPDATE users SET AccountStatus = 'banned' WHERE UserID = $userId";
        return $this->execute($sql);
    }

    public function unbanUser($userId) {
        $userId = (int)$userId;
        $sql = "UPDATE users SET AccountStatus = 'active' WHERE UserID = $userId";
        return $this->execute($sql);
    }

    // ================= CHECK EXISTS =================
    public function existsByUsername($username, $excludeId = null) {
        $username = mysqli_real_escape_string($this->link, $username);
        $sql = "SELECT * FROM users WHERE Username = '$username'";
        if ($excludeId !== null) {
            $excludeId = (int)$excludeId;
            $sql .= " AND UserID != $excludeId";
        }
        $result = $this->query($sql);
        return mysqli_num_rows($result) > 0;
    }

    // ================= QUÊN MẬT KHẨU =================
    public function verifyUserForReset($username, $email) {
        $username = mysqli_real_escape_string($this->link, $username);
        $email = mysqli_real_escape_string($this->link, $email);
        
        $sql = "SELECT * FROM users WHERE Username = '$username' AND Email = '$email' AND AccountStatus != 'deleted'";
        $result = $this->query($sql);
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
        return null;
    }

    public function updatePassword($userId, $newPassword) {
        $userId = (int)$userId;
        $newPassword = mysqli_real_escape_string($this->link, $newPassword);
        
        $sql = "UPDATE users SET AccountPassword = '$newPassword' WHERE UserID = $userId";
        return $this->execute($sql);
    }
}
?>

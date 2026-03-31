<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/User.php";

class UserModel extends AppModel {

    // ================= XÁC THỰC ĐĂNG NHẬP =================
    public function checkLogin($username, $password) {
        $username = mysqli_real_escape_string($this->link, $username);
        $password = mysqli_real_escape_string($this->link, $password);

        // Kiểm tra xem có user nào khớp và trạng thái tài khoản đang 'active' không
        $sql = "SELECT * FROM users WHERE Username = '$username' AND AccountPassword = '$password' AND AccountStatus = 'active'";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row; // Trả về thông tin user nếu đúng
        }
        return null; // Trả về null nếu sai
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

    // ================= LIST =================
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY UserID DESC";
        $result = $this->query($sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new User(
                $row['UserID'],
                $row['Username'],
                $row['Email'],
                $row['UserRole'],
                $row['AccountStatus']
            );
        }
        return $list;
    }

    // ================= GET BY ID =================
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM users WHERE UserID = $id";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row; // Trả về mảng để Controller lấy Bio, Phone dễ dàng
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

    // ================= DELETE (SOFT) =================
    public function delete($userId) {
        $userId = (int)$userId;
        // Xóa mềm để giữ toàn vẹn dữ liệu mạng xã hội
        $sql = "UPDATE users SET AccountStatus = 'deleted' WHERE UserID = $userId";
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
}
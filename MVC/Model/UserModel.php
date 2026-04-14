<?php
include_once __DIR__ . "/AppModel.php";
include_once __DIR__ . "/../../Entity/User.php";
include_once __DIR__ . "/../../Entity/Admin.php";
include_once __DIR__ . "/../../Entity/Member.php";

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
    public function insert(User $user, $password, $supabaseId = null, $provider = 'LOCAL') {
        $username = mysqli_real_escape_string($this->link, $user->getUsername());
        $email = mysqli_real_escape_string($this->link, $user->getEmail());
        $password = mysqli_real_escape_string($this->link, $password);
        $role = $user->getUserRole();
        $supabaseValue = $supabaseId !== null ? "'" . mysqli_real_escape_string($this->link, $supabaseId) . "'" : 'NULL';
        $providerValue = mysqli_real_escape_string($this->link, $provider);

        $sql = "INSERT INTO users (Username, Email, AccountPassword, UserRole, supabase_id, o_provider) 
                VALUES ('$username', '$email', '$password', '$role', $supabaseValue, '$providerValue')";
        
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
    public function searchUsers($keyword, $includeEmail = false) {
        $keyword = mysqli_real_escape_string($this->link, $keyword);
        $condition = "Username LIKE '%$keyword%'";

        if ($includeEmail) {
            $condition .= " OR Email LIKE '%$keyword%'";
        }

        $sql = "SELECT * FROM users WHERE AccountStatus != 'deleted' AND ($condition) ORDER BY UserID DESC";
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

    public function getByEmail($email) {
        $email = mysqli_real_escape_string($this->link, $email);
        $sql = "SELECT * FROM users WHERE Email = '$email' LIMIT 1";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
        return null;
    }

    public function getBySupabaseId($supabaseId) {
        $supabaseId = mysqli_real_escape_string($this->link, $supabaseId);
        $sql = "SELECT * FROM users WHERE supabase_id = '$supabaseId' LIMIT 1";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
        return null;
    }

    // ================= UPDATE =================
    // Thêm tham số $avatarFp = null vào cuối
    public function update($userId, $username, $email, $bio, $phone, $avatarFp = null, $supabaseId = null, $provider = null) {
        $userId = (int)$userId;
        $username = mysqli_real_escape_string($this->link, $username);
        $email = mysqli_real_escape_string($this->link, $email);
        $bio = mysqli_real_escape_string($this->link, $bio);
        $phone = mysqli_real_escape_string($this->link, $phone);

        // Câu lệnh update cơ bản
        $sql = "UPDATE users 
                SET Username = '$username', 
                    Email = '$email', 
                    Bio = '$bio', 
                    Phone = '$phone'";

        // Nếu có đường dẫn ảnh mới truyền vào thì mới update cột AvatarFP
        if ($avatarFp !== null) {
            $avatarFp = mysqli_real_escape_string($this->link, $avatarFp);
            $sql .= ", AvatarFP = '$avatarFp'";
        }

        if ($supabaseId !== null) {
            $supabaseId = mysqli_real_escape_string($this->link, $supabaseId);
            $sql .= ", supabase_id = '$supabaseId'";
        }

        if ($provider !== null) {
            $provider = mysqli_real_escape_string($this->link, $provider);
            $sql .= ", o_provider = '$provider'";
        }

        $sql .= " WHERE UserID = $userId";

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

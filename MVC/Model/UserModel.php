<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/User.php";

class UserModel extends AppModel {

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

    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM users WHERE UserID = $id";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return new User(
                $row['UserID'],
                $row['Username'],
                $row['Email'],
                $row['UserRole'],
                $row['AccountStatus']
            );
        }
        return null;
    }

    public function insert(User $user, $password) {
        $username = mysqli_real_escape_string($this->link, $user->getUsername());
        $email = mysqli_real_escape_string($this->link, $user->getEmail());
        $password = mysqli_real_escape_string($this->link, $password);
        $role = $user->getUserRole();

        $sql = "INSERT INTO users (Username, Email, AccountPassword, UserRole) 
                VALUES ('$username', '$email', '$password', '$role')";
        
        return $this->execute($sql);
    }
}
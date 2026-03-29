<?php
include_once "MVC/Model/UserModel.php";

class UserController {
    public function index() {
        $userModel = new UserModel();
        $users = $userModel->getAll(); // Trả về mảng các đối tượng User
        
        // Ví dụ: In ra tên user đầu tiên
        if (!empty($users)) {
            echo $users[0]->getUsername();
        }
    }
}
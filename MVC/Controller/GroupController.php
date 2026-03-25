<?php
include_once "MVC/Model/GroupModel.php";
include_once "MVC/Model/GroupMemberModel.php";

class GroupController {
    public function show($groupId) {
        $groupMemberModel = new GroupMemberModel();
        $members = $groupMemberModel->getMembers($groupId); // Trả về mảng đối tượng GroupMember
        
        return $members;
    }
}
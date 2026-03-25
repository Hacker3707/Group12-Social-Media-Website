<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Group.php";

class GroupModel extends AppModel {

    public function getAll() {
        $sql = "SELECT * FROM groups ORDER BY GroupID DESC";
        $result = $this->query($sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $group = new Group(
                $row['GroupID'],
                $row['GroupName'],
                $row['Privacy']
            );
            $group->setDescription($row['Description']);
            $list[] = $group;
        }
        return $list;
    }

    public function insert(Group $group) {
        $name = mysqli_real_escape_string($this->link, $group->getGroupName());
        $desc = mysqli_real_escape_string($this->link, $group->getDescription());
        $privacy = $group->getPrivacy();

        $sql = "INSERT INTO groups (GroupName, Description, Privacy) 
                VALUES ('$name', '$desc', '$privacy')";
        
        return $this->execute($sql);
    }
}
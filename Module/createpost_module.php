
<?php

include_once "../MVC/Controller/PostControl.php";

$userId = 1;
$groupId = null;
$categoryId = null;

$title = $_POST['title'];
$content = $_POST['content'];

$mediaList = [];

if(isset($_FILES['media']) && $_FILES['media']['error'] == 0){

    $uploadPath = "../uploads/" . basename($_FILES['media']['name']);

    move_uploaded_file($_FILES['media']['tmp_name'], $uploadPath);

    $mediaList[] = $uploadPath;
}

$postControl = new PostControl();

$result = $postControl->createPost(
    $userId,
    $groupId,
    $categoryId,
    $title,
    $content,
    $mediaList
);

if($result){
    echo "success";
} else{
    echo "fail";
}

?>
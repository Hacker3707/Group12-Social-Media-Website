<?php

function uploadFileTo($uploaddir, &$oldfilename){

    // 🔥 đảm bảo thư mục tồn tại
    if (!is_dir($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }

    if(!isset($_FILES['uploadfile']) || $_FILES['uploadfile']['error'] !== 0){
        return false;
    }

    $filetam = $_FILES['uploadfile']['tmp_name'];
    $oldfilename = basename($_FILES['uploadfile']['name']);

    // 🔥 tránh trùng tên
    $targetPath = $uploaddir . $oldfilename;

    if (file_exists($targetPath)) {
        $ext = pathinfo($oldfilename, PATHINFO_EXTENSION);
        $name = pathinfo($oldfilename, PATHINFO_FILENAME);

        $oldfilename = $name . "_" . uniqid() . "." . $ext;
        $targetPath = $uploaddir . $oldfilename;
    }

    return move_uploaded_file($filetam, $targetPath);
}


function uploadAndRenameFile($uploaddir, $newfilename){

    // 🔥 đảm bảo thư mục tồn tại
    if (!is_dir($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }

    if(!isset($_FILES['uploadfile']) || $_FILES['uploadfile']['error'] !== 0){
        return false;
    }

    $filetam = $_FILES['uploadfile']['tmp_name'];

    // 🔥 đảm bảo tên file an toàn
    $newfilename = basename($newfilename);

    $targetPath = $uploaddir . $newfilename;

    return move_uploaded_file($filetam, $targetPath);
}

?>
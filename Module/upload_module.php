<?php

function uploadFileTo($uploaddir, &$oldfilename){
    $filetam = $_FILES['uploadfile']['tmp_name'];
    $oldfilename = $_FILES['uploadfile']['name'];

    $checkdup = $uploaddir.$oldfilename;

    if (file_exists($checkdup)) {
        header("Location: index.php?msg=duplicate");
        exit();
    }

    return move_uploaded_file($filetam, $uploaddir.$oldfilename);
}

function uploadAndRenameFile($uploaddir, $newfilename){
    $filetam = $_FILES['uploadfile']['tmp_name'];
    return move_uploaded_file($filetam, $uploaddir.$newfilename);
}

?>
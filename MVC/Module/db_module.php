<?php

function taoKetNoi(&$link){
    $link = mysqli_connect("localhost", "root", "", "qlbaiviet");

    if(!$link){
        die("Không thể kết nối CSDL: ".mysqli_connect_error());
    }

    mysqli_set_charset($link, "utf8");
}

function chayTruyVanTraVeDL($link, $sql){
    $result = mysqli_query($link, $sql);

    if(!$result){
        die("Lỗi truy vấn: ".mysqli_error($link));
    }

    return $result;
}

function chayTruyVanKhongTraVeDL($link, $sql){
    $result = mysqli_query($link, $sql);

    if(!$result){
        die("Lỗi truy vấn: ".mysqli_error($link));
    }

    return $result;
}

function giaiPhongBoNho($link, $result){
    
    if($result instanceof mysqli_result){
        mysqli_free_result($result);
    }

    if($link){
        mysqli_close($link);
    }
}

?>
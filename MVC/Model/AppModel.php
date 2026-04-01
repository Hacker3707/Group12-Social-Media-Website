<?php

abstract class AppModel {
    protected $link = null;

    public function __construct() {
        if ($this->link === null) {
            $this -> taoKetNoi();
        }
    }

    public function taoKetNoi() {
        $this->link = mysqli_connect("localhost", "root", "", "passo");

        if(!$this->link){
            die("Không thể kết nối CSDL: ".mysqli_connect_error());
        }

        mysqli_set_charset($this->link, "utf8");
    }

    // SELECT
    protected function query($sql) {
        $result = mysqli_query($this->link, $sql);

        if(!$result){
            die("Lỗi truy vấn: ".mysqli_error($this->link));
        }

        return $result;
    }

    // INSERT UPDATE DELETE
    protected function execute($sql) {
        $result = mysqli_query($this->link, $sql);

        if(!$result){
            die("Lỗi truy vấn: ".mysqli_error($this->link));
        }

        return true;
    }

    // Lấy ID vừa insert
    protected function getLastInsertId() {
        return mysqli_insert_id($this->link);
    }

    // Destructor
    public function __destruct() {
        if($this->link){
            mysqli_close($this->link);
            $this->link = null;
        }
    }

    // Giải phóng bộ nhớ sau khi sử dụng kết quả truy vấn
    public function giaiPhongBoNho($result) {
        if($result instanceof mysqli_result){
        mysqli_free_result($result);
        }
    }
}
?>
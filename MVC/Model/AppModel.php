<?php
// Include module database của bạn
include_once "Module/db_module.php";

abstract class AppModel {
    protected $link = null;

    public function __construct() {
        // Tự động kết nối khi Model được khởi tạo
        if ($this->link === null) {
            taoKetNoi($this->link);
        }
    }

    /**
     * Chạy truy vấn trả về dữ liệu (SELECT)
     */
    protected function query($sql) {
        return chayTruyVanTraVeDL($this->link, $sql);
    }

    /**
     * Chạy truy vấn không trả về dữ liệu (INSERT, UPDATE, DELETE)
     */
    protected function execute($sql) {
        return chayTruyVanKhongTraVeDL($this->link, $sql);
    }

    /**
     * Lấy ID vừa mới Insert (Rất cần cho PostID)
     */
    protected function getLastInsertId() {
        return mysqli_insert_id($this->link);
    }

    /**
     * Tự động đóng kết nối khi đối tượng bị hủy (Giải phóng bộ nhớ)
     */
    public function __destruct() {
        if ($this->link !== null) {
            giaiPhongKetNoi($this->link);
        }
    }
}
?>
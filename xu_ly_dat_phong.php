<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_phong = intval($_POST['id_phong']);
    $gia_phong = floatval($_POST['gia_phong']);
    $ho_ten = trim($_POST['ho_ten']);
    $so_dien_thoai = trim($_POST['so_dien_thoai']);
    $ngay_den = $_POST['ngay_den'];
    $ngay_di = $_POST['ngay_di'];

    if (!empty($ho_ten) && !empty($so_dien_thoai) && !empty($ngay_den) && !empty($ngay_di)) {
        try {
            // 1. Tính toán tổng tiền dựa trên số ngày ở
            $date1 = new DateTime($ngay_den);
            $date2 = new DateTime($ngay_di);
            $interval = $date1->diff($date2);
            $so_ngay = $interval->days;
            if ($so_ngay <= 0) $so_ngay = 1; // Ở tối thiểu 1 ngày
            
            $tong_tien = $so_ngay * $gia_phong;

            // Bắt đầu một Transaction để đảm bảo tính toàn vẹn dữ liệu
            $pdo->beginTransaction();

            // 2. Thêm khách hàng vào bảng `khach_hang`
            $sql_kh = "INSERT INTO khach_hang (ho_ten, so_dien_thoai) VALUES (:ho_ten, :sdt)";
            $stmt_kh = $pdo->prepare($sql_kh);
            $stmt_kh->execute([':ho_ten' => $ho_ten, ':sdt' => $so_dien_thoai]);
            $id_khach_hang = $pdo->lastInsertId(); // Lấy ID khách hàng vừa sinh ra

            // 3. Thêm đơn đặt phòng vào bảng `dat_phong`
            $sql_dp = "INSERT INTO dat_phong (id_khach_hang, id_phong, ngay_den, ngay_di, tong_tien, trang_thai) 
                       VALUES (:id_kh, :id_phong, :ngay_den, :ngay_di, :tong_tien, 'cho_xac_nhan')";
            $stmt_dp = $pdo->prepare($sql_dp);
            $stmt_dp->execute([
                ':id_kh' => $id_khach_hang,
                ':id_phong' => $id_phong,
                ':ngay_den' => $ngay_den,
                ':ngay_di' => $ngay_di,
                ':tong_tien' => $tong_tien
            ]);

            // 4. Chuyển trạng thái phòng sang 'cho_xac_nhan' hoặc 'co_khach' để người khác không đặt trùng
            $sql_room = "UPDATE phong SET trang_thai = 'cho_xac_nhan' WHERE id = :id_phong";
            $stmt_room = $pdo->prepare($sql_room);
            $stmt_room->execute([':id_phong' => $id_phong]);

            // Hoàn tất lưu dữ liệu
            $pdo->commit();

            echo "<script>
                    alert('Đặt phòng thành công! Tổng số tiền dự kiến: " . number_format($tong_tien, 0, ',', '.') . " đ. Nhân viên sẽ liên hệ lại.');
                    window.location.href = 'index.php';
                  </script>";
            exit();

        } catch (Exception $e) {
            $pdo->rollBack(); // Hoàn tác dữ liệu nếu có lỗi xảy ra
            die("Lỗi hệ thống: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); window.history.back();</script>";
    }
}
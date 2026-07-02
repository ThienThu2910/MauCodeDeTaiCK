<?php
require_once '../admin/auth.php';
require_once '../config/db.php';

$error = '';
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';

if (isset($_POST['save'])) {
    $so_phong = isset($_POST['so_phong']) ? trim($_POST['so_phong']) : '';
    $loai_phong = isset($_POST['loai_phong']) ? trim($_POST['loai_phong']) : '';
    $gia_phong = isset($_POST['gia_phong']) ? trim($_POST['gia_phong']) : '';
    $trang_thai = isset($_POST['trang_thai']) ? $_POST['trang_thai'] : 'trong';

    $allowed_status = ['trong', 'cho_xac_nhan'];

    if (empty($so_phong) || empty($loai_phong) || empty($gia_phong)) {
        $error = "Vui lòng nhập đầy đủ các trường bắt buộc!";
    } elseif (!is_numeric($gia_phong) || $gia_phong <= 0) {
        $error = "Giá phòng phải là số lớn hơn 0!";
    } elseif (!in_array($trang_thai, $allowed_status)) {
        $error = "Trạng thái phòng không hợp lệ!";
    } else {
        $chk = $pdo->prepare("SELECT COUNT(*) FROM phong WHERE so_phong = ?");
        $chk->execute([$so_phong]);

        if ($chk->fetchColumn() > 0) {
            $error = "Số phòng này đã tồn tại, vui lòng chọn số khác!";
        }
    }

    $hinh_anh = null;

    if (empty($error) && isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = $_FILES['hinh_anh']['name'];
        $file_tmp = $_FILES['hinh_anh']['tmp_name'];
        $file_size = $_FILES['hinh_anh']['size'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size = 2 * 1024 * 1024;

        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Định dạng ảnh không hợp lệ! Chỉ nhận JPG, JPEG, PNG, WEBP.";
        } elseif ($file_size > $max_size) {
            $error = "Ảnh không được vượt quá 2MB!";
        } else {
            $hinh_anh = "room_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;

            if (!move_uploaded_file($file_tmp, $target_dir . $hinh_anh)) {
                $error = "Upload ảnh thất bại, vui lòng thử lại!";
                $hinh_anh = null;
            }
        }
    }

    if (empty($error)) {
        $sql = "INSERT INTO phong (so_phong, loai_phong, gia_phong, trang_thai, hinh_anh) 
                VALUES (:so_phong, :loai_phong, :gia_phong, :trang_thai, :hinh_anh)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':so_phong' => $so_phong,
            ':loai_phong' => $loai_phong,
            ':gia_phong' => $gia_phong,
            ':trang_thai' => $trang_thai,
            ':hinh_anh' => $hinh_anh
        ]);

        header("Location: list.php?msg=add_success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Phòng Mới - ALTF4 Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        .card-main {
            border: none;
            border-radius: 16px;
        }

        .navbar-brand {
            letter-spacing: 0.3px;
        }
    </style>
</head>

<body>

<div class="admin-wrapper">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="../admin/dashboard.php">
                <i class="bi bi-shield-lock-fill"></i>
                ALTF4 Admin
            </a>

            <button 
                class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#adminNavbar"
                aria-controls="adminNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="../admin/dashboard.php">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="list.php">
                            <i class="bi bi-door-open-fill"></i>
                            Quản lý phòng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../khach_hang/list.php">
                            <i class="bi bi-people-fill"></i>
                            Quản lý khách hàng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="bi bi-globe"></i>
                            Trang chủ website
                        </a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-white small">
                        Xin chào,
                        <strong><?= htmlspecialchars($admin_name) ?></strong>
                    </span>

                    <a href="../admin/logout.php" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        Đăng xuất
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <div class="container">

        <div class="card card-main shadow-sm p-4 mb-5" style="max-width: 750px; margin: 0 auto;">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold text-primary mb-1">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Thêm phòng mới
                    </h3>
                    <p class="text-muted mb-0">
                        Nhập thông tin phòng cần thêm vào hệ thống khách sạn.
                    </p>
                </div>

                <a href="list.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i>
                    Quay lại
                </a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="formPhong" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label fw-bold">Số phòng <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="so_phong" 
                        id="so_phong" 
                        class="form-control" 
                        placeholder="Ví dụ: 105, VIP03"
                        value="<?= isset($_POST['so_phong']) ? htmlspecialchars($_POST['so_phong']) : '' ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Loại phòng <span class="text-danger">*</span></label>
                    <select name="loai_phong" id="loai_phong" class="form-select">
                        <option value="">-- Chọn hạng phòng --</option>
                        <option value="Single" <?= (isset($_POST['loai_phong']) && $_POST['loai_phong'] == 'Single') ? 'selected' : '' ?>>Single Room</option>
                        <option value="Double" <?= (isset($_POST['loai_phong']) && $_POST['loai_phong'] == 'Double') ? 'selected' : '' ?>>Double Room</option>
                        <option value="Family" <?= (isset($_POST['loai_phong']) && $_POST['loai_phong'] == 'Family') ? 'selected' : '' ?>>Family Room</option>
                        <option value="VIP Luxury" <?= (isset($_POST['loai_phong']) && $_POST['loai_phong'] == 'VIP Luxury') ? 'selected' : '' ?>>VIP Luxury Suite</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giá phòng (VNĐ/đêm) <span class="text-danger">*</span></label>
                    <input 
                        type="number" 
                        name="gia_phong" 
                        id="gia_phong" 
                        class="form-control" 
                        placeholder="Ví dụ: 700000"
                        min="1"
                        value="<?= isset($_POST['gia_phong']) ? htmlspecialchars($_POST['gia_phong']) : '' ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="trong" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == 'trong') ? 'selected' : '' ?>>
                            Trống
                        </option>
                        <option value="cho_xac_nhan" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == 'cho_xac_nhan') ? 'selected' : '' ?>>
                            Chờ xác nhận
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Hình ảnh phòng</label>
                    <input 
                        type="file" 
                        name="hinh_anh" 
                        id="hinh_anh" 
                        class="form-control" 
                        accept="image/jpeg,image/png,image/webp"
                    >
                    <div class="form-text">
                        Chỉ nhận JPG, JPEG, PNG, WEBP. Dung lượng tối đa 2MB.
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="list.php" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left-circle me-1"></i>
                        Quay lại
                    </a>

                    <button type="submit" name="save" class="btn btn-primary px-5">
                        <i class="bi bi-save-fill me-1"></i>
                        Lưu lại
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('formPhong').addEventListener('submit', function(e) {
    let soPhong = document.getElementById('so_phong').value.trim();
    let loaiPhong = document.getElementById('loai_phong').value;
    let giaPhong = document.getElementById('gia_phong').value.trim();

    if (soPhong === "" || loaiPhong === "" || giaPhong === "") {
        alert("Vui lòng không để trống các trường có dấu (*)!");
        e.preventDefault();
        return false;
    }

    if (Number(giaPhong) <= 0) {
        alert("Giá phòng phải lớn hơn 0!");
        e.preventDefault();
        return false;
    }
});
</script>

</body>
</html>
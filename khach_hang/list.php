<?php
require_once '../admin/auth.php';
require_once '../config/db.php';

try {
    $sql = "
        SELECT *
        FROM khach_hang
        ORDER BY id DESC
    ";

    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Không thể tải dữ liệu!");
}

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng - ALTF4 Admin</title>

    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" 
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f4f6f9;
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

    <!-- NAVBAR ADMIN -->
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
                        <a class="nav-link" href="../phong/list.php">
                            <i class="bi bi-door-open-fill"></i>
                            Quản lý phòng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="list.php">
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

    <!-- NỘI DUNG CHÍNH -->
    <div class="container">

        <div class="card card-main shadow-sm p-4 mb-5">

            <!-- THÔNG BÁO -->
            <?php if (isset($_GET['msg'])): ?>

                <?php if ($_GET['msg'] == 'add_success'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Thêm khách hàng thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php elseif ($_GET['msg'] == 'edit_success'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Cập nhật khách hàng thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php elseif ($_GET['msg'] == 'del_success'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-trash-fill me-2"></i>
                        Đã xóa khách hàng thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php elseif ($_GET['msg'] == 'error'): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Có lỗi xảy ra, vui lòng thử lại!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <!-- TIÊU ĐỀ + NÚT -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-people-fill text-success me-2"></i>
                        Quản lý khách hàng
                    </h2>
                    <p class="text-muted mb-0">
                        Xem, thêm, sửa và xóa thông tin khách hàng trong hệ thống.
                    </p>
                </div>

                <div class="d-flex gap-2 flex-wrap">

                    <a href="../admin/dashboard.php" class="btn btn-outline-info">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Dashboard
                    </a>

                    <a href="../index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-house-door-fill me-1"></i>
                        Về trang chủ
                    </a>

                    <a href="add.php" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i>
                        Thêm khách hàng
                    </a>

                </div>
            </div>

            <!-- THÔNG TIN TỔNG -->
            <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap">
                <span>
                    <i class="bi bi-info-circle-fill text-primary me-1"></i>
                    Tổng số khách hàng:
                    <strong><?= count($data) ?></strong>
                </span>
            </div>

            <!-- BẢNG KHÁCH HÀNG -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>CCCD</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th class="text-center" width="180">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($data) > 0): ?>

                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <td class="fw-bold">
                                        <?= htmlspecialchars($row['id']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['ho_ten']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['cccd']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['so_dien_thoai']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['email']) ?>
                                    </td>

                                    <td class="text-center">
                                        <a 
                                            href="edit.php?id=<?= $row['id'] ?>" 
                                            class="btn btn-warning btn-sm"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                            Sửa
                                        </a>

                                        <a 
                                            href="delete.php?id=<?= $row['id'] ?>" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn xóa khách hàng này không?')"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Chưa có khách hàng nào trong hệ thống.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
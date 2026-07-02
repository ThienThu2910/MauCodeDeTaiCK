<?php
require_once '../admin/auth.php';
require_once '../config/db.php';

// ---- PHÂN TRANG ----
$limit = 5;
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? intval($_GET['p']) : 1;

if ($page < 1) {
    $page = 1;
}

// ---- TÌM KIẾM ----
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$params = [];

try {
    $sql_count = "SELECT COUNT(*) FROM phong WHERE 1=1";
    $sql_list  = "SELECT * FROM phong WHERE 1=1";

    if ($search !== '') {
        $sql_count .= " AND (so_phong LIKE ? OR loai_phong LIKE ?)";
        $sql_list  .= " AND (so_phong LIKE ? OR loai_phong LIKE ?)";

        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute($params);
    $total_rows = $stmt_count->fetchColumn();

    $total_pages = ceil($total_rows / $limit);

    if ($total_pages < 1) {
        $total_pages = 1;
    }

    if ($page > $total_pages) {
        $page = $total_pages;
    }

    $offset = ($page - 1) * $limit;

    $sql_list .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";

    $stmt_list = $pdo->prepare($sql_list);
    $stmt_list->execute($params);
    $rooms = $stmt_list->fetchAll(PDO::FETCH_ASSOC);

    // ---- THỐNG KÊ NHANH ----
    $stmt_total_rooms = $pdo->query("SELECT COUNT(*) FROM phong");
    $tong_phong = $stmt_total_rooms->fetchColumn();

    $stmt_available_rooms = $pdo->query("SELECT COUNT(*) FROM phong WHERE trang_thai = 'trong'");
    $phong_trong = $stmt_available_rooms->fetchColumn();

    $stmt_pending_rooms = $pdo->query("SELECT COUNT(*) FROM phong WHERE trang_thai = 'cho_xac_nhan'");
    $phong_cho_xac_nhan = $stmt_pending_rooms->fetchColumn();

} catch (PDOException $e) {
    die("Lỗi truy vấn CSDL: " . $e->getMessage());
}

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Phòng - ALTF4 Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        .page-title {
            font-weight: 700;
        }

        .table img {
            transition: 0.3s;
        }

        .table img:hover {
            transform: scale(1.08);
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

        <div class="card card-main shadow-sm p-4 mb-5">

            <?php if (isset($_GET['msg'])): ?>

                <?php if ($_GET['msg'] == 'add_success'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Thêm phòng mới thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php elseif ($_GET['msg'] == 'edit_success'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Cập nhật thông tin phòng thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php elseif ($_GET['msg'] == 'del_success'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-trash-fill me-2"></i>
                        Đã xóa phòng thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php elseif ($_GET['msg'] == 'delete_denied'): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Không thể xóa phòng vì phòng này đã có đơn đặt phòng!
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

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

                <div>
                    <h2 class="page-title text-dark mb-1">
                        <i class="bi bi-door-open-fill text-primary me-2"></i>
                        Quản lý phòng
                    </h2>
                    <p class="text-muted mb-0">
                        Thêm, sửa, xóa, tìm kiếm và phân trang danh sách phòng khách sạn.
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

                    <a href="add.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Thêm phòng mới
                    </a>

                </div>
            </div>

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small text-uppercase">Tổng số phòng</p>
                                <h3 class="fw-bold mb-0"><?= $tong_phong ?></h3>
                            </div>
                            <i class="bi bi-building fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small text-uppercase">Phòng trống</p>
                                <h3 class="fw-bold mb-0"><?= $phong_trong ?></h3>
                            </div>
                            <i class="bi bi-door-open fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small text-uppercase">Chờ xác nhận</p>
                                <h3 class="fw-bold mb-0"><?= $phong_cho_xac_nhan ?></h3>
                            </div>
                            <i class="bi bi-clock-history fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>

            </div>

            <form method="GET" class="row g-2 mb-4 justify-content-end">
                <div class="col-md-6 col-lg-5 d-flex">

                    <input 
                        type="text" 
                        name="search" 
                        class="form-control form-control-sm me-2" 
                        placeholder="Tìm theo số phòng hoặc loại phòng..." 
                        value="<?= htmlspecialchars($search) ?>"
                    >

                    <button type="submit" class="btn btn-sm btn-secondary px-3">
                        <i class="bi bi-search"></i>
                    </button>

                    <?php if ($search !== ''): ?>
                        <a href="list.php" class="btn btn-sm btn-outline-danger ms-2">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>

                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <p class="text-muted mb-0">
                    Tổng số phòng tìm thấy:
                    <strong><?= $total_rows ?></strong>
                </p>

                <?php if ($search !== ''): ?>
                    <p class="text-muted mb-0">
                        Từ khóa:
                        <strong><?= htmlspecialchars($search) ?></strong>
                    </p>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 110px;">Hình ảnh</th>
                            <th>Số phòng</th>
                            <th>Loại phòng</th>
                            <th>Giá phòng</th>
                            <th>Trạng thái</th>
                            <th class="text-center" style="width: 190px;">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($rooms) > 0): ?>

                            <?php foreach ($rooms as $row): ?>

                                <?php
                                    if (!empty($row['hinh_anh'])) {
                                        if (filter_var($row['hinh_anh'], FILTER_VALIDATE_URL)) {
                                            $img_path = $row['hinh_anh'];
                                        } else {
                                            $img_path = '../uploads/' . $row['hinh_anh'];
                                        }
                                    } else {
                                        $img_path = 'https://images.unsplash.com/photo-1611892440504-42a792e24d02?q=80&w=150';
                                    }

                                    $confirm_text = "Bạn có chắc chắn muốn xóa phòng " . $row['so_phong'] . " không?";
                                ?>

                                <tr>
                                    <td>
                                        <img 
                                            src="<?= htmlspecialchars($img_path) ?>" 
                                            class="rounded shadow-sm border" 
                                            style="width: 85px; height: 55px; object-fit: cover;"
                                            alt="Ảnh phòng <?= htmlspecialchars($row['so_phong']) ?>"
                                        >
                                    </td>

                                    <td class="fw-bold text-primary">
                                        <?= htmlspecialchars($row['so_phong']) ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark text-uppercase">
                                            <?= htmlspecialchars($row['loai_phong']) ?>
                                        </span>
                                    </td>

                                    <td class="fw-bold text-danger">
                                        <?= number_format($row['gia_phong'], 0, ',', '.') ?> đ
                                    </td>

                                    <td>
                                        <?php if ($row['trang_thai'] == 'trong'): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Trống
                                            </span>
                                        <?php elseif ($row['trang_thai'] == 'cho_xac_nhan'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock-history me-1"></i>
                                                Chờ xác nhận
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-question-circle me-1"></i>
                                                Không xác định
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a 
                                            href="edit.php?id=<?= $row['id'] ?>" 
                                            class="btn btn-sm btn-outline-warning me-1"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                            Sửa
                                        </a>

                                        <a 
                                            href="delete.php?id=<?= $row['id'] ?>" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick='return confirm(<?= json_encode($confirm_text, JSON_UNESCAPED_UNICODE) ?>)'
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
                                    <i class="bi bi-search fs-1 d-block mb-2"></i>
                                    Không tìm thấy phòng nào phù hợp.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_rows > $limit): ?>
                <nav class="mt-4">
                    <ul class="pagination pagination-sm justify-content-center">

                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a 
                                class="page-link" 
                                href="?p=<?= $page - 1 ?>&search=<?= urlencode($search) ?>"
                            >
                                <i class="bi bi-chevron-left"></i>
                                Trước
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a 
                                    class="page-link" 
                                    href="?p=<?= $i ?>&search=<?= urlencode($search) ?>"
                                >
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a 
                                class="page-link" 
                                href="?p=<?= $page + 1 ?>&search=<?= urlencode($search) ?>"
                            >
                                Sau
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
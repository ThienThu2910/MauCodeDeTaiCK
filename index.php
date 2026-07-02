<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU AN TOÀN (Bảo vệ tránh Fatal Error nếu thiếu file config)
$db_file = 'config/db.php';
if (file_exists($db_file)) {
    try {
        require_once $db_file;
    } catch (Exception $e) {
        $db_error = $e->getMessage();
    }
}

// Nhận tham số điều hướng trang (Mặc định khi truy cập là trang chủ 'trang-chu')
$page = isset($_GET['page']) ? $_GET['page'] : 'trang-chu';
?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALTF4 HOTEL - Hệ Thống Đặt Phòng Khách Sạn Thượng Lưu</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-gold fs-3" href="index.php?page=trang-chu">
                <i class="bi bi-building-haze"></i> ALTF4 HOTEL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'trang-chu' ? 'active fw-bold text-gold' : '' ?>" href="index.php?page=trang-chu">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'danh-sach' ? 'active fw-bold text-gold' : '' ?>" href="index.php?page=danh-sach">Danh sách phòng</a>
                    </li>
                    </li> <li class="nav-item">
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'tien-ich' ? 'active fw-bold text-gold' : '' ?>" href="index.php?page=tien-ich">Tiện ích</a>
                    </li>
                </ul>

                <form class="d-flex me-3" role="search" action="index.php" method="GET">
                    <input type="hidden" name="page" value="danh-sach">
                    <input class="form-control me-2 form-control-sm bg-transparent border-secondary" type="search" name="search" placeholder="Tìm số phòng, loại phòng..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" aria-label="Search">
                    <button class="btn btn-outline-gold btn-sm" type="submit"><i class="bi bi-search"></i></button>
                </form>

                <button class="btn btn-link text-body nav-link me-3 shadow-none" id="darkModeToggle" type="button" title="Thay đổi giao diện Sáng / Tối">
                    <i class="bi bi-moon-fill fs-5" id="darkModeIcon"></i>
                </button>

                <a href="admin/login.php" class="btn btn-sm btn-gold px-3"><i class="bi bi-person-circle"></i> Đăng Nhập</a>
            </div>
        </div>
    </nav>

    <main style="min-height: 75vh;">
        <?php
        switch ($page) {
            case 'trang-chu':
                // Nếu tồn tại kết nối PDO, hiển thị slider và phòng tiêu biểu trực tiếp từ Database
                if (isset($pdo)) {
                    ?>
                    <div id="heroBannerSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroBannerSlider" data-bs-slide-to="0" class="active" aria-current="true"></button>
                            <button type="button" data-bs-target="#heroBannerSlider" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#heroBannerSlider" data-bs-slide-to="2"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="slider-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1200'); height: 70vh; background-size: cover; background-position: center;"></div>
                                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-white">
                                    <h1 class="display-3 fw-bold text-uppercase mb-3 text-white">Trải Nghiệm Thượng Lưu</h1>
                                    <p class="lead mb-4 fs-4 text-white-50">Hệ thống phòng nghỉ đẳng cấp 5 sao mang đậm dấu ấn phong cách hoàn mỹ.</p>
                                    <a href="index.php?page=danh-sach" class="btn btn-gold btn-lg px-5 py-2 fs-5">Đặt Phòng Ngay</a>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="slider-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200'); height: 70vh; background-size: cover; background-position: center;"></div>
                                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-white">
                                    <h1 class="display-3 fw-bold text-uppercase mb-3 text-white">Không Gian Tuyệt Mỹ</h1>
                                    <p class="lead mb-4 fs-4 text-white-50">Nơi thời gian lắng đọng và mọi giác quan được nuông chiều trọn vẹn.</p>
                                    <a href="index.php?page=danh-sach" class="btn btn-gold btn-lg px-5 py-2 fs-5">Khám Phá Ngay</a>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="slider-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1200'); height: 70vh; background-size: cover; background-position: center;"></div>
                                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-white">
                                    <h1 class="display-3 fw-bold text-uppercase mb-3 text-white">Dịch Vụ Đẳng Cấp</h1>
                                    <p class="lead mb-4 fs-4 text-white-50">Sự chuyên nghiệp, tận tâm tạo nên giá trị độc bản tại ALTF4 HOTEL.</p>
                                    <a href="index.php?page=danh-sach" class="btn btn-gold btn-lg px-5 py-2 fs-5">Xem Phòng Trống</a>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerSlider" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroBannerSlider" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>

                    <div class="container my-5 py-3">
                        <h2 class="text-center mb-2 fw-bold text-uppercase" style="letter-spacing: 1px;">Không Gian Nghỉ Dưỡng</h2>
                        <p class="text-center text-muted small mb-5">Danh sách các phòng tiêu biểu đang sẵn sàng phục vụ quý khách tại ALTF4</p>
                        <div class="row g-4">
                            <?php
                            $stmt = $pdo->query("SELECT * FROM phong WHERE trang_thai = 'trong' LIMIT 3");
                            $phongs = $stmt->fetchAll();
                            if (count($phongs) > 0) {
                                foreach ($phongs as $row) {
                                    $img = !empty($row['hinh_anh']) ? $row['hinh_anh'] : 'https://images.unsplash.com/photo-1611892440504-42a792e24d02?q=80&w=600';
                            ?>
                                    <div class="col-md-4">
                                        <div class="card h-100 d-flex flex-column shadow-sm">
                                            <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Room">
                                            <div class="card-body d-flex flex-column p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="badge bg-warning text-dark px-3 py-2 text-uppercase fw-bold">Sẵn Sàng</span>
                                                    <small class="text-muted fw-bold fs-6">Phòng: <?= htmlspecialchars($row['so_phong']) ?></small>
                                                </div>
                                                <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($row['loai_phong']) ?> Room</h4>
                                                <p class="card-text text-muted small mb-4">Trải nghiệm tiện nghi cao cấp, nội thất hiện đại tinh tế cùng dịch vụ chăm sóc hoàn hảo.</p>
                                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                                    <h5 class="text-gold fw-bold mb-0 fs-4" style="color: #dfb76c;"><?= number_format($row['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fw-light fs-6">/ đêm</small></h5>
                                                    <a href="index.php?page=chi-tiet&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning px-3">Chi Tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<div class="col-12 text-center text-muted my-4"><p class="fs-5">Hiện tại hệ thống đang cập nhật danh sách phòng trống.</p></div>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } else {
                    // Nếu lỗi hoặc chưa kết nối DB, nạp file tĩnh dự phòng
                    if (file_exists('trang-chu.php')) include 'trang-chu.php';
                }

                // Gọi khối khuyến mãi xuất hiện ở cuối trang chủ
                if (file_exists('khuyen-mai.php')) include 'khuyen-mai.php'; 
                break;
                
            case 'danh-sach':
                // Xử lý bộ lọc tìm kiếm nâng cao toàn hệ thống
                if (isset($pdo)) {
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                    $filter_loai = isset($_GET['loai_phong']) ? trim($_GET['loai_phong']) : '';
                    $filter_gia = isset($_GET['gia_phong']) ? trim($_GET['gia_phong']) : '';

                    $sql = "SELECT * FROM phong WHERE 1=1";
                    $params = [];

                    if ($search !== '') {
                        $sql .= " AND (so_phong LIKE ? OR loai_phong LIKE ?)";
                        $params[] = "%$search%";
                        $params[] = "%$search%";
                    }
                    if ($filter_loai !== '') {
                        $sql .= " AND loai_phong = ?";
                        $params[] = $filter_loai;
                    }
                    if ($filter_gia !== '') {
                        if ($filter_gia == 'duoi_1tr5') {
                            $sql .= " AND gia_phong < 1500000";
                        } elseif ($filter_gia == 'tren_1tr5') {
                            $sql .= " AND gia_phong >= 1500000";
                        }
                    }

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $phongs = $stmt->fetchAll();

                    $loai_stmt = $pdo->query("SELECT DISTINCT loai_phong FROM phong");
                    $cac_loai_phong = $loai_stmt->fetchAll(PDO::FETCH_COLUMN);
                    ?>
                    <div class="container my-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php?page=trang-chu" class="text-muted text-decoration-none">Trang chủ</a></li>
                                <li class="breadcrumb-item active fw-bold text-warning" aria-current="page">Danh sách phòng</li>
                            </ol>
                        </nav>
                        
                        <h2 class="fw-bold text-uppercase mb-4">Tất Cả Các Loại Phòng Nghỉ</h2>
                        
                        <form action="index.php" method="GET" class="row g-3 mb-5 p-4 rounded border mx-0" style="background-color: var(--bs-tertiary-bg);">
                            <input type="hidden" name="page" value="danh-sach">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Từ khóa tìm kiếm</label>
                                <input type="text" name="search" class="form-control form-control-sm bg-transparent border-secondary text-body" placeholder="Nhập số phòng hoặc loại phòng..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Phân loại hạng phòng</label>
                                <select name="loai_phong" class="form-select form-select-sm bg-transparent border-secondary text-body">
                                    <option value="">-- Tất cả các hạng --</option>
                                    <?php foreach ($cac_loai_phong as $lp): ?>
                                        <option value="<?= htmlspecialchars($lp) ?>" <?= $filter_loai == $lp ? 'selected' : '' ?>><option-text><?= htmlspecialchars($lp) ?></option-text></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Mức giá niêm yết</label>
                                <select name="gia_phong" class="form-select form-select-sm bg-transparent border-secondary text-body">
                                    <option value="">-- Tất cả mức giá --</option>
                                    <option value="duoi_1tr5" <?= $filter_gia == 'duoi_1tr5' ? 'selected' : '' ?>>Dưới 1.500.000 đ</option>
                                    <option value="tren_1tr5" <?= $filter_gia == 'tren_1tr5' ? 'selected' : '' ?>>Từ 1.500.000 đ trở lên</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-warning btn-sm w-100 py-2 text-uppercase fw-bold"><i class="bi bi-sliders"></i> Lọc Kết Quả</button>
                            </div>
                        </form>

                        <div class="row g-4">
                            <?php
                            if (count($phongs) > 0) {
                                foreach ($phongs as $row) {
                                    $img = !empty($row['hinh_anh']) ? $row['hinh_anh'] : 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=600';
                                    $status_badge = $row['trang_thai'] == 'trong' ? '<span class="badge bg-success px-3 py-1">Trống</span>' : '<span class="badge bg-secondary px-3 py-1">Đang bận</span>';
                            ?>
                                    <div class="col-md-4">
                                        <div class="card h-100 d-flex flex-column shadow-sm">
                                            <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Room">
                                            <div class="card-body d-flex flex-column p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <?= $status_badge ?>
                                                    <small class="text-muted fw-bold">Phòng số: <?= htmlspecialchars($row['so_phong']) ?></small>
                                                </div>
                                                <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($row['loai_phong']) ?> Room</h4>
                                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                                    <h5 class="text-gold fw-bold mb-0 fs-5" style="color: #dfb76c;"><?= number_format($row['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fw-light fs-6">/đêm</small></h5>
                                                    <a href="index.php?page=chi-tiet&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning px-3">Chi Tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<div class="col-12 text-center text-muted my-5 py-5"><i class="bi bi-search fs-1 text-warning"></i><p class="mt-3 fs-5">Không có phòng nào phù hợp với điều kiện tìm kiếm.</p></div>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                } else {
                    if (file_exists('danh-sach-phong.php')) include 'danh-sach-phong.php';
                }
                break;
                
            case 'chi-tiet':
                if (isset($pdo)) {
                    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                    $stmt = $pdo->prepare("SELECT * FROM phong WHERE id = ?");
                    $stmt->execute([$id]);
                    $room = $stmt->fetch();

                    if ($room) {
                        $img = !empty($room['hinh_anh']) ? $room['hinh_anh'] : 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=800';
                    ?>
                        <div class="container my-4">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php?page=trang-chu" class="text-muted text-decoration-none">Trang chủ</a></li>
                                    <li class="breadcrumb-item"><a href="index.php?page=danh-sach" class="text-muted text-decoration-none">Danh sách phòng</a></li>
                                    <li class="breadcrumb-item active fw-bold text-warning">Chi tiết phòng</li>
                                </ol>
                            </nav>

                            <div class="row g-5 mt-2">
                                <div class="col-md-7">
                                    <img src="<?= htmlspecialchars($img) ?>" class="img-fluid rounded w-100 shadow" style="max-height: 450px; object-fit: cover;" alt="Chi tiết phòng">
                                </div>
                                <div class="col-md-5 d-flex flex-column justify-content-between">
                                    <div>
                                        <h1 class="fw-bold text-body mb-2"><?= htmlspecialchars($room['loai_phong']) ?> Luxury Suite</h1>
                                        <div class="mb-4 fs-6">
                                            <?= $room['trang_thai'] == 'trong' ? '<span class="badge bg-success px-3 py-2">SẢN SÀNG</span>' : '<span class="badge bg-secondary px-3 py-2">ĐANG ĐƯỢC THUÊ</span>' ?>
                                            <span class="ms-3 text-muted">Vị trí phòng: <strong class="text-body"><?= htmlspecialchars($room['so_phong']) ?></strong></span>
                                        </div>
                                        <h2 class="fw-bold mb-4" style="color: #dfb76c;"><?= number_format($room['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fs-6 fw-light">/ đêm</small></h2>
                                        <p class="text-muted" style="line-height: 1.7;">Không gian phòng nghỉ tinh tế được tối ưu hóa cho sự thoải mái của bạn. Chuẩn phong cách thượng lưu của hệ thống khách sạn ALTF4.</p>
                                    </div>
                                    <div class="mt-4">
                                        <a href="dat_phong.php?id_phong=<?= $room['id'] ?>" class="btn btn-warning btn-lg w-100 py-3 text-uppercase fw-bold fs-6 <?= $room['trang_thai'] != 'trong' ? 'disabled btn-secondary text-white-50' : '' ?>">
                                            <i class="bi bi-calendar2-check-fill me-2"></i> <?= $room['trang_thai'] == 'trong' ? 'Đăng Ký Đặt Phòng Trực Tiếp' : 'Phòng Hiện Đang Được Thuê' ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                        echo '<div class="container my-5 py-5 text-center text-muted"><p class="fs-5">Mã số phòng không tồn tại.</p></div>';
                    }
                } else {
                    if (file_exists('chi-tiet-phong.php')) include 'chi-tiet-phong.php';
                }
                break;
                
            case 'tien-ich':
                if (file_exists('tien-ich.php')) include 'tien-ich.php';
                break;
                
            case 'lien-he':
                if (file_exists('lien-he.php')) include 'lien-he.php';
                break;
                
            default:
                if (file_exists('trang-chu.php')) include 'trang-chu.php';
                break;
        }
        ?>
    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        function initScrollReveal() {
            const revealElements = document.querySelectorAll('.scroll-reveal');

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: "0px 0px -40px 0px"
            });

            revealElements.forEach(element => {
                observer.observe(element);
            });
        }

        initScrollReveal();

        const observerTarget = document.querySelector('main') || document.body;
        const mutationObserver = new MutationObserver(() => {
            initScrollReveal();
        });

        mutationObserver.observe(observerTarget, {
            childList: true,
            subtree: true
        });
    });
    </script>

    <footer class="site-footer py-5 mt-5">
        <div class="container py-2">
            <div class="row g-4">

                <div class="col-md-5">
                    <h4 class="fw-bold mb-3">
                        <i class="bi bi-building-haze"></i> ALTF4 HOTEL
                    </h4>

                    <p class="small pe-md-5" style="line-height: 1.6;">
                        Hệ thống quản lý khách sạn và giải pháp nghỉ dưỡng cao cấp đạt tiêu chuẩn quốc tế,
                        mang tới trải nghiệm thượng lưu đích thực cho mọi khách hàng.
                    </p>
                </div>

                <div class="col-md-4">
                    <h5 class="fw-bold text-uppercase small mb-3" style="letter-spacing: 0.5px;">
                        Tổng đài hỗ trợ
                    </h5>

                    <p class="small mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        123 Đường ABC, Phường XYZ, TP. Cà Mau
                    </p>

                    <p class="small mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        Hotline: 0123.456.789
                    </p>

                    <p class="small mb-0">
                        <i class="bi bi-envelope me-2"></i>
                        Email: info@altf4hotel.com
                    </p>
                </div>

                <div class="col-md-3">
                    <h5 class="fw-bold text-uppercase small mb-3" style="letter-spacing: 0.5px;">
                        Thông tin đồ án
                    </h5>

                    <p class="small mb-0">
                        © 2026 Học phần Lập trình Web.
                    </p>

                    <p class="small mb-0">
                        Đồ án kết thúc môn - Hệ thống Quản lý Khách sạn ALTF4.
                    </p>
                </div>

            </div>
        </div>
    </footer>

    <button 
        type="button" 
        class="btn btn-gold rounded-circle position-fixed shadow" 
        id="btnBackToTop" 
        style="bottom: 40px; right: 40px; width: 50px; height: 50px; display: none; z-index: 9999;"
    >
        <i class="bi bi-arrow-up fs-4 text-dark fw-bold"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    const themeToggle = document.getElementById('darkModeToggle');
    const themeIcon = document.getElementById('darkModeIcon');
    const htmlTag = document.documentElement;

    if (localStorage.getItem('theme') === 'dark') {
        htmlTag.setAttribute('data-bs-theme', 'dark');
        themeIcon.className = 'bi bi-moon-stars-fill text-warning';
    }

    themeToggle.addEventListener('click', () => {
        if (htmlTag.getAttribute('data-bs-theme') === 'light') {
            htmlTag.setAttribute('data-bs-theme', 'dark');
            themeIcon.className = 'bi bi-moon-stars-fill text-warning';
            localStorage.setItem('theme', 'dark');
        } else {
            htmlTag.setAttribute('data-bs-theme', 'light');
            themeIcon.className = 'bi bi-moon-fill fs-5';
            localStorage.setItem('theme', 'light');
        }
    });

    const btnBackToTop = document.getElementById('btnBackToTop');

    window.onscroll = function () {
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            btnBackToTop.style.display = 'block';
        } else {
            btnBackToTop.style.display = 'none';
        }
    };

    btnBackToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    </script>

    </body>
    </html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JS Xử lý bật/tắt Dark Mode đồng bộ lõi Bootstrap 5.3
        const themeToggle = document.getElementById('darkModeToggle');
        const themeIcon = document.getElementById('darkModeIcon');
        const htmlTag = document.documentElement;

        if (localStorage.getItem('theme') === 'dark') {
            htmlTag.setAttribute('data-bs-theme', 'dark');
            themeIcon.className = 'bi bi-moon-stars-fill text-warning';
        }

        themeToggle.addEventListener('click', () => {
            if (htmlTag.getAttribute('data-bs-theme') === 'light') {
                htmlTag.setAttribute('data-bs-theme', 'dark');
                themeIcon.className = 'bi bi-moon-stars-fill text-warning';
                localStorage.setItem('theme', 'dark');
            } else {
                htmlTag.setAttribute('data-bs-theme', 'light');
                themeIcon.className = 'bi bi-moon-fill fs-5';
                localStorage.setItem('theme', 'light');
            }
        });

        // Xử lý logic hiển thị và cuộn mượt cho nút Back To Top
        const btnBackToTop = document.getElementById('btnBackToTop');
        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                btnBackToTop.style.display = 'block';
            } else {
                btnBackToTop.style.display = 'none';
            }
        };
        btnBackToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
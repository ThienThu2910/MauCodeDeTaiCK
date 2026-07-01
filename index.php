<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU ĐỘNG (Sử dụng PDO theo yêu cầu)
require_once 'config/db.php';

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
            <a class="navbar-brand fw-bold text-gold fs-3" href="index.php">
                <i class="bi bi-building-haze"></i> ALTF4 HOTEL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'trang-chu' ? 'active fw-bold text-gold' : '' ?>" href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'danh-sach' ? 'active fw-bold text-gold' : '' ?>" href="index.php?page=danh-sach">Danh sách phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="phong/list.php">
                            <i class="bi bi-door-open-fill text-gold me-1"></i> Quản lý phòng
                        </a>
                 
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

                <a href="admin/login.php" class="btn btn-sm btn-gold px-3"><i class="bi bi-person-circle"></i> Nhân viên</a>
            </div>
        </div>
    </nav>

    <div class="main-content-wrapper" style="padding-top: 80px;">

        <?php
        if ($page == 'trang-chu') {
            // =======================================================
            // [GIAO DIỆN TRANG CHỦ] - BANNER + PHÒNG TIÊU BIỂU + TIỆN ÍCH THU GỌN
            // =======================================================
        ?>
            <div id="heroBannerSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroBannerSlider" data-bs-slide-to="0" class="active" aria-current="true"></button>
                    <button type="button" data-bs-target="#heroBannerSlider" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroBannerSlider" data-bs-slide-to="2"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="slider-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1200');"></div>
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-white">
                            <h1 class="display-3 fw-bold text-uppercase mb-3 slider-title text-white">Trải Nghiệm Thượng Lưu</h1>
                            <p class="lead mb-4 fs-4 text-white-50 slider-text">Hệ thống phòng nghỉ đẳng cấp 5 sao mang đậm dấu ấn phong cách hoàn mỹ.</p>
                            <a href="index.php?page=danh-sach" class="btn btn-gold btn-lg px-5 py-2 fs-5">Đặt Phòng Ngay</a>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="slider-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200');"></div>
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-white">
                            <h1 class="display-3 fw-bold text-uppercase mb-3 slider-title text-white">Không Gian Tuyệt Mỹ</h1>
                            <p class="lead mb-4 fs-4 text-white-50 slider-text">Nơi thời gian lắng đọng và mọi giác quan được nuông chiều trọn vẹn.</p>
                            <a href="index.php?page=danh-sach" class="btn btn-gold btn-lg px-5 py-2 fs-5">Khám Phá Ngay</a>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="slider-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1200');"></div>
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-white">
                            <h1 class="display-3 fw-bold text-uppercase mb-3 slider-title text-white">Dịch Vụ Đẳng Cấp</h1>
                            <p class="lead mb-4 fs-4 text-white-50 slider-text">Sự chuyên nghiệp, tận tâm tạo nên giá trị độc bản tại ALTF4 HOTEL.</p>
                            <a href="index.php?page=danh-sach" class="btn btn-gold btn-lg px-5 py-2 fs-5">Xem Phòng Trống</a>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroBannerSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="container my-5 py-3 scroll-reveal">
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
                                <div class="card h-100 d-flex flex-column">
                                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top room-img" alt="Phòng <?= htmlspecialchars($row['so_phong']) ?>">
                                    <div class="card-body d-flex flex-column p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge badge-gold px-3 py-2 text-uppercase">Sẵn Sàng</span>
                                            <small class="text-muted fw-bold fs-6">Phòng: <?= htmlspecialchars($row['so_phong']) ?></small>
                                        </div>
                                        <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($row['loai_phong']) ?> Room</h4>
                                        <p class="card-text text-muted small mb-4">Trải nghiệm tiện nghi cao cấp, nội thất hiện đại tinh tế cùng dịch vụ chăm sóc hoàn hảo, chu đáo 24/7.</p>
                                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                            <h5 class="text-gold fw-bold mb-0 fs-4"><?= number_format($row['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fw-light fs-6">/ đêm</small></h5>
                                            <a href="index.php?page=chi-tiet&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-gold px-3">Chi Tiết</a>
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

            <div class="container my-5 py-5 scroll-reveal">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-9 col-md-12">
                        <div class="row g-4 h-100">
                            <div class="col-md-4">
                                <div class="hotel-amenity-card h-100 d-flex flex-column border rounded shadow-sm bg-body-tertiary">
                                    <div class="hotel-img-container"><img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600" alt="Nhà hàng"></div>
                                    <div class="hotel-text-content p-3 flex-grow-1">
                                        <h5 class="hotel-card-title fw-bold">Nhà hàng</h5>
                                        <p class="hotel-card-desc text-muted mb-0">Không chỉ ở lối kiến trúc, ẩm thực của nhà hàng cũng mang đậm nét truyền thống...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="hotel-amenity-card h-100 d-flex flex-column border rounded shadow-sm bg-body-tertiary">
                                    <div class="hotel-img-container"><img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600" alt="Spa"></div>
                                    <div class="hotel-text-content p-3 flex-grow-1">
                                        <h5 class="hotel-card-title fw-bold">Spa</h5>
                                        <p class="hotel-card-desc text-muted mb-0">Lạc vào Spa Furama Resort sang trọng và quyến rũ để tận hưởng những phút giây...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="hotel-amenity-card h-100 d-flex flex-column border rounded shadow-sm bg-body-tertiary">
                                    <div class="hotel-img-container"><img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=600" alt="Yoga"></div>
                                    <div class="hotel-text-content p-3 flex-grow-1">
                                        <h5 class="hotel-card-title fw-bold">Yoga</h5>
                                        <p class="hotel-card-desc text-muted mb-0">Khách nghỉ tại Furama Resort có thể tham gia lớp học Yoga của khách sạn để thư giãn...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 d-flex flex-column justify-content-center text-center text-lg-start ps-lg-4">
                        <h2 class="display-5 fw-normal text-lowercase section-side-title m-0">tiện ích</h2>
                        <p class="text-muted text-uppercase tracking-wider small m-0 mt-1" style="font-size: 0.75rem; letter-spacing: 2px;">Các tiện ích khách sạn</p>
                    </div>
                </div>
            </div>
        <?php 
        } elseif ($page == 'tien-ich') {
            // =======================================================
            // [GIAO DIỆN TRANG TIỆN ÍCH ĐỘC LẬP MỚI TINH - KHÔNG TRÙNG TRANG CHỦ]
            // =======================================================
        ?>
            <div class="position-relative overflow-hidden text-center bg-dark text-white d-flex align-items-center justify-content-center" style="height: 350px; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=1200') no-repeat center center/cover;">
                <div>
                    <h1 class="display-4 fw-bold text-uppercase tracking-wide mb-3" style="font-family: 'Playfair Display', serif;">Dịch Vụ & Tiện Ích</h1>
                    <p class="lead text-white-50 max-w-2xl mx-auto px-3">Nơi mọi nhu cầu nghỉ dưỡng và tái tạo năng lượng của quý khách được nâng tầm thành nghệ thuật sống.</p>
                </div>
            </div>

            <div class="container my-5 py-4">
                <nav aria-label="breadcrumb" class="mb-5">
                    <ol class="breadcrumb justify-content-center bg-light py-2 px-3 rounded-pill shadow-sm" style="width: fit-content; margin: 0 auto;">
                        <li class="breadcrumb-item"><a href="index.php" class="text-muted text-decoration-none small">Trang chủ</a></li>
                        <li class="breadcrumb-item active text-gold fw-bold small" aria-current="page">Tiện ích cao cấp</li>
                    </ol>
                </nav>

                <div class="row g-5">
                    <div class="col-lg-4 col-md-6 scroll-reveal">
                        <div class="card h-100 border-0 bg-transparent text-center custom-page-card">
                            <div class="position-relative overflow-hidden rounded-4 mb-4 shadow">
                                <div class="hotel-img-container" style="height: 380px !important;">
                                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600" class="img-fluid w-100 h-100 object-fit-cover" alt="Nhà hàng">
                                </div>
                            </div>
                            <h3 class="fw-bold fs-4 mb-2 text-uppercase" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;">Nhà hàng quốc tế</h3>
                            <div class="w-25 mx-auto bg-gold my-2" style="height: 2px;"></div>
                            <p class="text-muted small px-3">Không chỉ dừng lại ở lối kiến trúc độc bản, ẩm thực của nhà hàng là sự giao thoa hoàn hảo giữa hương vị truyền thống tinh túy và phong cách Âu Mỹ đương đại đẳng cấp.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 scroll-reveal">
                        <div class="card h-100 border-0 bg-transparent text-center custom-page-card">
                            <div class="position-relative overflow-hidden rounded-4 mb-4 shadow">
                                <div class="hotel-img-container" style="height: 380px !important;">
                                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=600" class="img-fluid w-100 h-100 object-fit-cover" alt="Spa">
                                </div>
                            </div>
                            <h3 class="fw-bold fs-4 mb-2 text-uppercase" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;">Nirvana Spa</h3>
                            <div class="w-25 mx-auto bg-gold my-2" style="height: 2px;"></div>
                            <p class="text-muted small px-3">Rời xa những ồn ào để đắm mình vào không gian quyến rũ độc bản. Nơi các liệu trình trị liệu chuyên sâu nuôi dưỡng và đánh thức trọn vẹn mọi giác quan trong bạn.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 scroll-reveal">
                        <div class="card h-100 border-0 bg-transparent text-center custom-page-card">
                            <div class="position-relative overflow-hidden rounded-4 mb-4 shadow">
                                <div class="hotel-img-container" style="height: 380px !important;">
                                    <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=600" class="img-fluid w-100 h-100 object-fit-cover" alt="Yoga">
                                </div>
                            </div>
                            <h3 class="fw-bold fs-4 mb-2 text-uppercase" style="font-family: 'Playfair Display', serif; letter-spacing: 1px;">Yoga & Thiền định</h3>
                            <div class="w-25 mx-auto bg-gold my-2" style="height: 2px;"></div>
                            <p class="text-muted small px-3">Tận hưởng các lớp học Yoga chuyên nghiệp trong không gian đón gió biển trong lành, giúp bạn tìm lại sự cân bằng năng lượng sâu sắc và tái tạo tâm trí tuyệt đối.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
        } elseif ($page == 'danh-sach') {
            // =======================================================
            // [GIAO DIỆN DANH SÁCH PHÒNG] - BỘ LỌC ĐỘNG VÀ TÌM KIẾM
            // =======================================================
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
                        <li class="breadcrumb-item"><a href="index.php" class="text-muted text-decoration-none">Trang chủ</a></li>
                        <li class="breadcrumb-item active text-gold fw-bold" aria-current="page">Danh sách phòng</li>
                    </ol>
                </nav>
                
                <h2 class="fw-bold text-uppercase mb-4">Tất Cả Các Loại Phòng Nghỉ</h2>
                
                <form action="index.php" method="GET" class="row g-3 mb-5 p-4 rounded mx-0 border" style="background-color: var(--bs-tertiary-bg);">
                    <input type="hidden" name="page" value="danh-sach">
                    
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Từ khóa tìm kiếm</label>
                        <input type="text" name="search" class="form-control form-control-sm bg-transparent border-secondary text-body" placeholder="Nhập số phòng hoặc loại phòng..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Phân loại hạng phòng</label>
                        <select name="loai_phong" class="form-select form-select-sm bg-transparent border-secondary text-body">
                            <option value="" class="text-dark">-- Tất cả các hạng --</option>
                            <?php foreach ($cac_loai_phong as $lp): ?>
                                <option value="<?= htmlspecialchars($lp) ?>" <?= $filter_loai == $lp ? 'selected' : '' ?> class="text-dark"><?= htmlspecialchars($lp) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Mức giá niêm yết</label>
                        <select name="gia_phong" class="form-select form-select-sm bg-transparent border-secondary text-body">
                            <option value="" class="text-dark">-- Tất cả mức giá --</option>
                            <option value="duoi_1tr5" <?= $filter_gia == 'duoi_1tr5' ? 'selected' : '' ?> class="text-dark">Dưới 1.500.000 đ</option>
                            <option value="tren_1tr5" <?= $filter_gia == 'tren_1tr5' ? 'selected' : '' ?> class="text-dark">Từ 1.500.000 đ trở lên</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-gold btn-sm w-100 py-2 text-uppercase"><i class="bi bi-sliders"></i> Lọc Kết Quả</button>
                    </div>
                </form>

                <div class="row g-4">
                    <?php
                    if (count($phongs) > 0) {
                        foreach ($phongs as $row) {
                            $img = !empty($row['hinh_anh']) ? $row['hinh_anh'] : 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=600';
                            $status_badge = $row['trang_thai'] == 'trong' ? '<span class="badge badge-gold px-3 py-1">Trống</span>' : '<span class="badge bg-secondary px-3 py-1">Đang bận</span>';
                    ?>
                            <div class="col-md-4 scroll-reveal">
                                <div class="card h-100 d-flex flex-column">
                                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top room-img" alt="...">
                                    <div class="card-body d-flex flex-column p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <?= $status_badge ?>
                                            <small class="text-muted fw-bold">Phòng số: <?= htmlspecialchars($row['so_phong']) ?></small>
                                        </div>
                                        <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($row['loai_phong']) ?> Room</h4>
                                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                            <h5 class="text-gold fw-bold mb-0 fs-5"><?= number_format($row['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fw-light fs-6">/đêm</small></h5>
                                            <a href="index.php?page=chi-tiet&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-gold px-3">Chi Tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center text-muted my-5 py-5"><i class="bi bi-search fs-1 text-gold"></i><p class="mt-3 fs-5">Xin lỗi, không có phòng nào phù hợp với điều kiện tìm kiếm của bạn tại ALTF4.</p></div>';
                    }
                    ?>
                </div>
            </div>

        <?php
        } elseif ($page == 'chi-tiet') {
            // =======================================================
            // [GIAO DIỆN CHI TIẾT PHÒNG] - THÔNG TIN CHI TIẾT
            // =======================================================
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
                            <li class="breadcrumb-item"><a href="index.php" class="text-muted text-decoration-none">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="index.php?page=danh-sach" class="text-muted text-decoration-none">Danh sách phòng</a></li>
                            <li class="breadcrumb-item active text-gold fw-bold">Chi tiết phòng</li>
                        </ol>
                    </nav>

                    <div class="row g-5 mt-2">
                        <div class="col-md-7">
                            <img src="<?= htmlspecialchars($img) ?>" class="img-fluid rounded room-detail-img w-100" alt="Chi tiết phòng ALTF4">
                        </div>
                        
                        <h2 class="text-gold fw-bold mb-4"><?= number_format($room['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fs-6 fw-light">/ đêm lưu trú</small></h2>
                        
                        <h5 class="fw-bold text-uppercase small text-muted mb-3" style="letter-spacing: 0.5px;">Mô tả tiện ích phòng</h5>
                        <p class="text-muted" style="line-height: 1.7;">Không gian phòng nghỉ tinh tế được tối ưu hóa cho sự thoải mái của bạn. Trang bị hệ thống điều hòa thông minh, kết nối mạng không dây tốc độ cao, tủ két an toàn bảo mật, minibar và nội thất cao cấp mang chuẩn phong cách thượng lưu của hệ thống khách sạn ALTF4.</p>
                        
                        <h5 class="fw-bold text-uppercase small text-muted mt-4 mb-3" style="letter-spacing: 0.5px;">Thông số kỹ thuật phòng</h5>
                        <table class="table table-sm text-muted mt-2 border-top border-bottom">
                            <tr>
                                <td class="py-2 border-0"><i class="bi bi-door-open text-gold me-2"></i> Số hiệu phòng:</td>
                                <td class="py-2 text-end text-body fw-bold border-0"><?= htmlspecialchars($room['so_phong']) ?></td>
                            </tr>
                            <tr>
                                <td class="py-2 border-0"><i class="bi bi-tags text-gold me-2"></i> Phân cấp hạng phòng:</td>
                                <td class="py-2 text-end text-body fw-bold border-0"><?= htmlspecialchars($room['loai_phong']) ?></td>
                            </tr>
                            <tr>
                                <td class="py-2 border-0"><i class="bi bi-cash-stack text-gold me-2"></i> Giá niêm yết:</td>
                                <td class="py-2 text-end text-gold fw-bold border-0"><?= number_format($room['gia_phong'], 0, ',', '.') ?> đ</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="mailto:info@altf4hotel.com?subject=Yêu cầu đặt phòng số <?= $room['so_phong'] ?>" class="btn btn-gold btn-lg w-100 py-3 text-uppercase fs-6 <?= $room['trang_thai'] != 'trong' ? 'disabled btn-secondary text-white-50' : '' ?>">
                            <i class="bi bi-calendar2-check-fill me-2"></i> <?= $room['trang_thai'] == 'trong' ? 'Liên Hệ Đặt Phòng Trực Tuyến' : 'Phòng Hiện Đang Được Thuê' ?>
                        </a>
                        <div class="col-md-5 d-flex flex-column justify-content-between">
                            <div>
                                <h1 class="fw-bold text-body mb-2"><?= htmlspecialchars($room['loai_phong']) ?> Luxury Suite</h1>
                                <div class="mb-4 fs-6">
                                    <?php if ($room['trang_thai'] == 'trong'): ?>
                                        <span class="badge badge-gold px-3 py-2 text-uppercase">Sẵn Sàng</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-2 text-uppercase">Đang Được Thuê</span>
                                    <?php endif; ?>
                                    <span class="ms-3 text-muted">Vị trí phòng nghỉ: <strong class="text-body"><?= htmlspecialchars($room['so_phong']) ?></strong></span>
                                </div>
                                
                                <h2 class="text-gold fw-bold mb-4"><?= number_format($room['gia_phong'], 0, ',', '.') ?> đ <small class="text-muted fs-6 fw-light">/ đêm lưu trú</small></h2>
                                
                                <h5 class="fw-bold text-uppercase small text-muted mb-3" style="letter-spacing: 0.5px;">Mô tả tiện ích phòng</h5>
                                <p class="text-muted" style="line-height: 1.7;">Không gian phòng nghỉ tinh tế được tối ưu hóa cho sự thoải mái của bạn. Trang bị hệ thống điều hòa thông minh, kết nối mạng không dây tốc độ cao, tủ két an toàn bảo mật, minibar và nội thất cao cấp mang chuẩn phong cách thượng lưu của hệ thống khách sạn ALTF4.</p>
                                
                                <h5 class="fw-bold text-uppercase small text-muted mt-4 mb-3" style="letter-spacing: 0.5px;">Thông số kỹ thuật phòng</h5>
                                <table class="table table-sm text-muted mt-2 border-top border-bottom">
                                    <tr>
                                        <td class="py-2 border-0"><i class="bi bi-door-open text-gold me-2"></i> Số hiệu phòng:</td>
                                        <td class="py-2 text-end text-body fw-bold border-0"><?= htmlspecialchars($room['so_phong']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 border-0"><i class="bi bi-tags text-gold me-2"></i> Phân cấp hạng phòng:</td>
                                        <td class="py-2 text-end text-body fw-bold border-0"><?= htmlspecialchars($room['loai_phong']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 border-0"><i class="bi bi-cash-stack text-gold me-2"></i> Giá niêm yết:</td>
                                        <td class="py-2 text-end text-gold fw-bold border-0"><?= number_format($room['gia_phong'], 0, ',', '.') ?> đ</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mt-4">
                                <a href="mailto:info@altf4hotel.com?subject=Yêu cầu đặt phòng số <?= $room['so_phong'] ?>" class="btn btn-gold btn-lg w-100 py-3 text-uppercase fs-6 <?= $room['trang_thai'] != 'trong' ? 'disabled btn-secondary text-white-50' : '' ?>">
                                    <i class="bi bi-calendar2-check-fill me-2"></i> <?= $room['trang_thai'] == 'trong' ? 'Liên Hệ Đặt Phòng Trực Tuyến' : 'Phòng Hiện Đang Được Thuê' ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            } else {
                echo '<div class="container my-5 py-5 text-center text-muted"><i class="bi bi-exclamation-triangle fs-1 text-gold"></i><p class="mt-3 fs-5">Mã số phòng không tồn tại trên hệ thống dữ liệu ALTF4.</p><a href="index.php" class="btn btn-gold btn-sm mt-2 px-4">Quay lại Trang Chủ</a></div>';
            }
        }
        ?>

    </div> 
    
    <footer class="bg-body-tertiary text-body py-5 mt-5 border-top">
        <div class="container py-2">
            <div class="row g-4">
                <div class="col-md-5">
                    <h4 class="fw-bold text-gold mb-3"><i class="bi bi-building-haze"></i> ALTF4 HOTEL</h4>
                    <p class="text-muted small pr-md-5" style="line-height: 1.6;">Hệ thống quản lý khách sạn và giải pháp nghỉ dưỡng cao cấp đạt tiêu chuẩn quốc tế, mang tới trải nghiệm thượng lưu đích thực cho mọi khách hàng.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold text-uppercase small mb-3 text-gold" style="letter-spacing: 0.5px;">Tổng đài hỗ trợ</h5>
                    <p class="text-muted small mb-2"><i class="bi bi-geo-alt text-gold me-2"></i> 123 Đường Nguyễn Trãi, Quận 1, TP. HCM</p>
                    <p class="text-muted small mb-2"><i class="bi bi-telephone text-gold me-2"></i> Hotline: 0123.456.789</p>
                    <p class="text-muted small"><i class="bi bi-envelope text-gold me-2"></i> Email: info@altf4hotel.com</p>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold text-uppercase small mb-3 text-gold" style="letter-spacing: 0.5px;">Thông tin đồ án</h5>
                    <p class="text-muted small mb-0">© 2026 Học phần Lập trình Web.</p>
                    <p class="text-muted small">Đồ án kết thúc môn - Hệ thống Quản lý Khách sạn ALTF4.</p>
                </div>
            </div>
        </div>
    </footer>

    <button type="button" class="btn btn-gold rounded-circle position-fixed shadow" id="btnBackToTop" style="bottom: 40px; right: 40px; width: 50px; height: 50px; display: none; z-index: 9999;">
        <i class="bi bi-arrow-up fs-4 text-white"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
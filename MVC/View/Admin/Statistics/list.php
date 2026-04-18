<!DOCTYPE html>
<html>
<head>
    <title>Thống kê | Passo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">
    <style>
        body { background-color: #f0f2f5; }
        .stats-shell {
            background: linear-gradient(180deg, #ffffff 0%, #f5f8ff 100%);
        }
        .stat-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.12);
            transform: scale(0.7);
            opacity: 0;
            animation: popIn 0.7s ease forwards;
        }
        .stat-circle::before {
            content: '';
            position: absolute;
            inset: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }
        .stat-circle-content {
            position: relative;
            z-index: 1;
        }
        .stat-number {
            font-size: 2.2rem;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.96;
            margin-top: 0.25rem;
        }
        .circle-users { background: radial-gradient(circle at top left, #2b8cff, #1c5cff); animation-delay: 0.05s; }
        .circle-groups { background: radial-gradient(circle at top left, #39b56f, #13824a); animation-delay: 0.18s; }
        .circle-posts { background: radial-gradient(circle at top left, #ff8a3d, #e85d04); animation-delay: 0.31s; }
        .circle-categories { background: radial-gradient(circle at top left, #8e6cff, #5b34d6); animation-delay: 0.44s; }
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.72); }
            70% { opacity: 1; transform: scale(1.04); }
            100% { opacity: 1; transform: scale(1); }
        }
        .section-card {
            border: 0;
            box-shadow: 0 10px 24px rgba(16, 24, 40, 0.08);
        }
        .fade-up {
            opacity: 0;
            transform: translateY(16px);
            animation: fadeUp 0.6s ease forwards;
        }
        .fade-up.delay-1 { animation-delay: 0.08s; }
        .fade-up.delay-2 { animation-delay: 0.18s; }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 767.98px) {
            .stat-circle {
                width: 130px;
                height: 130px;
            }

            .stat-number {
                font-size: 1.9rem;
            }

            .stat-label {
                font-size: 0.82rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid" id="home-container">
        <div class="row" id="navbar">
            <div class="col-md-12 col-12 px-0">
                <?php include 'MVC/View/navbar.php'; ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-12 col-lg-2 mb-3 mb-lg-0" id="left-sidebar">
                <?php include 'MVC/View/leftsidebar.php'; ?>
            </div>

            <div class="col-12 col-lg-10" id="main-content">
                <div class="card section-card stats-shell rounded-lg">
                    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
                        <div class="fade-up">
                            <h4 class="mb-1 font-weight-bold text-danger">
                                <i class="fas fa-chart-pie"></i> Thống kê
                            </h4>
                            <p class="mb-0 text-muted">Tổng quan nhanh toàn hệ thống</p>
                        </div>
                    </div>

                    <div class="card-body py-4">
                        <div class="row justify-content-center">
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex justify-content-center">
                                <div class="stat-circle circle-users" data-target="<?= (int)($totalUsers ?? 0) ?>">
                                    <div class="stat-circle-content">
                                        <div class="stat-number">0</div>
                                        <div class="stat-label">Người dùng</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex justify-content-center">
                                <div class="stat-circle circle-groups" data-target="<?= (int)($totalGroups ?? 0) ?>">
                                    <div class="stat-circle-content">
                                        <div class="stat-number">0</div>
                                        <div class="stat-label">Nhóm</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex justify-content-center">
                                <div class="stat-circle circle-posts" data-target="<?= (int)($totalPosts ?? 0) ?>">
                                    <div class="stat-circle-content">
                                        <div class="stat-number">0</div>
                                        <div class="stat-label">Bài đăng</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex justify-content-center">
                                <div class="stat-circle circle-categories" data-target="<?= (int)($totalCategories ?? 0) ?>">
                                    <div class="stat-circle-content">
                                        <div class="stat-number">0</div>
                                        <div class="stat-label">Danh mục</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="footer">
            <div class="col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const circles = document.querySelectorAll('.stat-circle');

            circles.forEach(function(circle) {
                const target = parseInt(circle.getAttribute('data-target') || '0', 10);
                const output = circle.querySelector('.stat-number');
                const duration = 1000;
                const start = 0;
                const startTime = performance.now();

                function animateNumber(now) {
                    const progress = Math.min((now - startTime) / duration, 1);
                    const value = Math.floor(start + (target - start) * progress);
                    output.textContent = value.toLocaleString('vi-VN');

                    if (progress < 1) {
                        requestAnimationFrame(animateNumber);
                    } else {
                        output.textContent = target.toLocaleString('vi-VN');
                    }
                }

                requestAnimationFrame(animateNumber);
            });
        });
    </script>
</body>
</html>

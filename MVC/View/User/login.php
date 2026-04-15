<!DOCTYPE html>
<html>
<head>
    <title>Passo - Đăng nhập hoặc Đăng ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; } 
        /* Căn chỉnh logo hình ảnh */
        .fb-logo-img { width: 300px; margin-left: -20px; margin-bottom: 10px; }
        .fb-tagline { font-size: 1.75rem; font-weight: 400; line-height: 32px; width: 500px; color: #1c1e21; }
        .login-card { border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1); padding: 20px; background: #fff; }
        .btn-login { background-color: #1877f2; font-size: 1.25rem; font-weight: bold; padding: 10px; border: none; }
        .btn-register { background-color: #42b72a; font-size: 1.1rem; font-weight: bold; padding: 10px 20px; border-radius: 6px; color: white; border: none; display: inline-block; }
        .btn-register:hover { background-color: #36a420; color: white; text-decoration: none; }
        .flash-message {
            border-radius: 6px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-weight: 600;
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .captcha-status {
            display: block;
            margin-top: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #6c757d;
        }
        .captcha-status.is-expired {
            color: #c82333;
        }
        .captcha-text {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-left: 4px;
            font-family: "Trebuchet MS", "Arial Black", sans-serif;
            letter-spacing: 1px;
        }
        .captcha-text .captcha-char {
            display: inline-block;
            font-weight: 700;
            color: #1877f2;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
            font-style: italic;
            transform-origin: center center;
        }
        .captcha-text .captcha-char:nth-child(3n) {
            font-size: 1.18rem;
            transform: rotate(60deg);
        }
    </style>
</head>
<body>
    <?php
        $captchaQuestion = (string)($_SESSION['login_captcha_question'] ?? 'A B C D E');
        $captchaGeneratedAt = (int)($_SESSION['login_captcha_generated_at'] ?? 0);
        $captchaTtl = (int)($_SESSION['login_captcha_ttl'] ?? 30);
        $captchaExpiresAt = $captchaGeneratedAt > 0 ? $captchaGeneratedAt + $captchaTtl : 0;
        $captchaLetters = array_values(array_filter(explode(' ', $captchaQuestion), static function ($part) {
            return $part !== '';
        }));
    ?>

    <div class="container d-flex align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-between">
            
            <div class="col-md-6 d-flex flex-column justify-content-center pb-5">
                <img src="Materials/Picture/Passo.png" alt="Passo Logo" class="fb-logo-img">
                <h2 class="fb-tagline">Chào mừng đến với Passo, nơi đồ second hand tìm được chủ mới dễ dàng hơn.</h2>
            </div>
            
            <div class="col-md-5">
                <div class="login-card">
                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="flash-message" role="alert">
                            <?= htmlspecialchars($_SESSION['flash_message']) ?>
                        </div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>

                    <form action="index.php?controller=user&action=authenticate" method="POST">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="Email hoặc tên đăng nhập" required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Mật khẩu" required>
                        </div>
                        <div class="form-group">
                            <label for="captcha_answer" class="mb-1" style="font-weight: 600; color: #1c1e21;">
                                Xác nhận bạn không phải robot:
                                <span class="captcha-text" aria-label="<?= htmlspecialchars($captchaQuestion) ?>">
                                    <?php foreach ($captchaLetters as $letter): ?>
                                        <span class="captcha-char"><?= htmlspecialchars($letter) ?></span>
                                    <?php endforeach; ?>
                                </span>
                            </label>
                            <small id="captcha_status" class="captcha-status" data-expires-at="<?= htmlspecialchars((string)$captchaExpiresAt) ?>" data-ttl="<?= htmlspecialchars((string)$captchaTtl) ?>">
                                CAPTCHA hết hạn sau <?= htmlspecialchars((string)$captchaTtl) ?> giây.
                            </small>
                            <input id="captcha_answer" type="text" name="captcha_answer" class="form-control form-control-lg" placeholder="Nhập lại chuỗi ký tự" required>
                        </div>
                        <button id="login_submit_btn" type="submit" class="btn btn-login btn-primary btn-block shadow-none">Đăng nhập</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="index.php?controller=user&action=googleLogin" class="btn btn-outline-primary btn-block" style="font-weight: 600;">
                            Đăng nhập bằng Google
                        </a>
                    </div>
                    
                    <div class="text-center mt-3 mb-3">
                        <a href="index.php?controller=user&action=forgotPassword" style="text-decoration: none; color: #1877f2; font-size: 14px;">Quên mật khẩu?</a>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center mt-4 mb-2">
                        <a href="index.php?controller=user&action=register" class="btn btn-register">Tạo tài khoản mới</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const statusEl = document.getElementById('captcha_status');
            const submitBtn = document.getElementById('login_submit_btn');
            const expiresAt = Number(statusEl?.dataset.expiresAt || 0);

            if (!statusEl || !submitBtn || !expiresAt) {
                return;
            }

            const updateStatus = () => {
                const remainingSeconds = Math.max(0, Math.ceil((expiresAt * 1000 - Date.now()) / 1000));

                if (remainingSeconds <= 0) {
                    statusEl.textContent = 'CAPTCHA đã hết hạn. Vui lòng tải lại trang để lấy mã mới.';
                    statusEl.classList.add('is-expired');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'CAPTCHA đã hết hạn';
                    return;
                }

                statusEl.textContent = 'CAPTCHA hết hạn sau ' + remainingSeconds + ' giây.';
            };

            updateStatus();

            const timerId = setInterval(() => {
                const remainingSeconds = Math.max(0, Math.ceil((expiresAt * 1000 - Date.now()) / 1000));

                if (remainingSeconds <= 0) {
                    clearInterval(timerId);
                    statusEl.textContent = 'CAPTCHA đã hết hạn. Vui lòng tải lại trang để lấy mã mới.';
                    statusEl.classList.add('is-expired');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'CAPTCHA đã hết hạn';
                    return;
                }

                statusEl.textContent = 'CAPTCHA hết hạn sau ' + remainingSeconds + ' giây.';
            }, 1000);
        }());
    </script>
</body>
</html>
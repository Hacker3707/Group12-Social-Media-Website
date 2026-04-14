<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="CSS/profile.css">
</head>

<body>
    <div class="container-fluid" id="profile-container">

        <div class="row" id="navbar">
            <div class="col-md-12 col-12">
                <?php include 'navbar.php'; ?>
            </div>
        </div>

        <!-- Banner -->
        <div class="row">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12" id="profile-banner">
                Placeholder for Banner.
            </div>
            <div class="col-md-2 col-12"></div>
        </div>

        <!-- Profile Info -->
        <div class="row">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12" id="profile-info">
                <div class="d-flex align-items-center flex-wrap">

                    <img src="<?= !empty($user['Avatar']) ? htmlspecialchars($user['Avatar']) : '../../Materials/Picture/Passo.png' ?>"
                         class="rounded-circle" width="100" height="100"
                         alt="Profile Picture" style="object-fit:cover;">

                    <div class="ml-3 flex-grow-1">
                        <h2><?= htmlspecialchars($user['Username'] ?? 'Username') ?></h2>
                        <p class="mb-0 text-muted">
                            <?= !empty($user['Bio']) ? htmlspecialchars($user['Bio']) : 'Chưa có bio.' ?>
                        </p>
                    </div>

                    <div class="ml-auto d-flex gap-2" style="gap:8px;">
                        <?php
                        $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] == ($user['UserID'] ?? null);
                        $isLoggedIn = isset($_SESSION['user_id']);
                        ?>

                        <?php if ($isOwner): ?>
                            <!-- Chủ trang: nút Edit Profile -->
                            <a href="index.php?controller=user&action=edit&id=<?= $user['UserID'] ?>"
                               class="btn btn-outline-primary">
                                <i class="bi bi-pencil-square"></i> Edit Profile
                            </a>

                        <?php elseif ($isLoggedIn): ?>
                            <!-- Người khác xem: nút Nhắn tin -->
                            <button class="btn btn-primary"
                                    onclick="startChat(<?= (int)$user['UserID'] ?>, '<?= htmlspecialchars($user['Username'], ENT_QUOTES) ?>')">
                                <i class="bi bi-chat-dots-fill"></i> Nhắn tin
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <div class="col-md-2 col-12"></div>
        </div>

        <!-- Profile navbar -->
        <div class="row">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12" id="profile-navbar">
                Placeholder for Profile Navigation.
            </div>
            <div class="col-md-2 col-12"></div>
        </div>

        <!-- Main content -->
        <div class="row" id="middle-content">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12" id="main-content">
                <h1>Profile Page</h1>
                <p>This is the profile page. You can view and edit your profile information here.</p>
            </div>
            <div class="col-md-2 col-12"></div>
        </div>

    </div>

    <!-- Chat Widget -->
    <?php include_once __DIR__ . '/chat_widget.php'; ?>

</body>
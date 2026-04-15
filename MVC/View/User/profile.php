<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($user['Username']) ?> | Passo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">

    <style>
        body { background-color: #f0f2f5; }
        .profile-header { background: #fff; padding-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .profile-name { font-size: 2rem; font-weight: 700; margin-bottom: 0; }
        .profile-bio { color: #65676b; font-size: 1.1rem; }
        .content-section { margin-top: 20px; }
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-title { font-weight: 700; font-size: 1.25rem; }
        .follow-btn {
            background: #1877f2; color: white; border-radius: 20px;
            padding: 6px 16px; font-weight: 600; border: none; transition: 0.2s;
        }
        .follow-btn:hover { background: #166fe5; }
        .follow-btn.following { background: #e4e6eb; color: black; }
        .follow-btn.following:hover { background: #d8dadf; }
        .chat-btn {
            background: #0084ff; color: white; border-radius: 20px;
            padding: 6px 16px; font-weight: 600; border: none; transition: 0.2s; cursor: pointer;
        }
        .chat-btn:hover { background: #006edb; }
        .avatar-container {
            width: 168px; height: 168px; border-radius: 50%; overflow: hidden;
            position: relative; margin-top: 24px; margin-bottom: 15px;
            margin-left: auto; margin-right: auto; background-color: #fff;
        }
        .avatar-container img {
            width: 100%; height: 100%; object-fit: cover;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2); border: 4px solid #fff;
        }
        .profile-tab-link { cursor: pointer; }
        .followers-list .follower-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
            color: #222;
        }
        .followers-list .follower-item:hover { background-color: #f8f9fa; }
        .followers-list .follower-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="row" id="navbar">
        <div class="col-md-12 col-12">
            <?php include 'MVC/View/navbar.php'; ?>
        </div>
    </div>

    <div class="profile-header" style="background: linear-gradient(225deg, #feffe9, rgb(238, 246, 255));">
        <div class="container">
            <div class="avatar-container">
                <img class="img-fluid" src="<?= !empty($user['AvatarFP']) ? $user['AvatarFP'] : 'https://via.placeholder.com/168/007bff/ffffff?text='.strtoupper(substr($user['Username'], 0, 1)) ?>" alt="Avatar">
            </div>
            
            <div class="text-center">
                <h1 class="profile-name"><?= htmlspecialchars($user['Username']) ?></h1>
                <p id="followerCount" class="text-muted">
                    <?= $followerCount ?? 0 ?> người theo dõi
                </p>
                <p class="profile-bio"><?= !empty($user['Bio']) ? htmlspecialchars($user['Bio']) : 'Chưa có tiểu sử.' ?></p>
                
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['UserID']): ?>
                    <!-- Chủ trang: chỉ hiện Edit -->
                    <a href="index.php?controller=user&action=edit&id=<?= $user['UserID'] ?>" class="btn btn-light font-weight-bold mt-2">
                        <i class="fas fa-pen"></i> Chỉnh sửa trang cá nhân
                    </a>

                <?php elseif(isset($_SESSION['user_id'])): ?>
                    <!-- Người khác xem: Follow + Nhắn tin -->
                    <?php $isFollowing = $isFollowing ?? false; ?>
                    <button 
                        id="followBtn"
                        class="follow-btn mt-2 <?= $isFollowing ? 'following' : '' ?>"
                        data-user-id="<?= $user['UserID'] ?>">
                        <?= $isFollowing ? 'Đang theo dõi' : 'Theo dõi' ?>
                    </button>

                    <button 
                        class="chat-btn mt-2 ml-2"
                        onclick="startChat(<?= (int)$user['UserID'] ?>, '<?= htmlspecialchars($user['Username'], ENT_QUOTES) ?>')">
                        💬 Nhắn tin
                    </button>
                <?php endif; ?>
            </div>
            
            <hr class="mt-4 mb-0">
            <ul class="nav nav-pills justify-content-center mt-2 font-weight-bold text-muted">
                <li class="nav-item"><a class="nav-link active profile-tab-link" id="tab-posts" data-target="posts-section">Bài viết</a></li>
                <li class="nav-item"><a class="nav-link text-dark profile-tab-link" id="tab-followers" data-target="followers-section">Người theo dõi</a></li>
            </ul>
        </div>
    </div>

    <div class="container content-section" style="background-color: #e6efff; /* test màu */
    padding: 20px;
    border-radius: 10px;">
        <div class="card card-custom p-3 mb-3">
            <h5 class="card-title">Giới thiệu tổng</h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><strong>Điện thoại:</strong> <?= !empty($user['Phone']) ? htmlspecialchars($user['Phone']) : 'Đang cập nhật' ?></li>
                <li class="mb-2"><strong>Tiểu sử:</strong> <?= !empty($user['Bio']) ? htmlspecialchars($user['Bio']) : 'Chưa có tiểu sử.' ?></li>
                <li class="mb-0"><strong>Trạng thái:</strong> <?= ucfirst($user['AccountStatus']) ?></li>
            </ul>
        </div>

        <div id="posts-section">
            <div class="col-md-12 px-0">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['UserID']): ?>
                <div class="card card-custom p-3 mb-3">
                    <div class="d-flex align-items-center">
                        <img src="<?= !empty($user['AvatarFP']) ? $user['AvatarFP'] : 'https://via.placeholder.com/40' ?>" class="rounded-circle mr-2" width="40" height="40">
                        <a href="index.php?controller=post&action=showCreateForm" class="form-control rounded-pill bg-light text-muted text-left" style="border:none; text-decoration:none; line-height: 1.6;">
                            <?= htmlspecialchars($user['Username']) ?> oi, ban dang nghi gi the?
                        </a>
                        <a href="index.php?controller=post&action=showCreateForm" class="btn btn-primary ml-2">Create Post</a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($posts)): ?>
                    <?php include_once __DIR__ . "/../postview.php"; ?>
                <?php else: ?>
                    <div class="card card-custom p-3 text-center">
                        <h5 class="text-muted mt-3 mb-3">Chua co bai viet nao de hien thi.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="followers-section" style="display:none;">
            <div class="card card-custom p-3 followers-list" id="followersListWrap">
                <h5 class="card-title mb-2">Danh sách người theo dõi</h5>
                <div id="followersList" class="text-muted">Bam vao tab Nguoi theo doi de tai du lieu...</div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        if ($("#followBtn").length === 0) return;
        let button = $("#followBtn");

        button.hover(
            function(){ if(button.hasClass("following")) button.text("Bỏ theo dõi"); },
            function(){ if(button.hasClass("following")) button.text("Đang theo dõi"); }
        );

        button.click(function(){
            let userId = parseInt(button.data("user-id"));
            if(!userId){ alert("Không lấy được userId!"); return; }

            let isFollowing = button.hasClass("following");
            let action = isFollowing ? "unfollow" : "follow";
            button.prop("disabled", true).text("Đang xử lý...");

            $.ajax({
                url: "index.php?controller=follow&action=" + action,
                method: "POST",
                data: { following_id: userId },
                success: function(response){
                    if(response.status === "followed" || response.status === "already"){
                        button.addClass("following").text("Đang theo dõi");
                    } else if(response.status === "unfollowed"){
                        button.removeClass("following").text("Theo dõi");
                    } else if(response.status === "error"){
                        alert(response.message);
                    }
                    if(response.count !== undefined){
                        $("#followerCount").text(response.count + " người theo dõi");
                    }
                    button.prop("disabled", false);
                },
                error: function(xhr){
                    console.error(xhr);
                    alert("Lỗi kết nối!");
                    button.prop("disabled", false);
                }
            });
        });
    });

    $(document).ready(function(){
        function switchTab(target){
            $(".profile-tab-link").removeClass("active text-dark");
            $("#tab-" + target).addClass("active");

            if(target === "posts"){
                $("#posts-section").show();
                $("#followers-section").hide();
            } else {
                $("#posts-section").hide();
                $("#followers-section").show();
            }
        }

        function loadFollowers(){
            const userId = <?= (int)$user['UserID'] ?>;
            const list = $("#followersList");
            list.text("Dang tai...");

            $.get("index.php?controller=follow&action=getFollowersJson&user_id=" + userId, function(response){
                if(!response || response.status !== "success"){
                    list.text("Khong the tai danh sach nguoi theo doi.");
                    return;
                }

                if(!response.followers || response.followers.length === 0){
                    list.text("Chua co nguoi theo doi nao.");
                    return;
                }

                let html = "";
                response.followers.forEach(function(follower){
                    const avatar = follower.AvatarFP && follower.AvatarFP !== ""
                        ? follower.AvatarFP
                        : "https://via.placeholder.com/40";

                    html += `
                        <a class="follower-item" href="index.php?controller=user&action=profile&id=${follower.UserID}">
                            <img class="follower-avatar" src="${avatar}" alt="${follower.Username}">
                            <span>${follower.Username}</span>
                        </a>
                    `;
                });

                list.html(html);
            }, "json").fail(function(){
                list.text("Loi ket noi khi tai nguoi theo doi.");
            });
        }

        $("#tab-posts").on("click", function(){
            switchTab("posts");
        });

        $("#tab-followers").on("click", function(){
            switchTab("followers");
            loadFollowers();
        });
    });
    </script>

    <!-- Chat Widget (load từ root) -->
    <?php include_once "MVC/View/chat_widget.php"; ?>

</body>
</html>
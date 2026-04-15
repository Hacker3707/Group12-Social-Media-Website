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
        /* ── Page ── */
        body { background-color: #f0f2f5; }
        .profile-header { background: #fff; padding-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .profile-name { font-size: 2rem; font-weight: 700; margin-bottom: 0; }
        .profile-bio { color: #65676b; font-size: 1.1rem; }
        .content-section { margin-top: 20px; }
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-title { font-weight: 700; font-size: 1.25rem; }
        .follow-btn { background: #1877f2; color: white; border-radius: 20px; padding: 6px 16px; font-weight: 600; border: none; transition: 0.2s; }
        .follow-btn:hover { background: #166fe5; }
        .follow-btn.following { background: #e4e6eb; color: black; }
        .follow-btn.following:hover { background: #d8dadf; }
        .chat-btn { background: #0084ff; color: white; border-radius: 20px; padding: 6px 16px; font-weight: 600; border: none; transition: 0.2s; cursor: pointer; }
        .chat-btn:hover { background: #006edb; }
        .avatar-container {
            width: 168px; height: 168px; border-radius: 50%; overflow: hidden;
            position: relative; margin-top: 24px; margin-bottom: 15px;
            margin-left: auto; margin-right: auto; background-color: #fff;
        .avatar-container img { width: 100%; height: 100%; object-fit: cover; box-shadow: 0 1px 3px rgba(0,0,0,0.2); border: 4px solid #fff; }
        /* ══════════════════════════════════════════
           MESSENGER MODAL
        ══════════════════════════════════════════ */
        #mess-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }
        #mess-overlay.open { display: flex; }

        #mess-box {
            width: 860px;
            max-width: 96vw;
            height: 560px;
            max-height: 92vh;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 12px 48px rgba(0,0,0,.28);
            animation: messIn .22s ease;
        }
        @keyframes messIn {
            from { transform: scale(.92); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }

        /* ── Sidebar ── */
        #mess-sidebar {
            width: 280px;
            min-width: 280px;
            border-right: 1px solid #e4e6eb;
            display: flex;
            flex-direction: column;
            background: #fff;
        }
        #mess-sidebar-hdr {
            padding: 16px 16px 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        #mess-sidebar-hdr h5 { font-weight: 700; font-size: 1.15rem; margin: 0 0 10px; }
        #mess-search {
            width: 100%;
            border: none;
            background: #f0f2f5;
            border-radius: 20px;
            padding: 7px 14px;
            font-size: 13.5px;
            outline: none;
        }
        #mess-conv-list { overflow-y: auto; flex: 1; }
        .mconv-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            cursor: pointer;
            border-radius: 10px;
            margin: 2px 6px;
            transition: background .13s;
        }
        .mconv-item:hover { background: #f0f2f5; }
        .mconv-item.active { background: #e7f3ff; }
        .mconv-av {
            width: 44px; height: 44px; border-radius: 50%;
            background: #dde3ec; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #555; font-size: 18px; overflow: hidden;
        }
        .mconv-av img { width: 100%; height: 100%; object-fit: cover; }
        .mconv-info { flex: 1; overflow: hidden; }
        .mconv-name { font-weight: 600; font-size: 14px; }
        .mconv-last { font-size: 12px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mconv-unread { background: #0084ff; color: #fff; border-radius: 10px; padding: 2px 7px; font-size: 11px; font-weight: 700; flex-shrink: 0; }
        #mess-conv-empty { padding: 30px; text-align: center; color: #aaa; font-size: 13px; }

        /* ── Main chat area ── */
        #mess-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        /* ── Chat header ── */
        #mess-chat-hdr {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid #e4e6eb;
            background: #fff;
        }
        #mess-chat-hdr .hdr-av {
            width: 38px; height: 38px; border-radius: 50%;
            background: #dde3ec; display: flex; align-items: center;
            justify-content: center; font-weight: 700; overflow: hidden;
        }
        #mess-chat-hdr .hdr-av img { width: 100%; height: 100%; object-fit: cover; }
        #mess-chat-hdr .hdr-name { font-weight: 700; font-size: 15px; flex: 1; }
        #mess-close-btn {
            background: none; border: none; font-size: 22px;
            color: #888; cursor: pointer; line-height: 1; padding: 0 4px;
        }
        #mess-close-btn:hover { color: #333; }

        /* ── Empty state ── */
        #mess-empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #aaa;
        }
        #mess-empty-state .big-icon { font-size: 56px; margin-bottom: 12px; }
        #mess-empty-state p { font-size: 15px; }

        /* ── Msg list ── */
        #mess-msg-list {
            flex: 1;
            overflow-y: auto;
            padding: 14px 14px 6px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: #f0f4f8;
        }

        /* ── Bubble ── */
        .mmsg-row { display: flex; flex-direction: column; }
        .mmsg-row.me  { align-items: flex-end; }
        .mmsg-row.them { align-items: flex-start; }
        .mbubble {
            max-width: 68%;
            padding: 9px 14px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.45;
            word-break: break-word;
            position: relative;
            cursor: pointer;
        }
        .mbubble.me   { background: #0084ff; color: #fff; border-bottom-right-radius: 5px; }
        .mbubble.them { background: #fff; color: #222; border-bottom-left-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,.09); }
        .mbubble img.mchat-img { max-width: 200px; max-height: 200px; border-radius: 12px; display: block; cursor: zoom-in; }
        .mbubble .mbmeta { font-size: 10px; margin-top: 3px; opacity: .6; display: block; }
        .mbubble .mbreact { position: absolute; bottom: -10px; right: 8px; font-size: 14px; background: #fff; border-radius: 10px; padding: 0 3px; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        .mbubble.me .mbreact { right: auto; left: 8px; }

        /* ── Emoji picker ── */
        .memoji-picker {
            display: none;
            position: fixed;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.2);
            padding: 8px 10px;
            gap: 4px;
            flex-wrap: wrap;
            width: 172px;
            z-index: 10010;
        }
        .memoji-picker.show { display: flex; }
        .memoji-picker span { font-size: 22px; cursor: pointer; padding: 2px; transition: transform .1s; }
        .memoji-picker span:hover { transform: scale(1.3); }

        /* ── Input ── */
        #mess-input-area {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-top: 1px solid #eee;
            gap: 8px;
            background: #fff;
        }
        #mess-text-input {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 22px;
            padding: 9px 16px;
            font-size: 14px;
            outline: none;
            background: #f0f2f5;
            transition: border-color .15s;
        }
        #mess-text-input:focus { border-color: #0084ff; background: #fff; }
        .mess-img-label { cursor: pointer; font-size: 22px; color: #0084ff; padding: 4px; }
        #mess-img-file { display: none; }
        #mess-send-btn {
            background: #0084ff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 38px; height: 38px;
            font-size: 18px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background .15s;
        }
        #mess-send-btn:hover { background: #006edb; }

        /* ── Lightbox ── */
        #mess-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.85);
            z-index: 99999;
            align-items: center;
            justify-content: center;
        }
        #mess-lightbox.show { display: flex; }
        #mess-lightbox img { max-width: 90vw; max-height: 90vh; border-radius: 10px; }
        #mess-lightbox .lb-x { position: absolute; top: 18px; right: 24px; color: #fff; font-size: 34px; cursor: pointer; }

        /* ── Responsive ── */
        @media(max-width:640px) {
            #mess-sidebar { width: 200px; min-width: 200px; }
        }
        @media(max-width:480px) {
            #mess-sidebar { display: none; }
            #mess-box { width: 100vw; height: 100vh; border-radius: 0; }
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
                <img class="img-fluid"
                     src="<?= !empty($user['AvatarFP']) ? $user['AvatarFP'] : 'https://via.placeholder.com/168/007bff/ffffff?text='.strtoupper(substr($user['Username'], 0, 1)) ?>"
                     alt="Avatar">
            </div>
            
            <div class="text-center">
                <h1 class="profile-name"><?= htmlspecialchars($user['Username']) ?></h1>
                <p id="followerCount" class="text-muted">
                    <?= $followerCount ?? 0 ?> người theo dõi
                </p>
                <p class="profile-bio"><?= !empty($user['Bio']) ? htmlspecialchars($user['Bio']) : 'Chưa có tiểu sử.' ?></p>
                
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['UserID']): ?>
                    <a href="index.php?controller=user&action=edit&id=<?= $user['UserID'] ?>"
                       class="btn btn-light font-weight-bold mt-2">
                        <i class="fas fa-pen"></i> Chỉnh sửa trang cá nhân
                    </a>
                    <!-- Chủ trang cũng có thể mở Messenger của mình -->
                    <button class="chat-btn mt-2 ml-2" onclick="openMessenger()">
                        💬 Tin nhắn
                    </button>

                <?php elseif(isset($_SESSION['user_id'])): ?>
                    <?php $isFollowing = $isFollowing ?? false; ?>
                    <button id="followBtn"
                            class="follow-btn mt-2 <?= $isFollowing ? 'following' : '' ?>"
                            data-user-id="<?= $user['UserID'] ?>">
                        <?= $isFollowing ? 'Đang theo dõi' : 'Theo dõi' ?>
                    </button>
                    <!-- Mở Messenger và focus thẳng vào cuộc trò chuyện với user này -->
                    <button class="chat-btn mt-2 ml-2"
                            onclick="openMessengerWith(<?= (int)$user['UserID'] ?>, '<?= htmlspecialchars($user['Username'], ENT_QUOTES) ?>')">
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

    <!-- ═══════════════════════════════════════════════
         MESSENGER MODAL
    ═══════════════════════════════════════════════ -->
    <?php if(isset($_SESSION['user_id'])): ?>

    <div id="mess-overlay" onclick="overlayClick(event)">
      <div id="mess-box">

        <!-- Sidebar: danh sách hội thoại -->
        <div id="mess-sidebar">
          <div id="mess-sidebar-hdr">
            <h5>💬 Tin nhắn</h5>
            <input id="mess-search" type="text" placeholder="🔍 Tìm kiếm..." oninput="filterConvs(this.value)">
          </div>
          <div id="mess-conv-list">
            <div id="mess-conv-empty">Đang tải...</div>
          </div>
        </div>

        <!-- Main area -->
        <div id="mess-main">

          <!-- Header (hiển thị khi đang chat) -->
          <div id="mess-chat-hdr" style="display:none;">
            <div class="hdr-av" id="mess-hdr-av">?</div>
            <span class="hdr-name" id="mess-hdr-name">...</span>
            <button id="mess-close-btn" onclick="closeMessenger()">×</button>
          </div>

          <!-- Nút đóng khi chưa chọn hội thoại -->
          <div id="mess-top-close" style="display:flex; justify-content:flex-end; padding:10px 12px;">
            <button style="background:none;border:none;font-size:22px;color:#888;cursor:pointer;" onclick="closeMessenger()">×</button>
          </div>

          <!-- Empty state -->
          <div id="mess-empty-state">
            <div class="big-icon">💬</div>
            <p>Chọn một cuộc trò chuyện để bắt đầu</p>
          </div>

          <!-- Msg list (ẩn ban đầu) -->
          <div id="mess-msg-list" style="display:none;"></div>

          <!-- Input area (ẩn ban đầu) -->
          <div id="mess-input-area" style="display:none;">
            <label class="mess-img-label" title="Gửi ảnh">
              🖼️<input type="file" id="mess-img-file" accept="image/*" onchange="messSendImage(this)">
            </label>
            <input id="mess-text-input" type="text" placeholder="Aa"
                   autocomplete="off" onkeydown="if(event.key==='Enter')messSendMsg()">
            <button id="mess-send-btn" onclick="messSendMsg()">➤</button>
          </div>

        </div>
      </div>
    </div>

    <!-- Emoji picker -->
    <div class="memoji-picker" id="mess-emoji-picker">
        <?php foreach(['❤️','😂','😮','😢','😡','👍'] as $e): ?>
            <span onclick="messPickEmoji('<?= $e ?>')"><?= $e ?></span>
        <?php endforeach; ?>
    </div>

    <!-- Lightbox -->
    <div id="mess-lightbox" onclick="messCloseLightbox()">
        <span class="lb-x">×</span>
        <img id="mess-lb-img" src="" alt="">
    </div>

    <script>
    const _MESS_ME = <?= (int)$_SESSION['user_id'] ?>;
    let _mConvId     = null;
    let _mLastMsgId  = 0;
    let _mPollTimer  = null;
    let _mEmojiTgt   = null;
    let _mAllConvs   = [];   // cache để filter
    let _mActiveItem = null;

    /* ── Open / Close ───────────────────────────────────── */
    function openMessenger() {
        document.getElementById('mess-overlay').classList.add('open');
        messLoadConvs();
    }

    function openMessengerWith(uid, uname) {
        document.getElementById('mess-overlay').classList.add('open');
        messLoadConvs(() => messOpenChat(uid, uname));
    }

    function closeMessenger() {
        document.getElementById('mess-overlay').classList.remove('open');
        messStopPoll();
        hideMessEmojiPicker();
    }

    function overlayClick(e) {
        if (e.target === document.getElementById('mess-overlay')) closeMessenger();
    }

    /* ── Load conversations ─────────────────────────────── */
    function messLoadConvs(callback) {
        fetch('index.php?controller=chat&action=conversations')
            .then(r => r.json())
            .then(d => {
                _mAllConvs = d.conversations || [];
                messRenderConvList(_mAllConvs);
                if (callback) callback();
            });
    }

    function messRenderConvList(list) {
        const box = document.getElementById('mess-conv-list');
        if (!list || list.length === 0) {
            box.innerHTML = '<div id="mess-conv-empty">Chưa có tin nhắn nào</div>';
            return;
        }
        box.innerHTML = list.map(c => {
            const ini = (c.OtherUsername || '?')[0].toUpperCase();
            const avInner = c.OtherAvatar
                ? `<img src="${c.OtherAvatar}" alt="">`
                : ini;
            const badge = c.UnreadCount > 0
                ? `<span class="mconv-unread">${c.UnreadCount}</span>` : '';
            const lastTxt = c.LastMessage
                ? messEsc(c.LastMessage).substring(0, 36)
                : (c.LastImage ? '🖼️ Ảnh' : 'Bắt đầu trò chuyện');
            return `<div class="mconv-item" id="mconv-${c.OtherUserID}"
                        onclick="messOpenChat(${c.OtherUserID},'${messEsc(c.OtherUsername)}')">
                <div class="mconv-av">${avInner}</div>
                <div class="mconv-info">
                    <div class="mconv-name">${messEsc(c.OtherUsername)}</div>
                    <div class="mconv-last">${lastTxt}</div>
                </div>
                ${badge}
            </div>`;
        }).join('');
    }

    function filterConvs(q) {
        const filtered = q
            ? _mAllConvs.filter(c => c.OtherUsername.toLowerCase().includes(q.toLowerCase()))
            : _mAllConvs;
        messRenderConvList(filtered);
    }

    /* ── Open 1-1 chat ──────────────────────────────────── */
    function messOpenChat(uid, uname) {
        // Highlight active conv
        if (_mActiveItem) _mActiveItem.classList.remove('active');
        const item = document.getElementById('mconv-' + uid);
        if (item) { item.classList.add('active'); _mActiveItem = item; }

        // Show header, hide top-close
        document.getElementById('mess-chat-hdr').style.display = 'flex';
        document.getElementById('mess-top-close').style.display = 'none';

        // Update header name/avatar
        document.getElementById('mess-hdr-name').textContent = uname;
        const hdrAv = document.getElementById('mess-hdr-av');
        hdrAv.textContent = uname[0].toUpperCase();

        // Show msg + input, hide empty
        document.getElementById('mess-empty-state').style.display = 'none';
        document.getElementById('mess-msg-list').style.display = 'flex';
        document.getElementById('mess-input-area').style.display = 'flex';
        document.getElementById('mess-msg-list').innerHTML = '';
        document.getElementById('mess-text-input').focus();

        _mConvId = null; _mLastMsgId = 0;
        messStopPoll();

        fetch(`index.php?controller=chat&action=open&user_id=${uid}`)
            .then(r => r.json())
            .then(d => {
                if (d.status !== 'ok') return;
                _mConvId = d.conversation_id;

                // Update header avatar if available
                if (d.other_user.avatar) {
                    hdrAv.innerHTML = `<img src="${d.other_user.avatar}" alt="">`;
                }

                messRenderMsgs(d.messages);
                if (d.messages.length > 0) {
                    _mLastMsgId = d.messages[d.messages.length - 1].MessageID;
                }
                messStartPoll();
            });
    }

    /* ── Render messages ────────────────────────────────── */
    function messRenderMsgs(msgs) {
        const box = document.getElementById('mess-msg-list');
        msgs.forEach(m => {
            const isMe = parseInt(m.SenderID) === _MESS_ME;
            const row  = document.createElement('div');
            row.className = 'mmsg-row ' + (isMe ? 'me' : 'them');

            let inner = '';
            if (m.Content)   inner += `<span>${messEsc(m.Content)}</span>`;
            if (m.ImagePath) inner += `<img class="mchat-img" src="${m.ImagePath}" alt="ảnh" onclick="messOpenLightbox('${m.ImagePath}')">`;
            inner += `<span class="mbmeta">${isMe ? 'Bạn' : messEsc(m.Username)} · ${String(m.CreatedAt).substring(11,16)}</span>`;
            if (m.Reaction)  inner += `<span class="mbreact">${m.Reaction}</span>`;

            const bubble = document.createElement('div');
            bubble.className = 'mbubble ' + (isMe ? 'me' : 'them');
            bubble.dataset.msgid = m.MessageID;
            bubble.innerHTML = inner;
            bubble.addEventListener('dblclick', () => showMessEmojiPicker(m.MessageID, bubble));

            row.appendChild(bubble);
            box.appendChild(row);
        });
        box.scrollTop = box.scrollHeight;
    }

    /* ── Send text ──────────────────────────────────────── */
    function messSendMsg() {
        const input = document.getElementById('mess-text-input');
        const text  = input.value.trim();
        if (!text || !_mConvId) return;
        input.value = '';
        const fd = new FormData();
        fd.append('conversation_id', _mConvId);
        fd.append('content', text);
        fetch('index.php?controller=chat&action=send', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.status === 'ok') {
                    _mLastMsgId = d.message_id;
                    messRenderMsgs([{
                        MessageID: d.message_id, SenderID: _MESS_ME,
                        Username: d.username, Content: d.content,
                        ImagePath: d.image_path, CreatedAt: d.created_at, Reaction: null
                    }]);
                    messLoadConvs(); // refresh sidebar
                }
            });
    }

    /* ── Send image ─────────────────────────────────────── */
    function messSendImage(input) {
        if (!_mConvId || !input.files[0]) return;
        const fd = new FormData();
        fd.append('conversation_id', _mConvId);
        fd.append('content', '');
        fd.append('image', input.files[0]);
        fetch('index.php?controller=chat&action=send', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.status === 'ok') {
                    _mLastMsgId = d.message_id;
                    messRenderMsgs([{
                        MessageID: d.message_id, SenderID: _MESS_ME,
                        Username: d.username, Content: '',
                        ImagePath: d.image_path, CreatedAt: d.created_at, Reaction: null
                    }]);
                    messLoadConvs();
                }
            });
        input.value = '';
    }

    /* ── Emoji ──────────────────────────────────────────── */
    function showMessEmojiPicker(msgId, bubbleEl) {
        hideMessEmojiPicker();
        _mEmojiTgt = msgId;
        const picker = document.getElementById('mess-emoji-picker');
        const rect   = bubbleEl.getBoundingClientRect();
        picker.style.top  = (rect.top - 60) + 'px';
        picker.style.left = Math.min(rect.left, window.innerWidth - 180) + 'px';
        picker.classList.add('show');
        setTimeout(() => document.addEventListener('click', hideMessEmojiPicker, { once: true }), 50);
    }
    function hideMessEmojiPicker() {
        document.getElementById('mess-emoji-picker').classList.remove('show');
    }
    function messPickEmoji(emoji) {
        if (!_mEmojiTgt) return;
        hideMessEmojiPicker();
        const fd = new FormData();
        fd.append('message_id', _mEmojiTgt);
        fd.append('emoji', emoji);
        fetch('index.php?controller=chat&action=react', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.status === 'ok') {
                    const bubble = document.querySelector(`.mbubble[data-msgid="${d.message_id}"]`);
                    if (bubble) {
                        let br = bubble.querySelector('.mbreact');
                        if (!br) { br = document.createElement('span'); br.className = 'mbreact'; bubble.appendChild(br); }
                        br.textContent = d.emoji;
                    }
                }
            });
    }

    /* ── Polling ────────────────────────────────────────── */
    function messStartPoll() {
        messStopPoll();
        _mPollTimer = setInterval(() => {
            if (!_mConvId) return;
            fetch(`index.php?controller=chat&action=poll&conversation_id=${_mConvId}&last_id=${_mLastMsgId}`)
                .then(r => r.json())
                .then(d => {
                    if (d.messages && d.messages.length > 0) {
                        _mLastMsgId = d.messages[d.messages.length - 1].MessageID;
                        messRenderMsgs(d.messages);
                        messLoadConvs();
                    }
                });
        }, 3000);
    }
    function messStopPoll() {
        if (_mPollTimer) { clearInterval(_mPollTimer); _mPollTimer = null; }
    }

    /* ── Lightbox ───────────────────────────────────────── */
    function messOpenLightbox(src) {
        document.getElementById('mess-lb-img').src = src;
        document.getElementById('mess-lightbox').classList.add('show');
    }
    function messCloseLightbox() {
        document.getElementById('mess-lightbox').classList.remove('show');
    }

    /* ── Escape HTML ────────────────────────────────────── */
    function messEsc(s) {
        return String(s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── Public API (dùng từ chat_widget hoặc nơi khác) ── */
    window.startChat = function(uid, uname) {
        openMessengerWith(uid, uname);
    };
    </script>

    <?php endif; ?>

    <!-- Follow button script -->
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

</body>
</html>
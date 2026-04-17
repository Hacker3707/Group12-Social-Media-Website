<!DOCTYPE html>
<html>
<head>
    <title>Tin nhắn | Passo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="MVC/View/css/chat.css">

    <style>
        body { background:#f0f2f5; }
    </style>
</head>
<body>
    <div class="container-fluid" id="home-container">
        <div class="row" id="navbar">
            <div class="col-md-12 col-12">
                <?php include(__DIR__ . "/navbar.php"); ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-lg-2 d-none d-lg-block" id="left-sidebar">
                <?php include(__DIR__ . "/leftsidebar.php"); ?>
            </div>

            <div class="col-12 col-lg-10 mt-3 mb-4">
                <div class="card chat-page-card">
                    <div class="row no-gutters">
                        <div class="col-12 col-lg-4 friend-pane">
                            <div class="friend-pane-header">
                                <h4 class="font-weight-bold text-primary mb-3">
                                    <i class="fas fa-comments"></i> Tin nhắn
                                </h4>
                                <input type="text" class="friend-search" id="friend-search" placeholder="Tìm tài khoản..." oninput="filterFriends(this.value)">
                            </div>

                            <div class="friend-list" id="friend-list">
                                <?php if (empty($friends)): ?>
                                    <div class="p-4 text-center text-muted">
                                        Hiện chưa có tài khoản nào khả dụng để nhắn tin.
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($friends as $f): ?>
                                        <div class="friend-item"
                                             id="friend-<?= (int)$f['UserID'] ?>"
                                             data-name="<?= htmlspecialchars(strtolower($f['Username']), ENT_QUOTES) ?>"
                                             onclick="openChatFromList(<?= (int)$f['UserID'] ?>, '<?= htmlspecialchars($f['Username'], ENT_QUOTES) ?>', '<?= htmlspecialchars($f['AvatarFP'] ?? '', ENT_QUOTES) ?>')">
                                            <div class="friend-avatar">
                                                <?php if (!empty($f['AvatarFP'])): ?>
                                                    <img src="<?= htmlspecialchars($f['AvatarFP']) ?>" alt="">
                                                <?php else: ?>
                                                    <?= strtoupper(substr($f['Username'], 0, 1)) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="friend-name"><?= htmlspecialchars($f['Username']) ?></div>
                                                <div class="friend-bio"><?= htmlspecialchars($f['Bio'] ?? 'Nhấn để trò chuyện') ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12 col-lg-8 chat-pane">
                            <div class="chat-header">
                                <div class="chat-header-avatar" id="chat-header-avatar">💬</div>
                                <div class="chat-header-name" id="chat-header-name">Chọn một tài khoản để bắt đầu trò chuyện</div>
                            </div>

                            <div class="chat-empty" id="chat-empty">
                                <i class="fas fa-comment-dots"></i>
                                <div>Chọn một người ở bên trái để xem lịch sử chat</div>
                            </div>

                            <div class="msg-list" id="msg-list" style="display:none;"></div>

                            <div class="sticker-bar" id="sticker-bar" style="display:none;">
                                <button class="sticker-btn" onclick="sendSticker('👍')">👍</button>
                                <button class="sticker-btn" onclick="sendSticker('❤️')">❤️</button>
                                <button class="sticker-btn" onclick="sendSticker('😂')">😂</button>
                                <button class="sticker-btn" onclick="sendSticker('😮')">😮</button>
                                <button class="sticker-btn" onclick="sendSticker('😢')">😢</button>
                                <button class="sticker-btn" onclick="sendSticker('🎉')">🎉</button>
                            </div>

                            <div class="chat-input" id="chat-input" style="display:none;">
                                <label class="chat-file-label" title="Gửi ảnh">
                                    🖼️
                                    <input type="file" id="msg-img-file" accept="image/*" onchange="sendImage(this)">
                                </label>
                                <input type="text" id="msg-text-input" placeholder="Nhập tin nhắn..." onkeydown="if(event.key==='Enter') sendMsg()">
                                <button class="chat-send" onclick="sendMsg()">➤</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lightbox" id="chat-lightbox" onclick="closeLightbox()">
            <span class="close-x">×</span>
            <img id="lightbox-img" src="" alt="">
        </div>

        <div class="emoji-picker" id="emoji-picker">
            <span onclick="pickEmoji('❤️')">❤️</span>
            <span onclick="pickEmoji('😂')">😂</span>
            <span onclick="pickEmoji('😮')">😮</span>
            <span onclick="pickEmoji('😢')">😢</span>
            <span onclick="pickEmoji('😡')">😡</span>
            <span onclick="pickEmoji('👍')">👍</span>
        </div>
    </div>

    <script>
    const ME = <?= (int)($_SESSION['user_id'] ?? 0) ?>;
    let currentConvId = null;
    let currentLastMsgId = 0;
    let currentPollTimer = null;
    let currentEmojiTarget = null;
    let activeFriendItem = null;

    function filterFriends(q) {
        q = String(q || '').toLowerCase();
        document.querySelectorAll('.friend-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(q) ? 'flex' : 'none';
        });
    }

    function openChatFromList(uid, uname, avatar) {
        if (activeFriendItem) activeFriendItem.classList.remove('active');
        const item = document.getElementById('friend-' + uid);
        if (item) {
            item.classList.add('active');
            activeFriendItem = item;
        }

        document.getElementById('chat-empty').style.display = 'none';
        document.getElementById('msg-list').style.display = 'flex';
        document.getElementById('sticker-bar').style.display = 'flex';
        document.getElementById('chat-input').style.display = 'flex';
        document.getElementById('msg-list').innerHTML = '';
        document.getElementById('chat-header-name').textContent = uname;

        const avBox = document.getElementById('chat-header-avatar');
        avBox.innerHTML = avatar
            ? `<img src="${avatar}" alt="" style="width:100%;height:100%;object-fit:cover;">`
            : uname.charAt(0).toUpperCase();

        currentConvId = null;
        currentLastMsgId = 0;
        stopPoll();

        fetch(`index.php?controller=chat&action=open&user_id=${uid}`)
            .then(r => r.text())
            .then(t => {
                let d;
                try { d = JSON.parse(t); }
                catch (e) { alert('Lỗi mở chat: ' + t); return; }

                if (d.status !== 'ok') {
                    alert('Lỗi mở chat: ' + (d.debug || d.message || 'unknown'));
                    return;
                }

                currentConvId = d.conversation_id;

                if (d.other_user && d.other_user.avatar) {
                    avBox.innerHTML = `<img src="${d.other_user.avatar}" alt="" style="width:100%;height:100%;object-fit:cover;">`;
                }

                renderMessages(d.messages || []);
                if ((d.messages || []).length > 0) {
                    currentLastMsgId = d.messages[d.messages.length - 1].MessageID;
                }
                startPoll();
            })
            .catch(err => alert('Lỗi mở chat: ' + err));
    }

    function renderMessages(messages) {
        const box = document.getElementById('msg-list');

        messages.forEach(m => {
            const isMe = parseInt(m.SenderID) === ME;
            const row = document.createElement('div');
            row.className = 'msg-row ' + (isMe ? 'me' : 'them');

            let inner = '';
            if (m.Content) inner += `<span>${esc(m.Content)}</span>`;
            if (m.ImagePath) inner += `<img class="chat-img" src="${m.ImagePath}" alt="ảnh" onclick="openLightbox('${m.ImagePath}')">`;
            inner += `<span class="meta">${isMe ? 'Bạn' : esc(m.Username)} · ${String(m.CreatedAt).substring(11,16)}</span>`;
            if (m.Reaction) inner += `<span class="react">${m.Reaction}</span>`;

            const bubble = document.createElement('div');
            bubble.className = 'bubble ' + (isMe ? 'me' : 'them');
            bubble.dataset.msgid = m.MessageID;
            bubble.innerHTML = inner;
            bubble.addEventListener('dblclick', () => showEmojiPicker(m.MessageID, bubble));

            row.appendChild(bubble);
            box.appendChild(row);
        });

        box.scrollTop = box.scrollHeight;
    }

    function sendMsg() {
        const input = document.getElementById('msg-text-input');
        const text = input.value.trim();
        if (!text || !currentConvId) return;

        input.value = '';

        const fd = new FormData();
        fd.append('conversation_id', currentConvId);
        fd.append('content', text);

        fetch('index.php?controller=chat&action=send', {
            method: 'POST',
            body: fd
        })
        .then(r => r.text())
        .then(t => {
            let d;
            try { d = JSON.parse(t); }
            catch (e) { alert('Lỗi gửi tin nhắn: ' + t); return; }

            if (d.status === 'ok') {
                currentLastMsgId = d.message_id;
                renderMessages([{
                    MessageID: d.message_id,
                    SenderID: ME,
                    Username: d.username,
                    Content: d.content,
                    ImagePath: d.image_path,
                    CreatedAt: d.created_at,
                    Reaction: null
                }]);
            } else {
                alert('Lỗi gửi tin nhắn: ' + (d.debug || d.message || 'unknown'));
            }
        })
        .catch(err => alert('Lỗi gửi tin nhắn: ' + err));
    }

    function sendImage(input) {
        if (!currentConvId || !input.files[0]) return;

        const fd = new FormData();
        fd.append('conversation_id', currentConvId);
        fd.append('content', '');
        fd.append('image', input.files[0]);

        fetch('index.php?controller=chat&action=send', {
            method: 'POST',
            body: fd
        })
        .then(r => r.text())
        .then(t => {
            let d;
            try { d = JSON.parse(t); }
            catch (e) { alert('Lỗi gửi ảnh: ' + t); return; }

            if (d.status === 'ok') {
                currentLastMsgId = d.message_id;
                renderMessages([{
                    MessageID: d.message_id,
                    SenderID: ME,
                    Username: d.username,
                    Content: '',
                    ImagePath: d.image_path,
                    CreatedAt: d.created_at,
                    Reaction: null
                }]);
            } else {
                alert('Lỗi gửi ảnh: ' + (d.debug || d.message || 'unknown'));
            }
        })
        .catch(err => alert('Lỗi gửi ảnh: ' + err));

        input.value = '';
    }

    function sendSticker(stickerText) {
        if (!currentConvId) return;

        const fd = new FormData();
        fd.append('conversation_id', currentConvId);
        fd.append('content', stickerText);

        fetch('index.php?controller=chat&action=send', {
            method: 'POST',
            body: fd
        })
        .then(r => r.text())
        .then(t => {
            let d;
            try { d = JSON.parse(t); }
            catch (e) { alert('Lỗi sticker: ' + t); return; }

            if (d.status === 'ok') {
                currentLastMsgId = d.message_id;
                renderMessages([{
                    MessageID: d.message_id,
                    SenderID: ME,
                    Username: d.username,
                    Content: d.content,
                    ImagePath: d.image_path,
                    CreatedAt: d.created_at,
                    Reaction: null
                }]);
            } else {
                alert('Lỗi sticker: ' + (d.debug || d.message || 'unknown'));
            }
        })
        .catch(err => alert('Lỗi sticker: ' + err));
    }

    function startPoll() {
        stopPoll();
        currentPollTimer = setInterval(() => {
            if (!currentConvId) return;

            fetch(`index.php?controller=chat&action=poll&conversation_id=${currentConvId}&last_id=${currentLastMsgId}`)
                .then(r => r.text())
                .then(t => {
                    let d;
                    try { d = JSON.parse(t); }
                    catch (e) { return; }

                    if (d.messages && d.messages.length > 0) {
                        currentLastMsgId = d.messages[d.messages.length - 1].MessageID;
                        renderMessages(d.messages);
                    }
                });
        }, 3000);
    }

    function stopPoll() {
        if (currentPollTimer) {
            clearInterval(currentPollTimer);
            currentPollTimer = null;
        }
    }

    function showEmojiPicker(msgId, bubbleEl) {
        hideEmojiPicker();
        currentEmojiTarget = msgId;

        const picker = document.getElementById('emoji-picker');
        const rect = bubbleEl.getBoundingClientRect();

        picker.style.top = (rect.top - 60) + 'px';
        picker.style.left = Math.min(rect.left, window.innerWidth - 180) + 'px';
        picker.classList.add('show');

        setTimeout(() => {
            document.addEventListener('click', hideEmojiPicker, { once: true });
        }, 50);
    }

    function hideEmojiPicker() {
        document.getElementById('emoji-picker').classList.remove('show');
    }

    function pickEmoji(emoji) {
        if (!currentEmojiTarget) return;
        hideEmojiPicker();

        const fd = new FormData();
        fd.append('message_id', currentEmojiTarget);
        fd.append('emoji', emoji);

        fetch('index.php?controller=chat&action=react', {
            method: 'POST',
            body: fd
        })
        .then(r => r.text())
        .then(t => {
            let d;
            try { d = JSON.parse(t); }
            catch (e) { return; }

            if (d.status === 'ok') {
                const bubble = document.querySelector(`.bubble[data-msgid="${d.message_id}"]`);
                if (bubble) {
                    let br = bubble.querySelector('.react');
                    if (!br) {
                        br = document.createElement('span');
                        br.className = 'react';
                        bubble.appendChild(br);
                    }
                    br.textContent = d.emoji;
                }
            }
        });
    }

    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('chat-lightbox').classList.add('show');
    }

    function closeLightbox() {
        document.getElementById('chat-lightbox').classList.remove('show');
    }

    function esc(s) {
        return String(s)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;');
    }

    (function autoOpenFromUrl() {
        const params = new URLSearchParams(window.location.search);
        const uid = params.get('user_id');
        if (!uid) return;

        const item = document.getElementById('friend-' + uid);
        if (item) item.click();
    })();
    </script>
</body>
</html>
<?php if (isset($_SESSION['user_id'])): ?>
<!-- ================================================================
     CHAT WIDGET  —  nhúng vào cuối home.php trước </body>
     Tính năng: text · gửi ảnh · react emoji · xem ảnh to · polling
     ================================================================ -->
<style>
/* ── FAB button ─────────────────────────────── */
#chat-fab{position:fixed;bottom:40px;right:24px;width:52px;height:52px;border-radius:50%;background:#0084ff;color:#fff;border:none;font-size:22px;cursor:pointer;box-shadow:0 4px 14px rgba(0,132,255,.4);z-index:9000;display:flex;align-items:center;justify-content:center;transition:transform .2s;}
#chat-fab:hover{transform:scale(1.1);}
#chat-fab .cbadge{position:absolute;top:-4px;right:-4px;background:#e41e3f;color:#fff;font-size:11px;font-weight:700;border-radius:10px;padding:1px 5px;display:none;}

/* ── Panel chung ────────────────────────────── */
.chat-panel{position:fixed;bottom:145px;right:24px;width:330px;background:#fff;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,.18);display:none;flex-direction:column;z-index:9001;overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;}
.chat-panel.active{display:flex;}

/* ── Header ─────────────────────────────────── */
.chat-hdr{background:#0084ff;color:#fff;padding:11px 14px;display:flex;align-items:center;gap:8px;font-weight:600;font-size:15px;}
.chat-hdr .c-back{background:none;border:none;color:#fff;font-size:20px;cursor:pointer;padding:0;line-height:1;}
.chat-hdr .c-title{flex:1;}
.chat-hdr .c-close{background:none;border:none;color:#fff;font-size:22px;cursor:pointer;padding:0;line-height:1;}

/* ── Conv list ──────────────────────────────── */
#conv-list{overflow-y:auto;max-height:390px;}
.conv-item{display:flex;align-items:center;gap:10px;padding:10px 14px;cursor:pointer;border-bottom:1px solid #f2f2f2;transition:background .15s;}
.conv-item:hover{background:#f5f7fb;}
.c-av{width:42px;height:42px;border-radius:50%;background:#dde3ec;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#555;font-size:17px;overflow:hidden;}
.c-av img{width:100%;height:100%;object-fit:cover;}
.c-info{flex:1;overflow:hidden;}
.c-name{font-weight:600;font-size:14px;}
.c-last{font-size:12px;color:#888;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.c-ubadge{background:#0084ff;color:#fff;border-radius:10px;padding:2px 7px;font-size:11px;font-weight:700;flex-shrink:0;}
#conv-empty{padding:36px;text-align:center;color:#aaa;font-size:13px;}

/* ── Msg list ───────────────────────────────── */
#msg-list{overflow-y:auto;max-height:290px;padding:10px 10px 6px;display:flex;flex-direction:column;gap:4px;background:#f0f4f8;}

/* ── Bubble ─────────────────────────────────── */
.msg-row{display:flex;flex-direction:column;}
.msg-row.me{align-items:flex-end;}
.msg-row.them{align-items:flex-start;}
.bubble{max-width:74%;padding:8px 13px;border-radius:18px;font-size:13.5px;line-height:1.45;word-break:break-word;position:relative;cursor:pointer;}
.bubble.me{background:#0084ff;color:#fff;border-bottom-right-radius:4px;}
.bubble.them{background:#fff;color:#222;border-bottom-left-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,.08);}
.bubble img.chat-img{max-width:180px;max-height:180px;border-radius:10px;display:block;cursor:zoom-in;}
.bubble .bmeta{font-size:10px;margin-top:3px;opacity:.6;display:block;}
.bubble .breact{position:absolute;bottom:-10px;right:6px;font-size:14px;background:#fff;border-radius:10px;padding:0 3px;box-shadow:0 1px 4px rgba(0,0,0,.15);}
.bubble.me .breact{right:auto;left:6px;}

/* ── Emoji picker ───────────────────────────── */
.emoji-picker{display:none;position:absolute;bottom:22px;background:#fff;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.18);padding:6px 8px;gap:4px;flex-wrap:wrap;width:160px;z-index:9010;}
.emoji-picker.show{display:flex;}
.emoji-picker span{font-size:20px;cursor:pointer;padding:2px;}
.emoji-picker span:hover{transform:scale(1.3);}

/* ── Input area ─────────────────────────────── */
#msg-form{display:flex;align-items:center;padding:8px 10px;border-top:1px solid #eee;gap:6px;background:#fff;}
#msg-input{flex:1;border:1px solid #ddd;border-radius:20px;padding:7px 13px;font-size:13.5px;outline:none;}
#msg-input:focus{border-color:#0084ff;}
.img-label{cursor:pointer;font-size:20px;color:#0084ff;padding:4px;}
#msg-img-input{display:none;}
#msg-send{background:#0084ff;color:#fff;border:none;border-radius:50%;width:36px;height:36px;font-size:17px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
#msg-send:hover{background:#006edb;}

/* ── Lightbox ───────────────────────────────── */
#chat-lightbox{display:none;position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:99999;align-items:center;justify-content:center;}
#chat-lightbox.show{display:flex;}
#chat-lightbox img{max-width:90vw;max-height:90vh;border-radius:10px;box-shadow:0 4px 40px rgba(0,0,0,.5);}
#chat-lightbox .lb-close{position:absolute;top:18px;right:24px;color:#fff;font-size:32px;cursor:pointer;line-height:1;}
</style>

<!-- FAB -->
<button id="chat-fab" onclick="chatToggle()" title="Tin nhắn">
    💬<span class="cbadge" id="chat-badge"></span>
</button>

<!-- Panel conv list -->
<div class="chat-panel" id="panel-conv">
    <div class="chat-hdr">
        <span class="c-title">Tin nhắn</span>
        <button class="c-close" onclick="chatClose()">×</button>
    </div>
    <div id="conv-list"><div id="conv-empty">Đang tải...</div></div>
</div>

<!-- Panel chat box -->
<div class="chat-panel" id="panel-chat">
    <div class="chat-hdr">
        <button class="c-back" onclick="chatBack()">‹</button>
        <span class="c-title" id="chat-pname">...</span>
        <button class="c-close" onclick="chatClose()">×</button>
    </div>
    <div id="msg-list"></div>
    <div id="msg-form">
        <label class="img-label" title="Gửi ảnh">
            🖼️<input type="file" id="msg-img-input" accept="image/*" onchange="sendImage(this)">
        </label>
        <input type="text" id="msg-input" placeholder="Nhắn tin..." autocomplete="off"
               onkeydown="if(event.key==='Enter')sendMsg()">
        <button id="msg-send" onclick="sendMsg()">➤</button>
    </div>
</div>

<!-- Emoji picker (global) -->
<div class="emoji-picker" id="emoji-picker">
    <?php foreach(['❤️','😂','😮','😢','😡','👍'] as $e): ?>
        <span onclick="pickEmoji('<?= $e ?>')"><?= $e ?></span>
    <?php endforeach; ?>
</div>

<!-- Lightbox -->
<div id="chat-lightbox" onclick="closeLightbox()">
    <span class="lb-close">×</span>
    <img id="lb-img" src="" alt="">
</div>

<script>
const _ME = <?= (int)$_SESSION['user_id'] ?>;
let _convId   = null;
let _lastMsgId = 0;
let _pollTimer = null;
let _emojiTarget = null; // message_id đang chờ react
let _chatOpen = false;

/* ── Toggle / open / close ──────────────────────────────── */
function chatToggle(){
    _chatOpen = !_chatOpen;
    document.getElementById('panel-conv').classList.toggle('active', _chatOpen);
    document.getElementById('panel-chat').classList.remove('active');
    if(_chatOpen) loadConvs();
}
function chatClose(){
    _chatOpen = false;
    document.getElementById('panel-conv').classList.remove('active');
    document.getElementById('panel-chat').classList.remove('active');
    stopPoll();
    hideEmojiPicker();
}
function chatBack(){
    document.getElementById('panel-chat').classList.remove('active');
    document.getElementById('panel-conv').classList.add('active');
    stopPoll(); hideEmojiPicker();
    loadConvs();
}

/* ── Conversation list ──────────────────────────────────── */
function loadConvs(){
    fetch('index.php?controller=chat&action=conversations')
        .then(r=>r.json()).then(d=>{
            const box = document.getElementById('conv-list');
            if(!d.conversations||d.conversations.length===0){
                box.innerHTML='<div id="conv-empty">Chưa có tin nhắn nào</div>'; return;
            }
            box.innerHTML = d.conversations.map(c=>{
                const ini = (c.OtherUsername||'?')[0].toUpperCase();
                const av  = c.OtherAvatar
                    ? `<img src="${c.OtherAvatar}" alt="">`
                    : ini;
                const badge = c.UnreadCount>0 ? `<span class="c-ubadge">${c.UnreadCount}</span>` : '';
                let lastTxt = c.LastMessage ? esc(c.LastMessage).substring(0,38) : (c.LastImage ? '🖼️ Ảnh' : 'Bắt đầu trò chuyện');
                return `<div class="conv-item" onclick="openChat(${c.OtherUserID},'${esc(c.OtherUsername)}')">
                    <div class="c-av">${av}</div>
                    <div class="c-info"><div class="c-name">${esc(c.OtherUsername)}</div><div class="c-last">${lastTxt}</div></div>
                    ${badge}
                </div>`;
            }).join('');
        });
}

/* ── Open 1-1 chat ─────────────────────────────────────── */
function openChat(uid, uname){
    document.getElementById('panel-conv').classList.remove('active');
    document.getElementById('panel-chat').classList.add('active');
    document.getElementById('chat-pname').textContent = uname;
    document.getElementById('msg-list').innerHTML = '';
    _convId = null; _lastMsgId = 0;

    fetch(`index.php?controller=chat&action=open&user_id=${uid}`)
        .then(r=>r.json()).then(d=>{
            if(d.status!=='ok') return;
            _convId = d.conversation_id;
            renderMsgs(d.messages);
            if(d.messages.length>0) _lastMsgId = d.messages[d.messages.length-1].MessageID;
            startPoll();
        });
}

/* ── Render messages ────────────────────────────────────── */
function renderMsgs(msgs){
    const box = document.getElementById('msg-list');
    msgs.forEach(m=>{
        const isMe = parseInt(m.SenderID) === _ME;
        const row  = document.createElement('div');
        row.className = 'msg-row ' + (isMe ? 'me' : 'them');

        let inner = '';
        if(m.Content) inner += `<span>${esc(m.Content)}</span>`;
        if(m.ImagePath) inner += `<img class="chat-img" src="${m.ImagePath}" alt="ảnh" onclick="openLightbox('${m.ImagePath}')">`;
        inner += `<span class="bmeta">${isMe?'Bạn':esc(m.Username)} · ${m.CreatedAt.substring(11,16)}</span>`;
        if(m.Reaction) inner += `<span class="breact">${m.Reaction}</span>`;

        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + (isMe ? 'me' : 'them');
        bubble.dataset.msgid = m.MessageID;
        bubble.innerHTML = inner;
        bubble.addEventListener('dblclick', e=>{ showEmojiPicker(m.MessageID, bubble); });

        row.appendChild(bubble);
        box.appendChild(row);
    });
    box.scrollTop = box.scrollHeight;
}

/* ── Send text ──────────────────────────────────────────── */
function sendMsg(){
    const input = document.getElementById('msg-input');
    const text  = input.value.trim();
    if(!text || !_convId) return;
    input.value = '';
    const fd = new FormData();
    fd.append('conversation_id', _convId);
    fd.append('content', text);
    fetch('index.php?controller=chat&action=send',{method:'POST',body:fd})
        .then(r=>r.json()).then(d=>{
            if(d.status==='ok'){
                _lastMsgId = d.message_id;
                renderMsgs([{MessageID:d.message_id,SenderID:_ME,Username:d.username,Content:d.content,ImagePath:d.image_path,CreatedAt:d.created_at,Reaction:null}]);
            }
        });
}

/* ── Send image ─────────────────────────────────────────── */
function sendImage(input){
    if(!_convId||!input.files[0]) return;
    const fd = new FormData();
    fd.append('conversation_id', _convId);
    fd.append('content', '');
    fd.append('image', input.files[0]);
    fetch('index.php?controller=chat&action=send',{method:'POST',body:fd})
        .then(r=>r.json()).then(d=>{
            if(d.status==='ok'){
                _lastMsgId = d.message_id;
                renderMsgs([{MessageID:d.message_id,SenderID:_ME,Username:d.username,Content:'',ImagePath:d.image_path,CreatedAt:d.created_at,Reaction:null}]);
            }
        });
    input.value='';
}

/* ── Emoji picker ───────────────────────────────────────── */
function showEmojiPicker(msgId, bubbleEl){
    hideEmojiPicker();
    _emojiTarget = msgId;
    const picker = document.getElementById('emoji-picker');
    const rect   = bubbleEl.getBoundingClientRect();
    picker.style.bottom = (window.innerHeight - rect.top + 8) + 'px';
    picker.style.right  = (window.innerWidth  - rect.right + 4) + 'px';
    picker.style.position = 'fixed';
    picker.classList.add('show');
    setTimeout(()=>document.addEventListener('click', hideEmojiPicker, {once:true}), 50);
}
function hideEmojiPicker(){
    document.getElementById('emoji-picker').classList.remove('show');
}
function pickEmoji(emoji){
    if(!_emojiTarget) return;
    hideEmojiPicker();
    const fd = new FormData();
    fd.append('message_id', _emojiTarget);
    fd.append('emoji', emoji);
    fetch('index.php?controller=chat&action=react',{method:'POST',body:fd})
        .then(r=>r.json()).then(d=>{
            if(d.status==='ok'){
                const bubble = document.querySelector(`.bubble[data-msgid="${d.message_id}"]`);
                if(bubble){
                    let br = bubble.querySelector('.breact');
                    if(!br){ br=document.createElement('span'); br.className='breact'; bubble.appendChild(br); }
                    br.textContent = d.emoji;
                }
            }
        });
}

/* ── Polling ────────────────────────────────────────────── */
function startPoll(){
    stopPoll();
    _pollTimer = setInterval(()=>{
        if(!_convId) return;
        fetch(`index.php?controller=chat&action=poll&conversation_id=${_convId}&last_id=${_lastMsgId}`)
            .then(r=>r.json()).then(d=>{
                if(d.messages&&d.messages.length>0){
                    _lastMsgId = d.messages[d.messages.length-1].MessageID;
                    renderMsgs(d.messages);
                }
            });
    }, 3000);
}
function stopPoll(){ if(_pollTimer){clearInterval(_pollTimer);_pollTimer=null;} }

/* ── Unread badge ───────────────────────────────────────── */
function refreshBadge(){
    fetch('index.php?controller=chat&action=unread')
        .then(r=>r.json()).then(d=>{
            const b = document.getElementById('chat-badge');
            if(d.count>0){b.textContent=d.count;b.style.display='block';}
            else b.style.display='none';
        });
}
setInterval(refreshBadge, 5000); refreshBadge();

/* ── Lightbox ───────────────────────────────────────────── */
function openLightbox(src){
    document.getElementById('lb-img').src = src;
    document.getElementById('chat-lightbox').classList.add('show');
}
function closeLightbox(){
    document.getElementById('chat-lightbox').classList.remove('show');
}

/* ── Escape HTML ────────────────────────────────────────── */
function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

/* ── API công khai: gọi từ nút "Nhắn tin" bên ngoài ─────── */
window.startChat = function(uid, uname){
    _chatOpen = true;
    openChat(uid, uname);
};
</script>
<?php endif; ?>
<?php if(empty($notifications)): ?>
    <div class="p-2 text-center text-muted">
        Không có thông báo
    </div>
<?php else: ?>
    <?php foreach($notifications as $noti): ?>
        <div class="dropdown-item noti-item <?= $noti->getIsRead() ? '' : 'font-weight-bold' ?>"
             data-id="<?= $noti->getNotificationID() ?>"
             style="white-space: normal; border-bottom:1px solid #eee;">

            <div>
                <?= $noti->getContent() ?>
            </div>

            <small class="text-muted">
                <?= $noti->getCreatedAt() ?>
            </small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
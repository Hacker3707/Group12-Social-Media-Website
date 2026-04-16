<div class="container mt-4">

<?php $userFollowStatus = $userFollowStatus ?? []; ?>

<?php if (!empty($users)): ?>

    <?php foreach ($users as $user): ?>
        <?php include __DIR__ . "/partials/user_card.php"; ?>
    <?php endforeach; ?>

<?php else: ?>

<div class="alert alert-warning">
    Không tìm thấy người dùng nào.
</div>

<?php endif; ?>

</div>

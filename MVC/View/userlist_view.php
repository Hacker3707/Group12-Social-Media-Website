<div class="container mt-4">

<?php if (!empty($users)): ?>

<?php foreach ($users as $u): ?>

    <div class="card mb-3 shadow-sm">

        <div class="card-body d-flex align-items-center">

            <!-- Avatar -->
            <img src="<?= htmlspecialchars($u->getAvatarFP() ?? 'uploads/default-avatar.png') ?>"
                 class="rounded-circle mr-3"
                 width="60"
                 height="60"
                 style="object-fit:cover;">

            <!-- User info -->
            <div class="flex-grow-1">

                <h5 class="mb-1 font-weight-bold">
                    <?= htmlspecialchars($u->getUsername()) ?>
                </h5>

                <p class="text-muted mb-1">
                    <?= htmlspecialchars($u->getEmail()) ?>
                </p>

                <?php if ($u->getBio()): ?>
                    <small class="text-secondary">
                        <?= htmlspecialchars($u->getBio()) ?>
                    </small>
                <?php endif; ?>

            </div>

            <!-- Actions -->
            <div>

                <a href="index.php?controller=user&action=profile&id=<?= $u->getUserId() ?>"
                   class="btn btn-sm btn-outline-primary mr-2">
                    View Profile
                </a>

                <a href="index.php?controller=follow&action=follow&id=<?= $u->getUserId() ?>"
                   class="btn btn-sm btn-primary">
                    Follow
                </a>

            </div>

        </div>

    </div>

<?php endforeach; ?>

<?php else: ?>

<div class="alert alert-warning">
    Không tìm thấy người dùng nào.
</div>

<?php endif; ?>

</div>

<div class="container mt-4">

    <div class="row">

        <?php foreach ($groups as $g): ?>

            <div class="col-md-4 mb-4">

            <div class="card shadow-sm h-100">

            <div class="card-body">

            <h5 class="card-title">
            <?= htmlspecialchars($g->getGroupName()) ?>
            </h5>

            <p class="text-muted">
            Group ID: <?= $g->getGroupId() ?>
            </p>

            <p>
            Category:
            <span class="badge badge-light">
            <?= $g->getCategoryId() ?>
            </span>
            </p>

            <?php if ($g->getPrivacy() == 'public'): ?>
            <span class="badge badge-success">🌍 Public</span>
            <?php else: ?>
            <span class="badge badge-secondary">🔒 Private</span>
            <?php endif; ?>

            </div>

            <div class="card-footer text-right">

            <a href="index.php?controller=group&action=viewMembers&id=<?= $g->getGroupId() ?>" 
            class="btn btn-sm btn-info">Members</a>

            <a href="index.php?controller=group&action=edit&id=<?= $g->getGroupId() ?>" 
            class="btn btn-sm btn-outline-primary">Edit</a>

            </div>

            </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>
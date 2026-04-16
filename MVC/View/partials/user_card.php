<?php
if (!function_exists('getUserField')) {
    function getUserField($user, $field, $default = '') {
        if (is_object($user)) {
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
            if (method_exists($user, $getter)) {
                return $user->$getter();
            }
        }

        if (is_array($user)) {
            if (isset($user[$field])) {
                return $user[$field];
            }
            $camelField = ucfirst($field);
            if (isset($user[$camelField])) {
                return $user[$camelField];
            }
        }

        return $default;
    }
}

$userId = getUserField($user, 'UserID', 0);
$userFollowStatus = $userFollowStatus ?? [];
$username = htmlspecialchars(getUserField($user, 'Username', 'Unknown'));
$email = htmlspecialchars(getUserField($user, 'Email', ''));
$bio = htmlspecialchars(getUserField($user, 'Bio', '')) ?? null;
$isUserFollowed = $userFollowStatus[$userId] ?? false;
$currentUserId = $_SESSION['user_id'] ?? null;
?>

<div class="card mb-3 shadow-sm">
    <div class="card-body d-flex align-items-center">
        <img src="<?= $avatar ?>"
             class="rounded-circle mr-3"
             width="60"
             height="60"
             style="object-fit:cover;">

        <div class="flex-grow-1">
            <h5 class="mb-1 font-weight-bold"><?= $username ?></h5>
            <p class="text-muted mb-1"><?= $email ?></p>
            <?php if ($bio): ?>
                <small class="text-secondary"><?= $bio ?></small>
            <?php endif; ?>
        </div>

        <div>
            <a href="index.php?controller=user&action=profile&id=<?= $userId ?>"
               class="btn btn-sm btn-outline-primary mr-2">
                View Profile
            </a>

            <?php if ($currentUserId && $currentUserId != $userId): ?>
                <?php if (!$isUserFollowed): ?>
                    <a href="index.php?controller=follow&action=follow&id=<?= $userId ?>"
                       class="btn btn-sm btn-primary">
                        Follow
                    </a>
                <?php else: ?>
                    <a href="index.php?controller=follow&action=follow&id=<?= $userId ?>"
                       class="btn btn-sm btn-secondary text-white">
                        Followed
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

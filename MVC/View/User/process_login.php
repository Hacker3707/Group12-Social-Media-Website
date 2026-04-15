<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../MVC/Service/Supabase/SupabaseService.php';
require_once __DIR__ . '/../../../MVC/Model/UserModel.php';
require_once __DIR__ . '/../../../Entity/Member.php';
require_once __DIR__ . '/../../../Entity/Admin.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$sessionTimeout = 1800; // 30 minutes idle timeout
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
ini_set('session.gc_maxlifetime', (string)$sessionTimeout);
session_set_cookie_params([
    'lifetime' => $sessionTimeout,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['last_activity']) && (time() - (int)$_SESSION['last_activity']) > $sessionTimeout) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
    }
    session_destroy();
    session_start();
}

$_SESSION['last_activity'] = time();


// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'method_not_allowed']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['access_token'])) {
    echo json_encode(['success' => false, 'error' => 'missing_token']);
    exit;
}

$accessToken = $data['access_token'];

if ($accessToken === '') {
    echo json_encode(['success' => false, 'error' => 'missing_token']);
    exit;
}

// Verify token with Supabase
$userData = SupabaseService::verifyUserToken($accessToken);

if ($userData === null) {
    error_log("Google OAuth: Token verification failed");
    echo json_encode(['success' => false, 'error' => 'invalid_token']);
    exit;
}

// Extract user information from token
$supabaseId = $userData->sub ?? $userData->id ?? null;
$email = $userData->email ?? null;
$name = trim((string)($userData->user_metadata->full_name ?? $userData->user_metadata->name ?? 'User'));
$avatarUrl = $userData->user_metadata->avatar_url ?? $userData->user_metadata->picture ?? null;

if (!$supabaseId || !$email) {
    error_log("Google OAuth: Missing supabase_id or email in token");
    echo json_encode(['success' => false, 'error' => 'missing_user_data']);
    exit;
}

$user = new UserModel();

$buildUserEntity = function (array $row) {
    if (($row['UserRole'] ?? 'user') === 'admin') {
        return new Admin(
            (int)$row['UserID'],
            $row['Username'],
            $row['Email'],
            $row['AccountPassword'],
            $row['AccountStatus'] ?? 'active',
            $row['AvatarFP'] ?? null,
            $row['Phone'] ?? null,
            $row['Bio'] ?? null
        );
    }

    return new Member(
        (int)$row['UserID'],
        $row['Username'],
        $row['Email'],
        $row['AccountPassword'],
        $row['AccountStatus'] ?? 'active',
        $row['AvatarFP'] ?? null,
        $row['Phone'] ?? null,
        $row['Bio'] ?? null
    );
};

$existingUser = $user->getBySupabaseId($supabaseId);

if (empty($existingUser)) {
    $existingUser = $user->getByEmail($email);
}

if (!empty($existingUser)) {
    if (($existingUser['UserRole'] ?? 'user') === 'admin') {
        error_log('Google OAuth: Admin account cannot use Google login - ' . $email);
        echo json_encode(['success' => false, 'error' => 'admin_google_login_not_allowed']);
        exit;
    }

    if (($existingUser['AccountStatus'] ?? 'active') !== 'active') {
        error_log('Google OAuth: Account is not active - ' . $email);
        echo json_encode(['success' => false, 'error' => 'account_not_active']);
        exit;
    }

    $userId = (int)$existingUser['UserID'];
    $currentAvatar = $existingUser['AvatarFP'] ?? null;
    $avatarToSave = $avatarUrl ?: $currentAvatar;

    if ($avatarToSave !== null && $avatarToSave !== $currentAvatar) {
        $user->update(
            $userId,
            $existingUser['Username'],
            $existingUser['Email'],
            $existingUser['Bio'] ?? '',
            $existingUser['Phone'] ?? '',
            $avatarToSave,
            $supabaseId,
            'GOOGLE'
        );
    } else {
        $user->update(
            $userId,
            $existingUser['Username'],
            $existingUser['Email'],
            $existingUser['Bio'] ?? '',
            $existingUser['Phone'] ?? '',
            null,
            $supabaseId,
            'GOOGLE'
        );
    }

    $existingUser['supabase_id'] = $supabaseId;
    $existingUser['o_provider'] = 'GOOGLE';
    $existingUser['AvatarFP'] = $avatarToSave;

    $sessionUser = $buildUserEntity($existingUser);

    error_log('Google OAuth: User found by supabase_id/email - ' . $email);
} else {
    $baseUsername = $name !== '' ? $name : explode('@', $email)[0];
    $candidateUsername = $baseUsername;
    $suffix = 1;

    while ($user->existsByUsername($candidateUsername)) {
        $candidateUsername = $baseUsername . $suffix;
        $suffix++;
    }

    $temporaryPassword = bin2hex(random_bytes(16));
    $newMember = new Member(null, $candidateUsername, $email, $temporaryPassword);

    if (!$user->insert($newMember, $temporaryPassword, $supabaseId, 'GOOGLE')) {
        error_log('Google OAuth: Failed to create new user - ' . $email);
        echo json_encode(['success' => false, 'error' => 'create_user_failed']);
        exit;
    }

    $createdUser = $user->getByEmail($email);

    if (!$createdUser) {
        error_log('Google OAuth: Created user not found - ' . $email);
        echo json_encode(['success' => false, 'error' => 'create_user_failed']);
        exit;
    }

    if ($avatarUrl) {
        $user->update(
            (int)$createdUser['UserID'],
            $createdUser['Username'],
            $createdUser['Email'],
            $createdUser['Bio'] ?? '',
            $createdUser['Phone'] ?? '',
            $avatarUrl,
            $supabaseId,
            'GOOGLE'
        );
        $createdUser['AvatarFP'] = $avatarUrl;
    } else {
        $user->update(
            (int)$createdUser['UserID'],
            $createdUser['Username'],
            $createdUser['Email'],
            $createdUser['Bio'] ?? '',
            $createdUser['Phone'] ?? '',
            null,
            $supabaseId,
            'GOOGLE'
        );
    }

    $createdUser['supabase_id'] = $supabaseId;
    $createdUser['o_provider'] = 'GOOGLE';

    $sessionUser = $buildUserEntity($createdUser);

    error_log('Google OAuth: New user created - ' . $email);
}

$_SESSION['user_id'] = $sessionUser->getUserId();
$_SESSION['username'] = $sessionUser->getUsername();
$_SESSION['role'] = $sessionUser->getUserRole();
$_SESSION['avatar'] = $sessionUser->getAvatarFp();
$_SESSION['last_activity'] = time();
session_regenerate_id(true);

error_log("Google OAuth: Session created successfully for " . $email);

// Return success response
echo json_encode([
    'success' => true,
    'redirect' => '/index.php'
]);
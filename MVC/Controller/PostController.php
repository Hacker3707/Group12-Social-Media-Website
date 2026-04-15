<?php
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../../Entity/Media.php";
include_once __DIR__ . "/../Model/CategoryModel.php";
include_once __DIR__ . "/../Model/CommentModel.php";
include_once __DIR__ . "/../Model/UserModel.php";
include_once __DIR__ . "/../Model/GroupModel.php";
include_once __DIR__ . "/../Model/MediaModel.php";

class PostController extends AppController
{
    private $postModel;
    private $reactionModel;
    private $categoryModel;
    private $commentModel;
    private $userModel;
    private $groupModel;
    private $mediaModel;

    public function __construct()
    {
        $this->postModel     = new PostModel();
        $this->reactionModel = new ReactionModel();
        $this->categoryModel = new CategoryModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->mediaModel    = new MediaModel();
    }

    public function createPost()
    {
        $userId     = $_SESSION['user_id'] ?? null;
        $groupId    = $_POST['group_id'] ?? null;
        $categoryId = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;

        $title      = $_POST['title'] ?? '';
        $content    = $_POST['content'] ?? '';
        $isProduct  = $_POST['is_product'] ?? 1;
        $price      = $_POST['price'] ?? null;
        $condition  = $_POST['condition'] ?? 'good';
        $location   = $_POST['location'] ?? 'other';
        $brand      = $_POST['brand'] ?? null;
        $status     = 'selling';

        // Neu KHONG phai product -> reset het
        if ($isProduct == 0) {
            $price = null;
            $condition = null;
            $location = null;
            $brand = null;
            $status = null;
        }

        $post = new Post(
            null,
            $userId,
            $groupId,
            $categoryId,
            $title,
            $content,
            $price,
            $condition,
            $location,
            $brand,
            $status
        );

        $result = $this->postModel->insertPost($post);

        if (!$result) {
            echo "fail";
            exit;
        }

        $newPostId = $this->postModel->getLastInsertId();

        // Luu file media neu co upload
        if (isset($_FILES['media']) && isset($_FILES['media']['error']) && $_FILES['media']['error'] == 0) {
            $mimeType  = mime_content_type($_FILES['media']['tmp_name']);
            $mediaType = 'photo';

            if (strpos($mimeType, 'video/') === 0) {
                $mediaType = 'video';
            }

            $uploadDir = __DIR__ . "/../../../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $safeName = time() . "_" . $userId . "_" . basename($_FILES['media']['name']);
            $destPath = $uploadDir . $safeName;
            $dbPath   = "uploads/" . $safeName;

            if (move_uploaded_file($_FILES['media']['tmp_name'], $destPath)) {
                $this->mediaModel->insertMediaForPost($userId, $newPostId, $mediaType, $dbPath);
            }
        }

        echo "success:" . $newPostId;
        exit;
    }

    public function showHome()
    {
        $posts  = $this->postModel->getAll() ?? [];
        $userid = $_SESSION['user_id'] ?? null;
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        $reactions_forPost = [];
        $isSameUser_reactPost = [];
        $reactions_forComment = [];
        $isSameUser_reactCmt = [];

        // Lay thong tin user
        if ($userid) {
            $userInfo = $this->userModel->getById($userid);
            $username = $userInfo ? ($userInfo['Username'] ?? 'Guest') : "Guest";
        } else {
            $username = "Guest";
        }
        // LỖI Ở ĐÂY: Dấu '}' đóng hàm showHome() bị đặt sai vị trí đã được gỡ bỏ

        // =========================
        // 🔥 COMMENTS
        // =========================
        $comments = [];
        $commentTree = [];
        $canDel_EditPost = [];

        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $canDel_EditPost[$postId] = ($userid && (int)$post->getUserId() === (int)$userid) || $isSystemAdmin;

            $reactions_forPost[$postId] = $this->reactionModel->selectReactionsForPost($postId);
            $isSameUser_reactPost[$postId] = false;

            foreach ($reactions_forPost[$postId] as $reaction) {
                if ($userid && $reaction->getUserId() == $userid) {
                    $isSameUser_reactPost[$postId] = true;
                    break;
                }
            }

            $comments[$postId] = $this->commentModel->fetchByField('PostID', $postId);

            $commentTree[$postId] = [];

            foreach ($comments[$postId] as $c) {
                $parent = $c->getParentCommentId();
                $commentId = $c->getCommentId();
                $commentTree[$postId][$parent][] = $c;

                $reactions_forComment[$commentId] = $this->reactionModel->selectReactionsForComment($commentId);
                $isSameUser_reactCmt[$commentId] = false;

                foreach ($reactions_forComment[$commentId] as $reaction) {
                    if ($userid && $reaction->getUserId() == $userid) {
                        $isSameUser_reactCmt[$commentId] = true;
                        break;
                    }
                }
            }
        }

        // =========================
        // 🔥 MEDIA
        // =========================
        $mediaForPost = [];

        foreach ($posts as $post) {
            $mediaForPost[$post->getPostId()] = $this->mediaModel->getByPostId($post->getPostId());
        }

        // =========================
        // 🔥 USER INFO
        // =========================
        if ($userid) {
            $userInfo = $this->userModel->getById($userid);
            $username = $userInfo ? $userInfo['Username'] : "Guest";
        } else {
            $username = "Guest";
        }

        // =========================
        // 🔥 RENDER VIEW (DUY NHẤT 1 LẦN)
        // =========================
        include_once __DIR__ . "/../View/home.php";
    }

public function PostAction()
{
    $action = $_GET['action'] ?? "home";

    switch ($action) {
        case "createPost":
            $this->createPost();
            break;

        case "home":
            unset($_SESSION['category_filter']);
            $this->showHome();
            break;

        case "create":
            $this->showCreateForm();
             break;
        case "showEditForm":
            $this->showEditForm();
             break;

        foreach ($posts as $post) {
            include "MVC/View/post_item.php";
        }
    }

    public function getAllPosts() {
        $posts = $this->postModel->getAll() ?? [];

        $reactions = [];
        foreach ($posts as $post) {
            $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
        }

        $comments = [];
        foreach ($posts as $post) {
            $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
        }

        include_once __DIR__ . "/../View/home.php";
        return $posts;
    }

    public function getPostById($postId) {
        return $this->postModel->getById($postId);
    }

    public function getPostsByUserId($userId) {
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $posts  = $this->postModel->fetchByField('UserID', $userId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByGroupId($groupId) {
        if (isset($_GET['group_id'])) {
            $groupId = $_GET['group_id'];
            $posts   = $this->postModel->fetchByField('GroupID', $groupId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByCategoryId() {
        if (isset($_GET['category_id'])) {
            $categoryId = $_GET['category_id'];
            $posts      = $this->postModel->fetchByField('CategoryID', $categoryId);
            include_once __DIR__ . "/../View/home.php";
        }
        return [];
    }

    public function deletePost()
    {
        if (!isset($_SESSION['user_id'])) {
            echo "fail";
            exit;
        }

        $postId = (int)($_POST['postId'] ?? 0);

        if (!$postId) {
            echo "fail";
            exit;
        }

        $post = $this->postModel->getById($postId);
        if (!$post) {
            echo "fail";
            exit;
        }

        $currentUserId = (int)$_SESSION['user_id'];
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPostOwner = ((int)$post->getUserId() === $currentUserId);

        if (!$isSystemAdmin && !$isPostOwner) {
            echo "fail";
            exit;
        }

        $result = $this->postModel->delete($postId);
        echo $result ? "success" : "fail";
        exit;
    }

    public function showCreateForm()
    {
        $categories = $this->categoryModel->getAll();
        $group = null;
        include __DIR__ . "/../View/createpost_view.php";
        die();
    }

    public function showEditForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=user&action=login");
            exit;
        }

        $postId = (int)($_GET['id'] ?? 0);
        if ($postId <= 0) {
            header("Location: index.php?controller=post&action=showHome");
            exit;
        }

        $post = $this->postModel->getById($postId);
        if (!$post) {
            header("Location: index.php?controller=post&action=showHome");
            exit;
        }

        $currentUserId = (int)$_SESSION['user_id'];
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPostOwner = ((int)$post->getUserId() === $currentUserId);

        if (!$isSystemAdmin && !$isPostOwner) {
            header("Location: index.php?controller=post&action=showHome");
            exit;
        }

        $errorMessage = $_GET['error'] ?? '';

        include __DIR__ . "/../View/editpost_view.php";
        die();
    }

    public function updatePost()
    {
        $postId = (int)($_POST['postId'] ?? 0);
        $isFormSubmit = isset($_POST['edit_form_submit']);

        $fail = function($message = 'Update failed') use ($isFormSubmit, $postId) {
            if ($isFormSubmit) {
                $targetPostId = max(0, (int)$postId);
                header("Location: index.php?controller=post&action=showEditForm&id=$targetPostId&error=" . urlencode($message));
                exit;
            }

            echo "fail";
            exit;
        };

        if (!isset($_SESSION['user_id'])) {
            $fail('Please login first');
        }

        if (!$postId) {
            $fail('Invalid post ID');
        }

        $existingPost = $this->postModel->getById($postId);
        if (!$existingPost) {
            $fail('Post not found');
        }

        $currentUserId = (int)$_SESSION['user_id'];
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPostOwner = ((int)$existingPost->getUserId() === $currentUserId);

        if (!$isSystemAdmin && !$isPostOwner) {
            $fail('You do not have permission to edit this post');
        }

        $title = trim($_POST['title'] ?? $existingPost->getTitle());
        $content = trim($_POST['content'] ?? $existingPost->getContent());

        if ($title === '' || $content === '') {
            $fail('Title and content are required');
        }

        $price = array_key_exists('price', $_POST) ? $_POST['price'] : $existingPost->getPrice();
        $condition = $_POST['condition'] ?? $existingPost->getCondition();
        $location = $_POST['location'] ?? $existingPost->getLocation();
        $brand = array_key_exists('brand', $_POST) ? $_POST['brand'] : $existingPost->getBrand();
        $status = $_POST['status'] ?? $existingPost->getStatus();

        $post = new Post(
            $postId,
            null,
            null,
            null,
            $title,
            $content,
            $price,
            $condition,
            $location,
            $brand,
            $status
        );

        $result = $this->postModel->update($post);

        if ($isFormSubmit) {
            if ($result) {
                header("Location: index.php?controller=post&action=showHome");
                exit;
            }

            $fail('Cannot update post');
        }

        echo $result ? "success" : "fail";
        exit;
    }

    public function detail()
    {
        $postId = $_GET['id'] ?? 0;
        $post   = $this->postModel->getById($postId);

        if (!$post) {
            $this->redirect('/index.php', 'Bai viet nay khong ton tai hoac da bi xoa!');
        }

        $reactions = $this->reactionModel->selectReactionsForPost($postId);
        include_once __DIR__ . "/../View/home.php";
    }

    public function editPost() {
        $postId = $_GET["id"] ?? null;
        $post = $this->postModel->getById($postId);

        if (!$post) {
            echo "Post not found";
            exit;
        }
        
        
        include "MVC/View/Post/edit_view.php";
    }
    
    // ====================================================================
    // ================= KHU VUC DANH RIENG CHO ADMIN =====================
    // ====================================================================

    public function list()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }

        $posts = $this->postModel->getAll();
        include_once __DIR__ . "/../View/Admin/Post/list.php";
    }

    public function adminDelete()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }

        if (isset($_GET['id'])) {
            $postId = (int)$_GET['id'];
            $this->postModel->delete($postId);
            $_SESSION['flash_message'] = "Da xoa bai viet thanh cong!";
            header("Location: index.php?controller=post&action=list");
            exit();
        }
    }

    public function adminDetail()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }

        $postId = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->getById($postId);

        if (!$post) {
            $_SESSION['flash_message'] = "Bai viet khong ton tai!";
            header("Location: index.php?controller=post&action=list");
            exit();
        }

        $comments = $this->commentModel->fetchByField('PostID', $postId);
        include_once __DIR__ . "/../View/Admin/Post/admin_detail.php";
    }
}
?>
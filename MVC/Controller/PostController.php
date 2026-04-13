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

        // Nếu KHÔNG phải product → reset hết
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

        // Lưu file media nếu có upload
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

        // Lấy thông tin user
        if ($userid) {
            $userInfo = $this->userModel->getById($userid);
            $username = $userInfo ? ($userInfo['Username'] ?? 'Guest') : "Guest";
        } else {
            $username = "Guest";
        }

        $reactions_forPost = [];
        foreach ($posts as $post) {
            $reactions_forPost[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
        }

        $isSameUser = [];
        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $isSameUser[$postId] = false;

            foreach (($reactions_forPost[$postId] ?? []) as $reaction) {
                if ($reaction->getUserId() == $userid) {
                    $isSameUser[$postId] = true;
                    break;
                }
            }
        }

        $comments = [];
        foreach ($posts as $post) {
            $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
        }

        $commentTree = [];
        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $commentTree[$postId] = [];

            foreach (($comments[$postId] ?? []) as $c) {
                $parent = $c->getParentCommentId();
                $commentTree[$postId][$parent][] = $c;
            }
        }

        $reactions_forComment = [];
        foreach ($comments as $postComments) {
            foreach ($postComments as $comment) {
                $commentId = $comment->getCommentId();
                $reactions_forComment[$commentId] = $this->reactionModel->selectReactionsForComment($commentId);
            }
        }

        $isSameUser_reactCmt = [];
        foreach ($comments as $postComments) {
            foreach ($postComments as $comment) {
                $commentId = $comment->getCommentId();
                $isSameUser_reactCmt[$commentId] = false;

                foreach (($reactions_forComment[$commentId] ?? []) as $reaction) {
                    if ($reaction->getUserId() == $userid) {
                        $isSameUser_reactCmt[$commentId] = true;
                        break;
                    }
                }
            }
        }

        // Load media cho từng post để hiển thị ảnh/video trên Home
        $mediaForPost = [];
        foreach ($posts as $post) {
            $mediaForPost[$post->getPostId()] = $this->mediaModel->getByPostId($post->getPostId());
        }
    

        // =======================
        // 🔥 RENDER VIEW
        // =======================
        include_once __DIR__ . "/../View/home.php";
    }
   }

       

    public function PostAction(){
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

            case "getPostsByCategoryId":
                $this->getPostsByCategoryId();
                break;

            case "deletePost":
                $this->deletePost();
                break;

            case "updatePost":
                $this->updatePost();
                break;

            case "detail":
                $this->detail();
                break;

            case "list":
                $this->list();
                break;

            case "adminDelete":
                $this->adminDelete();
                break;

            case "adminDetail":
                $this->adminDetail();
                break;

            default:
                $this->showHome();
                break;
        }
    }

    public function getAllPosts()
    {
        return $this->postModel->getAll() ?? [];
    }

    public function getPostById($postId)
    {
        return $this->postModel->getById($postId);
    }

    public function getPostsByUserId($userId = null)
    {
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $posts  = $this->postModel->fetchByField('UserID', $userId);
            include_once __DIR__ . "/../View/postview.php";
            return $posts;
        }

        return [];
    }

    public function getPostsByGroupId($groupId = null)
    {
        if (isset($_GET['group_id'])) {
            $groupId = $_GET['group_id'];
            $posts   = $this->postModel->fetchByField('GroupID', $groupId);
            include_once __DIR__ . "/../View/postview.php";
            return $posts;
        }

        return [];
    }

    public function getPostsByCategoryId()
    {
        if (isset($_GET['category_id'])) {
            $categoryId = $_GET['category_id'];
            $posts      = $this->postModel->fetchByField('CategoryID', $categoryId);

            $userid = $_SESSION['user_id'] ?? null;

            $reactions_forPost = [];
            foreach ($posts as $post) {
                $reactions_forPost[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
            }

            $isSameUser = [];
            foreach ($posts as $post) {
                $postId = $post->getPostId();
                $isSameUser[$postId] = false;

                foreach (($reactions_forPost[$postId] ?? []) as $reaction) {
                    if ($reaction->getUserId() == $userid) {
                        $isSameUser[$postId] = true;
                        break;
                    }
                }
            }

            $comments = [];
            foreach ($posts as $post) {
                $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
            }

            $commentTree = [];
            foreach ($posts as $post) {
                $postId = $post->getPostId();
                $commentTree[$postId] = [];

                foreach (($comments[$postId] ?? []) as $c) {
                    $parent = $c->getParentCommentId();
                    $commentTree[$postId][$parent][] = $c;
                }
            }

            $reactions_forComment = [];
            foreach ($comments as $postComments) {
                foreach ($postComments as $comment) {
                    $commentId = $comment->getCommentId();
                    $reactions_forComment[$commentId] = $this->reactionModel->selectReactionsForComment($commentId);
                }
            }

            $isSameUser_reactCmt = [];
            foreach ($comments as $postComments) {
                foreach ($postComments as $comment) {
                    $commentId = $comment->getCommentId();
                    $isSameUser_reactCmt[$commentId] = false;

                    foreach (($reactions_forComment[$commentId] ?? []) as $reaction) {
                        if ($reaction->getUserId() == $userid) {
                            $isSameUser_reactCmt[$commentId] = true;
                            break;
                        }
                    }
                }
            }

            $mediaForPost = [];
            foreach ($posts as $post) {
                $mediaForPost[$post->getPostId()] = $this->mediaModel->getByPostId($post->getPostId());
            }

            include_once __DIR__ . "/../View/home.php";
            return $posts;
        }

        return [];
    }

    public function deletePost()
    {
        $postId = $_POST['postId'] ?? null;

        if (!$postId) {
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

    public function updatePost()
    {
        $postId = $_POST['postId'] ?? null;
        if (!$postId) {
            echo "fail";
            exit;
        }

        $post = new Post(
            $postId,
            null,
            null,
            null,
            $_POST['title'] ?? null,
            $_POST['content'] ?? null,
            $_POST['price'] ?? null,
            $_POST['condition'] ?? 'good',
            $_POST['location'] ?? 'other',
            $_POST['brand'] ?? null,
            $_POST['status'] ?? 'selling'
        );

        $result = $this->postModel->update($post);
        echo $result ? "success" : "fail";
        exit;
    }

    public function detail()
    {
        $postId = $_GET['id'] ?? 0;
        $post   = $this->postModel->getById($postId);

        if (!$post) {
            $this->redirect('/Group12-Social-Media-Website/index.php', 'Bài viết này không tồn tại hoặc đã bị xóa!');
        }

        $reactions = $this->reactionModel->selectReactionsForPost($postId);
        include_once __DIR__ . "/../View/home.php";
    }

    // ====================================================================
    // ================= KHU VỰC DÀNH RIÊNG CHO ADMIN =====================
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
            $_SESSION['flash_message'] = "Đã xóa bài viết thành công!";
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
            $_SESSION['flash_message'] = "Bài viết không tồn tại!";
            header("Location: index.php?controller=post&action=list");
            exit();
        }

        $comments = $this->commentModel->fetchByField('PostID', $postId);
        include_once __DIR__ . "/../View/Admin/Post/admin_detail.php";
    }
}
?>
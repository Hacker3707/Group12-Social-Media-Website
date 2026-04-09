
<?php
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../../Entity/Media.php";
include_once __DIR__ . "/../Model/CategoryModel.php";
include_once __DIR__ . "/../Model/CommentModel.php";
include_once __DIR__ . "/../Model/UserModel.php";

class PostController extends AppController {
    private $postModel;
    private $reactionModel;
    private $categoryModel;
    private $commentModel;
    private $userModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->reactionModel = new ReactionModel();
        $this->categoryModel = new CategoryModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
    }

    public function createPost(){

       
        $userId = $_SESSION['user_id'];
        $groupId = null;
        $categoryId = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;

        $title = $_POST['title'];
        $content = $_POST['content'];
        $isProduct = $_POST['is_product'] ?? 1;
        $price = $_POST['price'] ?? null;
        $condition = $_POST['condition'] ?? 'good';
        $location = $_POST['location'] ?? 'other';
        $brand = $_POST['brand'] ?? null;
        $status = 'selling';
        //  nếu KHÔNG phải product → reset hết
if($isProduct == 0){
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

        $mediaList = [];

        if(isset($_FILES['media']) && $_FILES['media']['error'] == 0){

            $ext = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . "." . $ext;

           $uploadDir = __DIR__ . "/../../uploads/";

// 🔥 tạo folder nếu chưa có
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
$fileName = uniqid() . "." . $ext;

// đường dẫn thật để lưu file
$uploadPath = $uploadDir . $fileName;

// upload file
move_uploaded_file($_FILES['media']['tmp_name'], $uploadPath);

// đường dẫn để lưu DB / hiển thị
$dbPath = "uploads/" . $fileName;

// tạo media object
$media = new Media(null, $userId, null, "image", $dbPath);
            $media = new Media(null, $userId, null, "image", $uploadPath);
            $mediaList[] = $media;
        }

       $result = $this->postModel->insertPost($post);
        
        if(!$result){
        echo "fail";
        die(mysqli_error($this->postModel->getConnection()));
        }
$newPostId = $this->postModel->getLastInsertId();
echo "success:" . $newPostId;
                exit;
    }

    // render page
   public function showHome(){

    // 🔥 Lấy filter nếu có
    $categoryId = $_SESSION['category_filter'] ?? null;

    if($categoryId){
        $posts = $this->postModel->fetchByField('CategoryID', $categoryId);
    } else {
        $posts = $this->postModel->getAll();
    }

    $posts = $posts ?? [];

    $userid = $_SESSION['user_id'] ?? null;

   
    // =======================
    // 🔥 REACTIONS POST
    // =======================
    $reactions_forPost = [];

    foreach($posts as $post){
        $postId = $post->getPostId();

        $reactions_forPost[$postId] =
            $this->reactionModel->selectReactionsForPost($postId);
    }

    // =======================
    // 🔥 CHECK USER LIKE POST
    // =======================
    $isSameUser = [];

    foreach($posts as $post){

        $postId = $post->getPostId();
        $isSameUser[$postId] = false;

        foreach($reactions_forPost[$postId] as $reaction){
            if($reaction->getUserId() == $userid){
                $isSameUser[$postId] = true;
                break;
            }
        }
    }

        $comments = [];
        foreach($posts as $post) {
            $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
        }


        $commentTree = [];

        foreach($posts as $post){

            $postId = $post->getPostId();
            $commentTree[$postId] = [];

            foreach($comments[$postId] as $c){
                $parent = $c->getParentCommentId();
                $commentTree[$postId][$parent][] = $c;
            }
        }

        $reactions_forComment = [];

    foreach($posts as $post){
        $postId = $post->getPostId();

        $comments[$postId] =
            $this->commentModel->fetchByField('PostID', $postId);
    }

    // =======================
    // 🔥 REACTION COMMENT
    // =======================
    $reactions_forComment = [];

    foreach($comments as $postComments){

        foreach($postComments as $comment){

            $commentId = $comment->getCommentId();

            $reactions_forComment[$commentId] =
                $this->reactionModel->selectReactionsForComment($commentId);
        }
    }

    // =======================
    // 🔥 CHECK USER LIKE COMMENT
    // =======================
    $isSameUser_reactCmt = [];

    foreach($comments as $postComments){

        foreach($postComments as $comment){

            $commentId = $comment->getCommentId();
            $isSameUser_reactCmt[$commentId] = false;

            foreach($reactions_forComment[$commentId] as $reaction){
                if($reaction->getUserId() == $userid){
                    $isSameUser_reactCmt[$commentId] = true;
                    break;
                }
            }
        }
    }

    // =======================
    // 🔥 RENDER VIEW
    // =======================
    include_once __DIR__ . "/../View/home.php";
}

       

    public function PostAction(){

        $action = $_GET['action'] ?? "home";

        switch($action){

            case "createPost":
                $this->createPost();
                break;

            case "home":
                 unset($_SESSION['category_filter']); // 🔥 tránh bị kẹt filter
                $this->showHome();
                break;
            case "create":
            $this->showCreateForm();
             break;

        }

    }

    public function getAllPosts() {
        $posts = $this->postModel->getAll() ?? [];

        $reactions = [];
        foreach($posts as $post) {
            $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
        }

        $comments = [];
        foreach($posts as $post) {
            $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
        }

        include_once __DIR__ . "/../View/home.php";
    }

    public function getPostById($postId) {
        return $this->postModel->getById($postId);
    }

    public function getPostsByUserId($userId) {
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $posts = $this->postModel->fetchByField('UserID', $userId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByGroupId($groupId) {
        if (isset($_GET['group_id'])) {
            $groupId = $_GET['group_id'];
            $posts = $this->postModel->fetchByField('GroupID', $groupId);
            include_once "../View/postview.php";
        }
        return [];
    }

    public function getPostsByCategoryId() {

    if (isset($_GET['category_id'])) {

        $categoryId = $_GET['category_id'];

        $posts = $this->postModel->fetchByField('CategoryID', $categoryId);

       // ✅ redirect về showHome và xử lý filter ở đó
        $_SESSION['category_filter'] = $categoryId;
        $this->showHome(); 
    }

    return [];
}

    public function deletePost(){

        $postId = $_POST['postId'] ?? null;

        if(!$postId){
            echo "fail";
            exit;
        }

        $result = $this->postModel->delete($postId);

        echo $result ? "success" : "fail";

        exit;
    }

    public function showCreateForm(){
        $categories = $this->categoryModel->getAll(); // 👈 lấy từ DB

        include __DIR__ . "/../View/createpost_view.php";
        
        die();
    }

    public function updatePost() {
        $postId = $_POST['postId'] ?? null;

        if(!$postId){
            echo "fail";
            exit;
        }

        $title = $_POST['title'] ?? null;
        $content = $_POST['content'] ?? null;
        $price = $_POST['price'] ?? null;
        $condition = $_POST['condition'] ?? 'good';
        $location = $_POST['location'] ?? 'other';
        $brand = $_POST['brand'] ?? null;
        $status = $_POST['status'] ?? 'selling';

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

        echo $result ? "success" : "fail";
        exit;
    }

    public function detail() {

        $postId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;
        
        $post = $this->postModel->getById($postId);
        
        if (!$post) {
            $this->redirect('/Group12-Social-Media-Website/index.php', 'Bài viết này không tồn tại hoặc đã bị xóa!');
        }

        $reactions = $this->reactionModel->selectReactionsForPost($postId);

        include_once "MVC/View/home.php";
    }

}
?>

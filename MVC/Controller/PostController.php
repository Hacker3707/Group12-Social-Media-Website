
<?php
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../../Entity/Media.php";
class PostController extends AppController {
    private $postModel;
    private $reactionModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->reactionModel = new ReactionModel();
    }

    public function createPost(){

       
        $userId = 2;
        $groupId = null;
       $categoryId = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;

        $title = $_POST['title'];
        $content = $_POST['content'];
        $price = $_POST['price'] ?? null;
        $condition = $_POST['condition'] ?? 'good';
        $location = $_POST['location'] ?? 'other';
        $brand = $_POST['brand'] ?? null;
        $status = 'selling';

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

            $uploadPath = "../../uploads/" . basename($_FILES['media']['name']);

            move_uploaded_file($_FILES['media']['tmp_name'], $uploadPath);

           $media = new Media(null, $userId, null, "image", $uploadPath);
            $mediaList[] = $media;
        }

       $result = $this->postModel->insertPost($post);
        
        if(!$result){
        echo "fail";
        die(mysqli_error($this->postModel->getConnection()));
        }

        echo "success";
                exit;
    }

    // render page
    public function showHome(){

        $posts = $this->postModel->getAll() ?? [];
        
        $reactions = [];
        foreach($posts as $post) {
            $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
        }

        include __DIR__ . "/../View/home.php";
    }

    public function PostAction(){

        $action = $_GET['action'] ?? "home";

        switch($action){

            case "createPost":
                $this->createPost();
                break;

            case "home":
                $this->showHome();
                break;

        }

    }

    public function getAllPosts() {
        $posts = $this->postModel->getAll() ?? [];

        $reactions = [];
        foreach($posts as $post) {
            $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
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

        include_once __DIR__ . "/../View/home.php";
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
}
?>

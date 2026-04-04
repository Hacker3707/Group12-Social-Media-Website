<?php 
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/../Model/CategoryModel.php";
include_once __DIR__ . "/../Model/UserModel.php";
include_once __DIR__ . "/../Model/GroupModel.php";
include_once __DIR__ . "/../Model/ReactionModel.php";

class SearchController extends AppController {

    private $postModel;
    private $categoryModel;
    private $groupModel;
    private $userModel;
    private $reactionModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->groupModel = new GroupModel();
        $this->userModel = new UserModel();
        $this->reactionModel = new ReactionModel();
    }

    public function find() {
        $keyword = $_GET['searchResults'] ?? '';
        if(empty($keyword)){
            $posts = [];
            $categories = [];
            $groups = [];
            $users = [];
            $reactions = [];
        } else {
            $posts = $this->postModel->searchPosts($keyword);
            $categories = $this->categoryModel->searchCategories($keyword);
            $groups = $this->groupModel->searchGroups($keyword);
            $users = $this-> userModel->searchUsers($keyword);
            foreach($posts as $post) {
            $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
            }
        }

        include_once __DIR__ . "/../View/Search/search_view.php";
    }

    public function searchPosts() {
        $keyword = $_GET['searchResults'] ?? '';
        if (empty($keyword)) {
            $posts = [];
        } else {
            $posts = $this->postModel->searchPosts($keyword);
            $reactions = [];
            foreach($posts as $post) {
                $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
            }
        }
        include_once __DIR__ . "/../View/Search/search_post.php";
    }

    public function searchGroups() {
        $keyword = $_GET['searchResults'] ?? '';
        if (empty($keyword)) {
            $groups = [];
        } else {
            $groups = $this->groupModel->findGroups($keyword);
        }
        include_once __DIR__ . "/../View/Search/search_group.php";
    }

    public function searchCategories() {
        $keyword = $_GET['searchResults'] ?? '';
        if (empty($keyword)) {
            $categories = [];
        } else {
            $categories = $this->categoryModel->searchCategories($keyword);
        }
        include_once __DIR__ . "/../View/Search/search_category.php";
    }

    public function searchUsers() {
        $keyword = $_GET['searchResults'] ?? '';
        if (empty($keyword)) {
            $users = [];
        } else {
            $users = $this->userModel->searchUsers($keyword);
        }
        include_once __DIR__ . "/../View/Search/search_user.php";
    }
}
?>
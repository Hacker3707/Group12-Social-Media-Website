<?php 
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/../Model/CategoryModel.php";
include_once __DIR__ . "/../Model/UserModel.php";
include_once __DIR__ . "/../Model/GroupModel.php";
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../Model/CommentModel.php";
include_once __DIR__ . "/../Model/MediaModel.php";

class SearchController extends AppController {

    private $postModel;
    private $categoryModel;
    private $groupModel;
    private $userModel;
    private $reactionModel;
    private $commentModel;
    private $mediaModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->groupModel = new GroupModel();
        $this->userModel = new UserModel();
        $this->reactionModel = new ReactionModel();
        $this->commentModel = new CommentModel();
        $this->mediaModel = new MediaModel();
    }

    private function paginateItems(array $items, int $page, int $perPage): array {
        $totalItems = count($items);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $currentPage = max(1, min($page, $totalPages));
        $offset = ($currentPage - 1) * $perPage;
        $pagedItems = array_slice($items, $offset, $perPage);

        return [$pagedItems, $currentPage, $totalPages, $totalItems];
    }

    public function find() {

        $userid = $_SESSION['user_id'] ?? null;
        $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        

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
            $users = $this-> userModel->searchUsers($keyword, $isAdmin);
            foreach($posts as $post) {
            $reactions[$post->getPostId()] = $this->reactionModel->selectReactionsForPost($post->getPostId());
            }
            $isSameUser_reactPost = [];

        foreach($posts as $post){

            $postId = $post->getPostId();
            $isSameUser_reactPost[$postId] = false;

            foreach($reactions[$postId] ?? [] as $reaction){
                if($reaction->getUserId() == $userid){
                    $isSameUser_reactPost[$postId] = true;
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

        foreach($comments as $postComments){

            foreach($postComments as $comment){

                $commentId = $comment->getCommentId();

                $reactions_forComment[$commentId] =
                    $this->reactionModel->selectReactionsForComment($commentId);

            }

        }

        $isSameUser_reactPost_reactCmt = [];

            foreach($comments as $postComments){

                foreach($postComments as $comment){

                    $commentId = $comment->getCommentId();
                    $isSameUser_reactPost_reactCmt[$commentId] = false;

                    foreach(($reactions_forComment[$commentId] ?? []) as $reaction){
                        if($reaction->getUserId() == $userid){
                            $isSameUser_reactPost_reactCmt[$commentId] = true;
                            break;
                        }
                    }

                }

            }
        }

        include_once __DIR__ . "/../View/Search/search_view.php";
    }

    public function searchPosts() {
        $userid = $_SESSION['user_id'] ?? null;
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;

        $posts = [];
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;
        $reactions_forPost = [];
        $isSameUser_reactPost = [];
        $comments = [];
        $commentTree = [];
        $reactions_forComment = [];
        $isSameUser_reactCmt = [];
        $mediaForPost = [];
        $canDel_EditPost = [];

        if (empty($keyword)) {
            $posts = [];
        } else {
            $allPosts = $this->postModel->searchPosts($keyword);
            list($posts, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allPosts, $page, $perPage);

            foreach($posts as $post) {
                $postId = $post->getPostId();

                $reactions_forPost[$postId] = $this->reactionModel->selectReactionsForPost($postId);
                $isSameUser_reactPost[$postId] = false;
                $canDel_EditPost[$postId] = ($userid && (int)$post->getUserId() === (int)$userid) || $isSystemAdmin;

                foreach (($reactions_forPost[$postId] ?? []) as $reaction) {
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

                    foreach (($reactions_forComment[$commentId] ?? []) as $reaction) {
                        if ($userid && $reaction->getUserId() == $userid) {
                            $isSameUser_reactCmt[$commentId] = true;
                            break;
                        }
                    }
                }

                $mediaForPost[$postId] = $this->mediaModel->getByPostId($postId);
            }
        }
        include_once __DIR__ . "/../View/Search/search_post.php";
    }

    public function searchGroups() {
        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;

        if (empty($keyword)) {
            $groups = [];
        } else {
            $allGroups = $this->groupModel->findGroups($keyword);
            list($groups, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allGroups, $page, $perPage);
        }
        include_once __DIR__ . "/../View/Search/search_group.php";
    }

    public function searchCategories() {
        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;

        if (empty($keyword)) {
            $categories = [];
        } else {
            $allCategories = $this->categoryModel->searchCategories($keyword);
            list($categories, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allCategories, $page, $perPage);
        }
        include_once __DIR__ . "/../View/Search/search_category.php";
    }

    public function searchUsers() {
        $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;

        if (empty($keyword)) {
            $users = [];
        } else {
            $allUsers = $this->userModel->searchUsers($keyword, $isAdmin);
            list($users, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allUsers, $page, $perPage);
        }
        include_once __DIR__ . "/../View/Search/search_user.php";
    }
}
?>
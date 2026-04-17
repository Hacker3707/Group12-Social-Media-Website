<?php 
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/../Model/CategoryModel.php";
include_once __DIR__ . "/../Model/UserModel.php";
include_once __DIR__ . "/../Model/GroupModel.php";
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../Model/CommentModel.php";
include_once __DIR__ . "/../Model/MediaModel.php";
include_once __DIR__ . "/../Model/FollowModel.php";

class SearchController extends AppController {

    private $postModel;
    private $categoryModel;
    private $groupModel;
    private $userModel;
    private $reactionModel;
    private $commentModel;
    private $mediaModel;
    private $followModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->groupModel = new GroupModel();
        $this->userModel = new UserModel();
        $this->reactionModel = new ReactionModel();
        $this->commentModel = new CommentModel();
        $this->mediaModel = new MediaModel();
        $this->followModel = new FollowModel();
    }

    private function paginateItems(array $items, int $page, int $perPage): array {
        $totalItems = count($items);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $currentPage = max(1, min($page, $totalPages));
        $offset = ($currentPage - 1) * $perPage;
        $pagedItems = array_slice($items, $offset, $perPage);

        return [$pagedItems, $currentPage, $totalPages, $totalItems];
    }

    private function containsKeyword($text, string $keyword): bool {
        $needle = trim($keyword);
        if ($needle === '') {
            return false;
        }

        $haystack = (string)$text;

        if (function_exists('mb_stripos')) {
            return mb_stripos($haystack, $needle, 0, 'UTF-8') !== false;
        }

        return stripos($haystack, $needle) !== false;
    }

    private function filterUsersByUsername(array $users, string $keyword): array {
        return array_values(array_filter($users, function($user) use ($keyword) {
            if (!is_object($user) || !method_exists($user, 'getUsername')) {
                return false;
            }

            return $this->containsKeyword($user->getUsername(), $keyword);
        }));
    }

    private function filterGroupsByName(array $groups, string $keyword): array {
        return array_values(array_filter($groups, function($group) use ($keyword) {
            if (!is_object($group) || !method_exists($group, 'getGroupName')) {
                return false;
            }

            return $this->containsKeyword($group->getGroupName(), $keyword);
        }));
    }

    private function filterGroupRowsByName(array $groups, string $keyword): array {
        return array_values(array_filter($groups, function($group) use ($keyword) {
            $groupName = $group['GroupName'] ?? '';
            return $this->containsKeyword($groupName, $keyword);
        }));
    }

    private function filterPostsByContent(array $posts, string $keyword): array {
        return array_values(array_filter($posts, function($post) use ($keyword) {
            if (!is_object($post) || !method_exists($post, 'getContent')) {
                return false;
            }

            return $this->containsKeyword($post->getContent(), $keyword);
        }));
    }

    private function filterCategoriesByName(array $categories, string $keyword): array {
        return array_values(array_filter($categories, function($category) use ($keyword) {
            if (!is_object($category) || !method_exists($category, 'getCategoryName')) {
                return false;
            }

            return $this->containsKeyword($category->getCategoryName(), $keyword);
        }));
    }

    public function find() {

    $userid = $_SESSION['user_id'] ?? null;
    $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    $isPageAdmin = false;

    $navbarCategories = $this->categoryModel->getAll() ?? [];

    $keyword = $_GET['searchResults'] ?? '';

    $userFollowStatus = []; // 🔥 Mảng lưu follow status cho mỗi user

    // 🔥 Check follow status cho mỗi user (MVC: Model -> Controller -> View)

    $posts = [];
    $categories = [];
    $groups = [];
    $users = [];

    $reactions_forPost = [];
    $isSameUser_reactPost = [];
    $comments = [];
    $commentTree = [];
    $reactions_forComment = [];
    $isSameUser_reactCmt = [];
    $mediaForPost = [];
    $canDel_EditPost = [];

    if (!empty($keyword)) {

        $posts = $this->postModel->searchPosts($keyword);
        $categories = $this->filterCategoriesByName(
            $this->categoryModel->searchCategories($keyword), $keyword
        );

        $groups = $this->filterGroupRowsByName(
            $this->groupModel->searchGroups($keyword), $keyword
        );

        $users = $this->userModel->searchUsers($keyword);

        if ($userid) {
        foreach ($users as $user) {
            $userFollowStatus[$user->getUserId()] = $this->followModel->exists($userid, $user->getUserId()); 
            } 
        }

        foreach ($posts as $post) {

            $postId = $post->getPostId();

            // quyền edit/delete
            $canDel_EditPost[$postId] =
                ($userid && (int)$post->getUserId() === (int)$userid) || $isSystemAdmin;

            // reactions post
            $reactions_forPost[$postId] =
                $this->reactionModel->selectReactionsForPost($postId);

            $isSameUser_reactPost[$postId] = false;

            foreach ($reactions_forPost[$postId] as $reaction) {
                if ($userid && $reaction->getUserId() == $userid) {
                    $isSameUser_reactPost[$postId] = true;
                    break;
                }
            }

            // comments
            $comments[$postId] =
                $this->commentModel->fetchByField('PostID', $postId);

            $commentTree[$postId] = [];

            foreach ($comments[$postId] as $c) {

                $parent = $c->getParentCommentId();
                $commentId = $c->getCommentId();

                $commentTree[$postId][$parent][] = $c;

                // reaction comment
                $reactions_forComment[$commentId] =
                    $this->reactionModel->selectReactionsForComment($commentId);

                $isSameUser_reactCmt[$commentId] = false;

                foreach ($reactions_forComment[$commentId] as $reaction) {
                    if ($userid && $reaction->getUserId() == $userid) {
                        $isSameUser_reactCmt[$commentId] = true;
                        break;
                    }
                }
            }

            // media
            $mediaForPost[$postId] =
                $this->mediaModel->getByPostId($postId);
        }
    }

    include_once __DIR__ . "/../View/Search/search_view.php";
}

    public function searchPosts() {


        $userid = $_SESSION['user_id'] ?? null;
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = false;

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
            $allPosts = $this->filterPostsByContent($this->postModel->searchPosts($keyword), $keyword);
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
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = false;

        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;

        if (empty($keyword)) {
            $groups = [];
        } else {
            $allGroups = $this->filterGroupsByName($this->groupModel->findGroups($keyword), $keyword);
            list($groups, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allGroups, $page, $perPage);
        }
        include_once __DIR__ . "/../View/Search/search_group.php";
    }

    public function searchCategories() {
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = false;

        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;

        if (empty($keyword)) {
            $categories = [];
        } else {
            $allCategories = $this->filterCategoriesByName($this->categoryModel->searchCategories($keyword), $keyword);
            list($categories, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allCategories, $page, $perPage);
        }
        include_once __DIR__ . "/../View/Search/search_category.php";
    }

    public function searchUsers() {
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = false;

        $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $currentUserId = $_SESSION['user_id'] ?? null;
        
        $keyword = $_GET['searchResults'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $currentPage = 1;
        $totalPages = 1;
        $totalItems = 0;

        $userFollowStatus = []; // 🔥 Mảng lưu follow status cho mỗi user

        if (empty($keyword)) {
            $users = [];
        } else {
            $allUsers = $this->filterUsersByUsername($this->userModel->searchUsers($keyword, $isAdmin), $keyword);
            list($users, $currentPage, $totalPages, $totalItems) = $this->paginateItems($allUsers, $page, $perPage);
            
            // 🔥 Check follow status cho mỗi user (MVC: Model -> Controller -> View)
            if ($currentUserId) {
                foreach ($users as $user) {
                    $userFollowStatus[$user->getUserId()] = $this->followModel->exists($currentUserId, $user->getUserId());
                }
            }
        }
        
        include_once __DIR__ . "/../View/Search/search_user.php";
    }
}
?>
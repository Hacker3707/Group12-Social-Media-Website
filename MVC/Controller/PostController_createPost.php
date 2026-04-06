// ================= SỬA HÀM createPost() TRONG PostController.php =================
// Thay thế toàn bộ hàm createPost() hiện tại bằng đoạn này

    public function createPost(){

        $userId    = $_SESSION['user_id'];
        $groupId   = null;
        $categoryId = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;

        $title     = $_POST['title'];
        $content   = $_POST['content'];
        $price     = !empty($_POST['price']) ? $_POST['price'] : null;
        $condition = $_POST['condition'] ?? 'good';
        $location  = $_POST['location']  ?? 'other';
        $brand     = !empty($_POST['brand']) ? $_POST['brand'] : null;
        $status    = 'selling';

        $post = new Post(
            null, $userId, $groupId, $categoryId,
            $title, $content, $price, $condition, $location, $brand, $status
        );

        $result = $this->postModel->insertPost($post);

        if (!$result) {
            echo "fail";
            exit;
        }

        // ✅ Trả về post_id vừa tạo để JS biết mà upload ảnh
        $newPostId = $this->postModel->getLastInsertId();
        echo "success:" . $newPostId;
        exit;
    }
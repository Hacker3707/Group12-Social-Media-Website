<form id="postForm" method="POST" enctype="multipart/form-data">

<div class="container mt-3">

<!-- 🧾 POST INFO -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-primary text-white">
    📝 Create Post
  </div>

  <div class="card-body">

    <div class="form-group">
      <label>👤 Username</label>
      <input type="text" name="username" class="form-control" placeholder="Enter username">
    </div>

    <div class="form-group">
      <label>📌 Title</label>
      <input type="text" name="title" class="form-control" placeholder="Post title">
    </div>
   <!-- 📝 CATEGORY -->
    <div class="form-group">
    <label>📂 Category</label>
    <select name="category_id" class="form-control category-select">
       <option value="">-- Choose category --</option>

    <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat->getCategoryID() ?>">
            <?= $cat->getCategoryName() ?>
        </option>
    <?php endforeach; ?>

  </select>
</div>

    <div class="form-group">
      <label>📄 Content</label>
      <textarea name="content" class="form-control" rows="4"></textarea>
    </div>

  </div>
</div>

<!-- 🛍️ PRODUCT INFO -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-success text-white">
    🛍️ Product Details
  </div>

  <div class="card-body">

    <div class="form-group">
      <label>💰 Price (VND)</label>
      <input type="number" name="price" class="form-control">
    </div>

    <div class="form-group">
      <label>📦 Condition</label>
      <select name="condition" class="form-control">
        <option value="new">New</option>
        <option value="like_new">Like New</option>
        <option value="very_good">Very Good</option>
        <option value="good" selected>Good</option>
        <option value="fair">Fair</option>
        <option value="for_parts">For Parts</option>
      </select>
    </div>

    <div class="form-group">
      <label>📍 Location</label>
      <select name="location" class="form-control">
        <option value="hcm">Ho Chi Minh</option>
        <option value="hanoi">Ha Noi</option>
        <option value="danang">Da Nang</option>
        <option value="cantho">Can Tho</option>
        <option value="haiphong">Hai Phong</option>
        <option value="other" selected>Other</option>
      </select>
    </div>

    <div class="form-group">
      <label>🏷️ Brand</label>
      <input type="text" name="brand" class="form-control" placeholder="e.g. Nike, Apple">
    </div>

    <div class="form-group">
      <label>📊 Status</label>
      <select name="status" class="form-control">
        <option value="selling" selected>Selling</option>
        <option value="reserved">Reserved</option>
        <option value="sold">Sold</option>
        <option value="hidden">Hidden</option>
      </select>
    </div>

  </div>
</div>

<!-- 📸 MEDIA -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-info text-white">
    📸 Media
  </div>

  <div class="card-body">

    <div class="custom-file">
      <input type="file" name="media" class="custom-file-input">
      <label class="custom-file-label">Choose file</label>
    </div>

  </div>
</div>

<!-- 🔘 BUTTON (GIỮ STYLE CŨ CỦA BẠN) -->
<div class="row" id="button-group">
    <button type="submit" class="btn btn-primary" style="margin-right:10px;">
        🚀 Post
    </button>

    <a href="/index.php?controller=post&action=showHome" class="btn btn-secondary">
        Cancel
    </a>
</div>


</div>
</form>

<div id="result"></div>

<script>

console.log("JS loaded");

document.getElementById("postForm").addEventListener("submit", function(e){

    e.preventDefault();


    let formData = new FormData(this);

    // ✅ validate phải nằm TRONG function
    if(!formData.get("category_id")){
        alert("Please choose category");
        return;
    }

    fetch("/index.php?controller=post&action=createPost",{
        method:"POST",
        body:formData
    })
    .then(res=>res.text())
    .then(data=>{

        console.log("Response:", data);

        if(data.trim().includes("success")){

            document.getElementById("result").innerHTML =
            "<div class='alert alert-success'>Post created successfully</div>";

            setTimeout(()=>{
                window.location.href = "/index.php?controller=post&action=showHome";
            },1500);

        }else{

            document.getElementById("result").innerHTML =
            "<div class='alert alert-danger'>Failed to create post</div>";

        }

    })
    .catch(err=>{
        console.error("ERROR:", err);
    });

});


</script>
<!-- ================= THAY THẾ TOÀN BỘ THẺ <script> ĐẦU TIÊN TRONG createpost.php =================
     (đoạn script xử lý submit form postForm) -->

<script>

console.log("JS loaded");

document.getElementById("postForm").addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);

    if(!formData.get("category_id")){
        alert("Please choose category");
        return;
    }

    // BƯỚC 1: Tạo post trước
    fetch("/index.php?controller=post&action=createPost", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        console.log("Response:", data);

        // ✅ Response giờ là "success:123" (123 là post_id)
        if (data.trim().startsWith("success")) {

            let postId = data.split(":")[1]; // Lấy post_id

            let mediaFile = document.querySelector('[name=media]');

            // BƯỚC 2: Nếu có file ảnh/video thì upload tiếp
            if (mediaFile && mediaFile.files.length > 0) {

                let mediaData = new FormData();
                mediaData.append("media",   mediaFile.files[0]);
                mediaData.append("post_id", postId);

                fetch("/index.php?controller=media&action=uploadForPost", {
                    method: "POST",
                    body: mediaData
                })
                .then(res => res.text())
                .then(mediaRes => {
                    console.log("Media upload:", mediaRes);
                    showSuccessAndRedirect();
                })
                .catch(err => {
                    console.error("Media upload error:", err);
                    showSuccessAndRedirect(); // Vẫn redirect dù upload ảnh lỗi
                });

            } else {
                // Không có ảnh thì redirect luôn
                showSuccessAndRedirect();
            }

        } else {
            document.getElementById("result").innerHTML =
                "<div class='alert alert-danger'>Failed to create post</div>";
        }

    })
    .catch(err => {
        console.error("ERROR:", err);
    });

});

function showSuccessAndRedirect() {
    document.getElementById("result").innerHTML =
        "<div class='alert alert-success'>Post created successfully</div>";
    setTimeout(() => {
        window.location.href = "/index.php?controller=post&action=showHome";
    }, 1500);
}

</script>
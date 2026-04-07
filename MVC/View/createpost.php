<form id="postForm" method="POST" enctype="multipart/form-data">
<div class="container mt-3">

<!-- 📝 POST INFO -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-primary text-white">📝 Create Post</div>
  <div class="card-body">

    <div class="form-group">
      <label>📌 Title</label>
      <input type="text" name="title" class="form-control" placeholder="Post title">
    </div>

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
  <div class="card-header bg-success text-white">🛍️ Product Details</div>
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
  <div class="card-header bg-info text-white">📸 Media</div>
  <div class="card-body">

    <div class="custom-file mb-2">
      <input type="file" name="media" id="mediaInput"
             class="custom-file-input" accept="image/*,video/*">
      <label class="custom-file-label" for="mediaInput">Choose file</label>
    </div>

    <!-- Xem trước ảnh/video -->
    <div id="mediaPreview" class="mt-2" style="display:none;">
      <p class="text-muted small mb-1">Preview:</p>
      <img id="previewImg" src="" alt="Preview"
           class="img-fluid rounded"
           style="max-height:300px; object-fit:cover; display:none;">
      <video id="previewVideo" controls
             class="w-100 rounded"
             style="max-height:300px; display:none;">
        <source id="previewVideoSrc" src="">
      </video>
    </div>

  </div>
</div>

<!-- 🔘 BUTTON -->
<div class="row" id="button-group">
    <button type="submit" id="submitBtn" class="btn btn-primary" style="margin-right:10px;">
        🚀 Post
    </button>
    <a href="index.php?controller=post&action=showHome" class="btn btn-secondary">
        Cancel
    </a>
</div>

</div>
</form>

<div id="result"></div>

<script>

// ===== XEM TRƯỚC ẢNH/VIDEO KHI CHỌN FILE =====
document.getElementById("mediaInput").addEventListener("change", function(){
    let file    = this.files[0];
    let preview = document.getElementById("mediaPreview");
    let imgEl   = document.getElementById("previewImg");
    let videoEl = document.getElementById("previewVideo");
    let videoSrc = document.getElementById("previewVideoSrc");

    imgEl.style.display    = "none";
    videoEl.style.display  = "none";
    preview.style.display  = "none";

    let label = document.querySelector(".custom-file-label");
    label.textContent = file ? file.name : "Choose file";

    if (!file) return;

    let url = URL.createObjectURL(file);

    if (file.type.startsWith("image/")) {
        imgEl.src           = url;
        imgEl.style.display = "block";
        preview.style.display = "block";
    } else if (file.type.startsWith("video/")) {
        videoSrc.src          = url;
        videoEl.load();
        videoEl.style.display = "block";
        preview.style.display = "block";
    }
});


// ===== SUBMIT FORM =====
document.getElementById("postForm").addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);

    if (!formData.get("category_id")) {
        alert("Please choose category");
        return;
    }

    let submitBtn = document.getElementById("submitBtn");
    submitBtn.disabled    = true;
    submitBtn.textContent = "⏳ Posting...";

    // BƯỚC 1: Tạo post — server trả về "success:POST_ID"
    fetch("index.php?controller=post&action=createPost", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        console.log("createPost response:", data);

        if (!data.trim().startsWith("success")) {
            document.getElementById("result").innerHTML =
                "<div class='alert alert-danger'>❌ Failed to create post.</div>";
            submitBtn.disabled    = false;
            submitBtn.textContent = "🚀 Post";
            return;
        }

        // Lấy post_id từ "success:123"
        let postId    = data.trim().split(":")[1];
        let mediaFile = document.getElementById("mediaInput");

        // BƯỚC 2: Nếu có file thì upload ảnh/video
        if (mediaFile && mediaFile.files.length > 0) {

            let mediaData = new FormData();
            mediaData.append("media",   mediaFile.files[0]);
            mediaData.append("post_id", postId);

            fetch("index.php?controller=media&action=uploadForPost", {
                method: "POST",
                body: mediaData
            })
            .then(res => res.text())
            .then(mediaRes => {
                console.log("uploadForPost response:", mediaRes);
                showSuccessAndRedirect();
            })
            .catch(err => {
                console.error("Media upload error:", err);
                showSuccessAndRedirect();
            });

        } else {
            showSuccessAndRedirect();
        }

    })
    .catch(err => {
        console.error("ERROR:", err);
        submitBtn.disabled    = false;
        submitBtn.textContent = "🚀 Post";
    });

});

function showSuccessAndRedirect() {
    document.getElementById("result").innerHTML =
        "<div class='alert alert-success'>✅ Post created successfully!</div>";
    setTimeout(() => {
        window.location.href = "index.php?controller=post&action=showHome";
    }, 1500);
}

</script>
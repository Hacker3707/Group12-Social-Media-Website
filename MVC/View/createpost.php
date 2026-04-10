<?php define("BASE_URL", "/Group12-Social-Media-Website/"); ?>

<form id="postForm" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="is_product" id="isProductInput" value="1">

<div class="container mt-3">

<!-- 🧾 POST INFO -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-primary text-white">

    <?php if ($group === null)
      echo"<h4>📝 Create Post </h4>";
    else
      {
        echo "<h4>📝 Create Post for Group " . $group->getGroupName() . "</h4>";
      }
    ?>

    <?php if ($group !== null): ?>
    <input type="hidden" name="group_id" value="<?= $group->getGroupId() ?>">
    <?php endif; ?>
  </div>

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

    <!-- 📸 MEDIA -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-info text-white">
    📸 Media
  </div>

  <div class="card-body">

    <!-- Ô chọn file -->
    <div class="custom-file mb-2">
      <input type="file" name="media" id="mediaInput" class="custom-file-input" accept="image/*,video/*">
      <label class="custom-file-label" for="mediaInput">Choose file</label>
    </div>

    <!-- Khung xem trước ảnh/video -->
    <div id="mediaPreview" class="mt-2" style="display:none;">
      <p class="text-muted small mb-1">Preview:</p>
      <img id="previewImg" src="" alt="Preview"
           class="img-fluid rounded"
           style="max-height: 300px; object-fit: cover; display:none;">
      <video id="previewVideo" controls
             class="w-100 rounded"
             style="max-height: 300px; display:none;">
        <source id="previewVideoSrc" src="">
      </video>
    </div>

  </div>
</div>


  </div>
</div>

<!-- 🛍️ PRODUCT INFO -->
 <div class="custom-control custom-switch mb-3">
  <input type="checkbox" class="custom-control-input" id="toggleProduct" checked>
  <label class="custom-control-label" for="toggleProduct">
    🛍️ This is a product post (selling item)
  </label>
</div>

<div class="card mb-3 shadow-sm" id="productSection">

  <div class="card-header bg-success text-white">
    🛍️ Product Details
  </div>

  <div class="card-body">

    <div class="form-group">
      <label>💰 Price</label>

<div class="custom-control custom-switch mb-2">
  <input type="checkbox" class="custom-control-input" id="togglePrice" checked>
  <label class="custom-control-label" for="togglePrice">Enable price</label>
</div>


<input type="number" name="price" id="priceInput" class="form-control">

    </div>

    <div class="form-group">
      <label>📦 Condition</label>
<div class="btn-group-toggle mb-3 d-flex flex-wrap" data-toggle="buttons">

  <?php 
  $conditions = [
    "new" => "New",
    "like_new" => "Like New",
    "very_good" => "Very Good",
    "good" => "Good",
    "fair" => "Fair",
    "for_parts" => "For Parts"
  ];
  ?>

  <?php foreach($conditions as $value => $label): ?>
    <label class="btn btn-outline-primary m-1 <?= $value == 'good' ? 'active' : '' ?>">
      <input type="radio" name="condition" value="<?= $value ?>" <?= $value == 'good' ? 'checked' : '' ?>>
      <?= $label ?>
    </label>
  <?php endforeach; ?>

</div>

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
<div class="btn-group-toggle mb-3" data-toggle="buttons">

  <label class="btn btn-outline-success active">
    <input type="radio" name="status" value="selling" checked> Selling
  </label>

  <label class="btn btn-outline-warning">
    <input type="radio" name="status" value="reserved"> Reserved
  </label>

  <label class="btn btn-outline-secondary">
    <input type="radio" name="status" value="sold"> Sold
  </label>

  <label class="btn btn-outline-dark">
    <input type="radio" name="status" value="hidden"> Hidden
  </label>

</div>

  </div>
</div>





</div>
<!-- 🔘 BUTTON (GIỮ STYLE CŨ CỦA BẠN) -->
<div class="row" id="button-group">
    <button type="submit" class="btn btn-primary" style="margin-right:10px;">
        🚀 Post
    </button>
    <a href="./index.php?controller=post&action=showHome" class="btn btn-secondary">
        Cancel
    </a>
</div>
</form>

<div id="result"></div>

<script>

// ===== XEM TRƯỚC ẢNH/VIDEO KHI CHỌN FILE =====
document.getElementById("mediaInput").addEventListener("change", function(){

    let file = this.files[0];
    let preview   = document.getElementById("mediaPreview");
    let imgEl     = document.getElementById("previewImg");
    let videoEl   = document.getElementById("previewVideo");
    let videoSrc  = document.getElementById("previewVideoSrc");

    // Reset
    imgEl.style.display   = "none";
    videoEl.style.display = "none";
    preview.style.display = "none";

    // Cập nhật label
    let label = document.querySelector(".custom-file-label");
    label.textContent = file ? file.name : "Choose file";

    if (!file) return;

    let url = URL.createObjectURL(file);

    if (file.type.startsWith("image/")) {
        imgEl.src         = url;
        imgEl.style.display   = "block";
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

    // Disable nút Post để tránh bấm 2 lần
    let submitBtn = this.querySelector("button[type=submit]");
    submitBtn.disabled = true;
    submitBtn.textContent = "Posting...";

    // BƯỚC 1: Tạo post
      fetch("./index.php?controller=post&action=createPost", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(data => {

        console.log("Response:", data);

        if (data.trim().startsWith("success")) {

            let postId = data.split(":")[1]; // Lấy post_id từ "success:123"

            let mediaFile = document.getElementById("mediaInput");

            // BƯỚC 2: Nếu có file thì upload ảnh/video
            if (mediaFile && mediaFile.files.length > 0) {

                let mediaData = new FormData();
                mediaData.append("media",   mediaFile.files[0]);
                mediaData.append("post_id", postId);

                fetch("./index.php?controller=media&action=uploadForPost", {
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
                    showSuccessAndRedirect(); // Vẫn redirect dù upload lỗi
                });

            } else {
                showSuccessAndRedirect();
            }

        } else {
            document.getElementById("result").innerHTML =
                "<div class='alert alert-danger'>Failed to create post. Please try again.</div>";
            submitBtn.disabled = false;
            submitBtn.textContent = "🚀 Post";
        }

    })
    .catch(err => {
        console.error("ERROR:", err);
        submitBtn.disabled = false;
        submitBtn.textContent = "🚀 Post";
    });

});

function updatePriceState(){
    let toggle = document.getElementById("togglePrice");
    let input = document.getElementById("priceInput");

    input.disabled = !toggle.checked;

    if(!toggle.checked){
        input.value = "";
    }
}

document.getElementById("togglePrice").addEventListener("change", updatePriceState);

// 🔥 chạy ngay khi load
updatePriceState();
function updateProductSection(){

    let toggle = document.getElementById("toggleProduct");
    let section = document.getElementById("productSection");

    if(toggle.checked){
        section.style.display = "block";
    } else {
        section.style.display = "none";

        // reset dữ liệu
        section.querySelectorAll("input, select").forEach(el => {

            if(el.id === "toggleProduct") return;

            if(el.type === "radio" || el.type === "checkbox"){
                el.checked = false;
            } else {
                el.value = "";
            }

        });
    }
}
        

document.getElementById("toggleProduct").addEventListener("change", updateProductSection);

// chạy khi load
updateProductSection();
document.getElementById("toggleProduct").addEventListener("change", function(){
    document.getElementById("isProductInput").value = this.checked ? "1" : "0";
});
function showSuccessAndRedirect() {
    document.getElementById("result").innerHTML =
        "<div class='alert alert-success'>✅ Post created successfully!</div>";
    setTimeout(() => {
        window.location.href = "./index.php?controller=post&action=showHome";
    }, 1500);
}

</script>
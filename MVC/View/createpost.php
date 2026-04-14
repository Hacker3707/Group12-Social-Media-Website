<?php define("BASE_URL", "/Group12-Social-Media-Website/"); ?>

<form id="postForm" method="POST" enctype="multipart/form-data">
<input type="hidden" name="is_product" id="isProductInput" value="1">

<div class="container mt-3">

<!-- 📝 POST INFO -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-primary text-white align-items-center d-flex">

    <?php if ($group === null): ?>
      <h4>📝 Create Post</h4>
    <?php else: ?>
      <h4>📝 Create Post for Group <?= $group->getGroupName() ?></h4>
      <input type="hidden" name="group_id" value="<?= $group->getGroupId() ?>">
    <?php endif; ?>

  </div>

  <div class="card-body">

    <div class="form-group">
      <label>📌 Title</label>
      <input type="text" name="title" class="form-control">
    </div>

    <div class="form-group">
      <label>📂 Category</label>
      <select name="category_id" class="form-control">
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

<!-- 📸 MEDIA (CHỈ 1 LẦN DUY NHẤT) -->
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-info text-white">📸 Media</div>

  <div class="card-body">

    <div class="custom-file mb-2">
      <input type="file" name="media[]" id="mediaInput" multiple
             class="custom-file-input" accept="image/*,video/*">
      <label class="custom-file-label">Choose files</label>
    </div>

    <div id="mediaPreview" class="row" style="display:none;"></div>
    </div>

  </div>
</div>

<!-- 🛍️ PRODUCT -->
<div class="card mb-3 shadow-sm" id="productSection">

  <!-- HEADER + TOGGLE -->
  <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: seagreen">
    <span>🛍️ Product Details</span>

    <div class="custom-control custom-switch">
      <input type="checkbox" class="custom-control-input" id="toggleProduct" checked>
      <label class="custom-control-label" for="toggleProduct"></label>
    </div>
  </div>

  <div class="card-body" id="productBody">

    <!-- PRICE -->
    <div class="form-group">
      <label>💰 Price</label>

      <div class="custom-control custom-switch mb-2">
        <input type="checkbox" class="custom-control-input" id="togglePrice" checked>
        <label class="custom-control-label" for="togglePrice">Enable price</label>
      </div>

      <input type="number" name="price" id="priceInput" class="form-control">
    </div>

    <!-- CONDITION -->
    <div class="form-group">
      <label>📦 Condition</label>

      <div class="btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
        <?php 
        $conditions = [
          "new"=>"New","like_new"=>"Like New","very_good"=>"Very Good",
          "good"=>"Good","fair"=>"Fair","for_parts"=>"For Parts"
        ];
        ?>

        <?php foreach($conditions as $value => $label): ?>
          <label class="btn btn-outline-primary m-1 <?= $value=='good'?'active':'' ?>">
            <input type="radio" name="condition" value="<?= $value ?>" <?= $value=='good'?'checked':'' ?>>
            <?= $label ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- LOCATION -->
    <div class="form-group">
      <label>📍 Location</label>
      <select name="location" class="form-control">
        <option value="hcm">Ho Chi Minh</option>
        <option value="hanoi">Ha Noi</option>
        <option value="danang">Da Nang</option>
        <option value="other">Other</option>
      </select>
    </div>

    <!-- BRAND -->
    <div class="form-group">
      <label>🏷️ Brand</label>
      <input type="text" name="brand" class="form-control">
    </div>

    <!-- STATUS -->
    <div class="form-group">
      <label>📊 Status</label>

      <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-outline-success active">
          <input type="radio" name="status" value="selling" checked> Selling
        </label>
        <label class="btn btn-outline-warning">
          <input type="radio" name="status" value="reserved"> Reserved
        </label>
        <label class="btn btn-outline-secondary">
          <input type="radio" name="status" value="sold"> Sold
        </label>
      </div>
    </div>

  </div>
</div>

<!-- 🔘 BUTTON NGOÀI CARD -->
<div class="d-flex justify-content-end mb-4">

    <a href="./index.php?controller=post&action=showHome"
       class="btn btn-secondary mr-2">
       Cancel
    </a>

    <button type="submit" id="submitBtn" class="btn btn-primary">
        🚀 Post
    </button>

</div>

</div>
</form>

<div id="result"></div>

<script>

// ===== XEM TRƯỚC ẢNH/VIDEO KHI CHỌN FILE =====
document.getElementById("mediaInput").addEventListener("change", function(){
  let files   = Array.from(this.files || []);
    let preview = document.getElementById("mediaPreview");
  preview.innerHTML = "";
  preview.style.display = "none";

    let label = document.querySelector(".custom-file-label");
  label.textContent = files.length > 0
    ? `${files.length} file(s) selected`
    : "Choose files";

  if (files.length === 0) return;

  files.forEach(file => {
    let url = URL.createObjectURL(file);
    let col = document.createElement("div");
    col.className = "col-md-4 col-6 mb-2";

    if (file.type.startsWith("image/")) {
      col.innerHTML = `<img src="${url}" class="img-fluid rounded" style="max-height:200px; width:100%; object-fit:cover;">`;
    } else if (file.type.startsWith("video/")) {
      col.innerHTML = `<video controls class="w-100 rounded" style="max-height:200px;"><source src="${url}"></video>`;
    } else {
      return;
    }

    preview.appendChild(col);
  });

  preview.style.display = preview.children.length > 0 ? "flex" : "none";
});


// ===== SUBMIT FORM =====
document.getElementById("postForm").addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);
    // Tao post truoc, media se upload o buoc tiep theo de tranh luu trung.
    formData.delete("media[]");
    formData.delete("media");

    if (!formData.get("category_id")) {
        alert("Please choose category");
        return;
    }

    let submitBtn = document.getElementById("submitBtn");
    submitBtn.disabled    = true;
    submitBtn.textContent = "⏳ Posting...";

    // BƯỚC 1: Tạo post
      fetch("./index.php?controller=post&action=createPost", {
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
          Array.from(mediaFile.files).forEach(file => {
            mediaData.append("media[]", file);
          });
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
    let section = document.getElementById("productBody");

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
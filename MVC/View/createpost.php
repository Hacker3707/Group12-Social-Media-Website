<form id="postForm" enctype="multipart/form-data">

<div class="row">
<div class="input-group mb-3">
  <div class="input-group-prepend" style="width: 90px;">
    <span class="input-group-text" style="width: 90px;">@</span>
  </div>
  <input type="text" name="username" class="form-control" placeholder="Username">
</div>
</div>

<div class="row">
<div class="input-group mb-3">
  <div class="input-group-prepend" style="width: 90px;">
    <span class="input-group-text" style="width: 90px;">Title:</span>
  </div>
  <input type="text" name="title" class="form-control" placeholder="Post Title">
</div>
</div>

<div class="row">
<div class="input-group">
  <div class="input-group-prepend" style="width: 90px;">
    <span class="input-group-text" style="width: 90px;">Content:</span>
  </div>
  <textarea name="content" class="form-control" rows="6"></textarea>
</div>
</div>

<div class="row" style="margin-top:10px;">
<div class="input-group mb-3">
    <div class="input-group-prepend" style="width: 90px;">
        <span class="input-group-text" style="width: 90px;">Media:</span>
    </div>
    <div class="custom-file">
        <input type="file" name="media" class="custom-file-input">
        <label class="custom-file-label">Choose file</label>
    </div>
</div>
</div>

<div class="row" id="button-group">
    <button type="submit" class="btn btn-primary" style="margin-right:10px;">
        Post
    </button>

    <a href="homeview.php" class="btn btn-secondary">
        Cancel
    </a>
</div>

</form>

<div id="result"></div>

<script>

document.getElementById("postForm").addEventListener("submit", function(e){

    e.preventDefault(); // chặn reload trang

    let formData = new FormData(this);

    fetch("../../Module/createpost_module.php", {
    method: "POST",
    body: formData
    })
.then(response => response.text())
.then(data => {

    if(data.trim() === "success"){

        document.getElementById("result").innerHTML =
        "<div class='alert alert-success'>Post created successfully</div>" +
        "<div class='alert alert-info'>Redirecting...</div>";

        setTimeout(function(){
            window.location.href = "/Group12-Social-Media-Website/MVC/View/index.php";
        },3000);

    } else {

        document.getElementById("result").innerHTML =
        "<div class='alert alert-danger'>Failed to create post</div>";

    }

});

});

</script>
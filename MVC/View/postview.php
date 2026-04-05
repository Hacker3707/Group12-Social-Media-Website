<?php

if(empty($posts)){
    echo "No posts found";
    return;
}


foreach($posts as $post) { ?>
    
<div class="post-item border rounded p-3 mb-3 bg-white">

    <!-- Post Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">

<div>
    <img src="" alt="???" class="rounded-circle mr-2">

    <strong><?= htmlspecialchars($post->getUsername()) ?></strong>

    <?php if($post->getCategoryName()): ?>
        <span style="margin: 0 5px;">›</span>
        <a href="index.php?controller=post&action=getPostsByCategoryId&category_id=<?= $post->getCategoryId() ?>"
           style="color: gray; text-decoration: none;">
            <?= htmlspecialchars($post->getCategoryName()) ?>
        </a>
    <?php endif; ?>
</div>
        <small class="text-muted">
            <?= $post->getCreatedAt() ?>
        </small>
 </div>

    <!-- Post Content -->
    <h5><?= htmlspecialchars($post->getTitle()) ?></h5>
    
    <p><?= nl2br(htmlspecialchars($post->getContent())) ?></p>

   <!-- Extra Info -->
   <div class="text-muted small mt-2">

    <?php if($post->getPrice() !== null): ?>
        💰 Price: <?= number_format($post->getPrice()) ?> VND <br>
    <?php endif; ?>

    📦 Condition: <?= htmlspecialchars($post->getCondition()) ?> <br>

    📍 Location: <?= htmlspecialchars($post->getLocation()) ?> <br>

    <?php if($post->getBrand()): ?>
        🏷️ Brand: <?= htmlspecialchars($post->getBrand()) ?> <br>
    <?php endif; ?>

    📌 Status: <?= htmlspecialchars($post->getStatus()) ?>

   </div>
    <!-- Post Actions -->
    <div class="mt-2 d-flex align-items-center" >
        <button class="btn btn-sm btn-outline-primary col-md-2 col-12"
        data-toggle="modal"
        data-target="#postModal<?= $post->getPostId() ?>">
            View Post
        </button>

        <button class="btn btn-sm btn-outline-secondary ml-2" type="button">
            Comment <span class="badge badge-light"><?= count($comments[$post->getPostId()] ?? []) ?></span>
        </button>

        <div class="btn-group dropright ml-2">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="background-color: rgb(186, 212, 230); border: none;">
                ...
            </button>

            <div class="dropdown-menu">
                <button class="dropdown-item" type="button">Edit</button>
                <button class="dropdown-item" type="button">Report</button>
                <button class="dropdown-item delete-btn" type="button" data-postid="<?= $post->getPostId() ?>">
                    Delete
                </button>
            </div>
        
        </div>

        <button class="btn btn-sm btn-outline-primary like-btn ml-auto" type="button" data-postid="<?= $post->getPostId() ?>">
                Like <span class="badge badge-light like-count"><?= count($reactions[$post->getPostId()] ?? []) ?></span>
        </button>
    </div>
    <div class="mt-2">

    <?php if($post->getPrice() !== null): ?>
        <span class="badge badge-success">
            💰 <?= number_format($post->getPrice()) ?> VND
        </span>
    <?php endif; ?>

    <span class="badge badge-info">
        <?= $post->getCondition() ?>
    </span>

    <span class="badge badge-secondary">
        <?= $post->getLocation() ?>
    </span>

    <?php if($post->getBrand()): ?>
        <span class="badge badge-dark">
            <?= $post->getBrand() ?>
        </span>
    <?php endif; ?>

</div>


</div>


<!-- Modal -->
<div class="modal fade"
id="postModal<?= $post->getPostId() ?>"
tabindex="-1">

  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
            <?= htmlspecialchars($post->getTitle()) ?>
        </h5>

        <button type="button" class="close" data-dismiss="modal">
        &times;
        </button>
      </div>

        <div class="modal-body">
            <p><?= htmlspecialchars($post->getContent()) ?></p>

            <div class="d-flex align-items-center mt-4">
                <small class="text-muted ">
                Posted at <?= $post->getCreatedAt() ?>
                </small>

                <button id="btn-forModal" class="btn btn-sm btn-outline-primary like-btn ml-auto justify-content-end" type="button" data-postid="<?= $post->getPostId() ?>">
                        Like <span class="badge badge-light like-count"><?= count($reactions[$post->getPostId()] ?? []) ?></span>
                </button>

            </div>

        </div>


    </div>
  </div>

</div>

<?php } ?>


<!-- Delete Post Script -->
<script>

document.addEventListener("click", function(e){

    if(!e.target.classList.contains("delete-btn")) return;

    let postId = e.target.getAttribute("data-postid");

    if(!confirm("Delete this post?")) return;

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){

        if(xhr.readyState === 4 && xhr.status === 200){

            if(xhr.responseText.trim() === "success"){
                alert("Post deleted");
                location.reload();
            }
            else{
                alert("Delete failed");
            }

        }

    };

    xhr.open("POST", "index.php?controller=post&action=deletePost", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("postId=" + encodeURIComponent(postId));

});


</script>

<!-- Like Post Script -->
<script>
    document.addEventListener("click", function(e){

    let btn = e.target.closest(".like-btn");
    if(!btn) return;

    let postId = btn.getAttribute("data-postid");

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){

        if(xhr.readyState === 4 && xhr.status === 200){

            if(xhr.responseText.trim() === "success"){

                let badge = btn.querySelector(".like-count");
                let count = parseInt(badge.textContent);

                badge.textContent = count + 1;

            }
            else{
                alert("Like failed");
            }

        }

    };

    xhr.open("POST", "index.php?controller=reaction&action=addReaction", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(
        "postId=" + encodeURIComponent(postId) +
        "&type=like"
    );

});
</script>


<?php
include 'confirm_alert.php';


if(empty($posts)){
    echo "No posts found";
    return;
}
?>

<?php foreach($posts as $post): ?>
<?php 
$isProduct = $post->getPrice() !== null 
          || $post->getCondition() 
          || $post->getStatus();
?>
    
<div class="post-item border rounded p-3 mb-3 bg-white">

    <!-- Post Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">

<div>
    <img src="" alt="???" class="rounded-circle mr-2">

    <a href="index.php?controller=user&action=profile&user_id=<?= $post->getUserId() ?>">
        <strong><?= htmlspecialchars($post->getUsername()) ?></strong>
    </a>

    <?php if($post->getCategoryName()): ?>
        <span style="margin: 0 5px;">›</span>
        <?php if($post->getCategoryName() !== 'No Category') { ?>
            <a href="index.php?controller=post&action=getPostsByCategoryId&category_id=<?= $post->getCategoryId() ?>"
               style="color: gray; text-decoration: none;">
                <?= htmlspecialchars($post->getCategoryName()) ?>
            </a>
            <?php } else { ?>
                <span style="color: gray;">
                    <?= htmlspecialchars($post->getCategoryName()) ?>
                </span>
            
        <?php } ?>
    <?php endif; ?>
</div>
        <small class="text-muted">
            <?= $post->getCreatedAt() ?>
        </small>
 </div>

    <!-- Post Content -->
    <h5><?= htmlspecialchars($post->getTitle()) ?></h5>
    
    <p><?= nl2br(htmlspecialchars($post->getContent())) ?></p>

    <!--  BADGE Ở ĐÂY -->
<?php if($isProduct): ?>
<div class="mt-2 d-flex flex-wrap" style="gap:6px;">

    <?php if($post->getPrice() !== null): ?>
        <span class="badge badge-success">
            💰 <?= number_format($post->getPrice()) ?> VND
        </span>
    <?php endif; ?>

    <?php if($post->getCondition()): ?>
        <span class="badge badge-info">
            <?= $post->getCondition() ?>
        </span>
    <?php endif; ?>

    <?php if($post->getLocation()): ?>
        <span class="badge badge-secondary">
            <?= $post->getLocation() ?>
        </span>
    <?php endif; ?>

    <?php if($post->getBrand()): ?>
        <span class="badge badge-dark">
            <?= $post->getBrand() ?>
        </span>
    <?php endif; ?>

</div>
<?php endif; ?>

   <!-- Extra Info -->
  

   

<?php if($isProduct): ?>
<div class="text-muted small mt-2">

    <?php if($post->getPrice() !== null): ?>
        💰 Price: <?= number_format($post->getPrice()) ?> VND <br>
    <?php endif; ?>

    <?php if($post->getCondition()): ?>
        📦 Condition: <?= htmlspecialchars($post->getCondition()) ?> <br>
    <?php endif; ?>

    <?php if($post->getLocation()): ?>
        📍 Location: <?= htmlspecialchars($post->getLocation()) ?> <br>
    <?php endif; ?>

    <?php if($post->getBrand()): ?>
        🏷️ Brand: <?= htmlspecialchars($post->getBrand()) ?> <br>
    <?php endif; ?>

    <?php if($post->getStatus()): ?>
        📌 Status: <?= htmlspecialchars($post->getStatus()) ?>
    <?php endif; ?>

</div>
<?php endif; ?>

   
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
                <i class="bi bi-three-dots"></i>
            </button>

            <div class="dropdown-menu">
                <button class="dropdown-item" type="button">Edit</button>
                <button class="dropdown-item" type="button">Report</button>
                <button class="dropdown-item delete-btn" type="button" data-postid="<?= $post->getPostId() ?>">
                    Delete
                </button>
            </div>
        </div>
        
    

        <?php $postId = $post->getPostId(); ?> 

        <?php if($isSameUser[$postId] ?? false): ?>

            <button class="btn btn-sm btn-outline-primary like-btn ml-auto"
            type="button"
            data-postid="<?= $postId ?>">

            <i class="bi bi-heart-fill"></i>
            <span class="badge badge-light like-count">
            <?= count($reactions_forPost[$postId] ?? []) ?>
            </span>

            </button>

        <?php else: ?>

            <button class="btn btn-sm btn-outline-primary like-btn ml-auto"
            type="button"
            data-postid="<?= $postId ?>">

            <i class="bi bi-heart"></i>
            <span class="badge badge-light like-count">
            <?= count($reactions_forPost[$postId] ?? []) ?>
            </span>

            </button>
     </div>

        <?php endif; ?>

    </div>
    

<!-- Modal -->
<?php include "post_modal.php"; ?>

 



            <!-- Delete Post Script -->

<script>

document.addEventListener("click", function(e){

    if(!e.target.classList.contains("delete-btn")) return;

    let postId = e.target.getAttribute("data-postid");

    showConfirm("Delete this post?", function(result) {
        if (!result) return;

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

            console.log(xhr.responseText);    

            if(xhr.responseText.trim() === "success"){

                document.querySelectorAll(`.like-btn[data-postid="${postId}"]`).forEach(button => {

                    let badge = button.querySelector(".like-count");
                    let icon = button.querySelector("i");

                    if(icon.classList.contains("bi-heart")){
                        icon.classList.remove("bi-heart");
                        icon.classList.add("bi-heart-fill");
                        badge.textContent = parseInt(badge.textContent) + 1;
                    }
                    else{
                        icon.classList.remove("bi-heart-fill");
                        icon.classList.add("bi-heart");
                        badge.textContent = Math.max(0, parseInt(badge.textContent) - 1);
                    }

                });

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


            <!-- Like Comment Script -->

<script>

    document.addEventListener("click", function(e){
    
    let btn = e.target.closest(".like-btn-cmt");
    if(!btn) return;

    let commentId = btn.getAttribute("data-commentid");

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){

        if(xhr.readyState === 4 && xhr.status === 200){

            console.log(xhr.responseText);    

            if(xhr.responseText.trim() === "success"){

                let badge = btn.querySelector(".like-count-cmt");
                let icon = btn.querySelector("i");

                if(icon.classList.contains("bi-heart")){
                    icon.classList.remove("bi-heart");
                    icon.classList.add("bi-heart-fill");
                    badge.textContent = parseInt(badge.textContent) + 1;
                }
                else{
                    icon.classList.remove("bi-heart-fill");
                    icon.classList.add("bi-heart");
                    badge.textContent = Math.max(0, parseInt(badge.textContent) - 1);
                }

            }
            else{
                alert("Failed to react to comment");
            }

        }

    };


    xhr.open("POST", "index.php?controller=reaction&action=addReaction", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(
        "commentId=" + encodeURIComponent(commentId) +
        "&type=like"
    );

});

</script>

    <!--  -->
                <!-- Reply Button Script -->
    <!--  -->

<script>
$(document).on("click", ".reply-btn", function(){

    let modal = $(this).closest(".post-modal");

    /* reset reply state nếu đang reply comment khác */
    modal.find(".parentId").val("");

    modal.find(".reply-btn-disabled")
         .removeClass("reply-btn-disabled");

    modal.find(".reply-preview").addClass("d-none");

    modal.find(".commentContent")
        .attr("placeholder","Write a comment...");

    modal.find(".cmt-btn")
        .text("Comment")
        .removeClass("reply-cmt-btn");

    /* bắt đầu reply comment mới */

    let commentItem = $(this).closest(".comment-item");
    let repbtn = $(this);

    repbtn.addClass("reply-btn-disabled");

    let preview = modal.find(".reply-preview");
    let parentInput = modal.find(".parentId");
    let commentInput = modal.find(".commentContent");

    let commentId = $(this).data("commentId");
    let username = commentItem.find("strong a").text();
    let content = commentItem.find("p").text();

    parentInput.val(commentId);

    modal.find("#reply-user").text(username);
    modal.find("#reply-content").text(content);

    preview.removeClass("d-none");

    commentInput
        .attr("placeholder","Reply to @" + username.trim())
        .focus();

    modal.find(".cmt-btn")
        .text("Reply")
        .addClass("reply-cmt-btn");

});


$(document).on("click", ".cancel-reply", function(){

    let modal = $(this).closest(".post-modal");

    modal.find(".parentId").val("");

    modal.find(".reply-btn-disabled")
         .removeClass("reply-btn-disabled");

    modal.find(".reply-preview").addClass("d-none");

    modal.find(".commentContent")
        .attr("placeholder","Write a comment...");

    modal.find(".cmt-btn").text("Comment");

});


</script>



<!--  -->
            <!-- Send Comment Button Script -->
<!--  -->

<script>
document.addEventListener("submit", function(e){

    let form = e.target;

    if(!form.classList.contains("comment-form")) return;

    e.preventDefault();

    let postId = form.postId.value;
    let content = form.commentContent.value;
    let parentId = form.parentId.value; // thêm dòng này

    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status === 200){
            let data;

            try{
                data = JSON.parse(xhr.responseText);
            }catch(e){
                console.log("SERVER RESPONSE:");
                console.log(xhr.responseText);
                alert("Server returned invalid JSON");
                return;
            }

            if(data.status === "success"){

                let c = data.comment;

                let commentList = form.closest(".post-modal").querySelector(".comment-list");

                commentList.insertAdjacentHTML("beforeend", `
                <div class="comment-item mb-2 d-flex justify-content-between" data-commentid="${c.id}">

                    <div>
                        <strong>
                            <a href="index.php?controller=user&action=profile&id=${c.user_id}">
                                ${c.username}
                            </a>
                            <small>commented:</small>
                        </strong>

                        <p class="mb-1">${c.content}</p>

                        <small class="text-muted">${c.created_at}</small>
                    </div>

                    <div class="d-flex align-items-end">

                        <button class="btn-forModal btn-sm btn-outline-primary reply-btn"
                            type="button"
                            data-comment-id="${c.id}">
                            Reply
                        </button>

                        <button class="btn btn-sm btn-outline-primary like-btn-cmt ml-2"
                            type="button"
                            data-commentid="${c.id}">

                            <i class="bi bi-heart"></i>
                            <span class="badge badge-light like-count-cmt">
                                0
                            </span>

                        </button>

                    </div>

                </div>
                `);
                // reset form
                form.commentContent.value = "";
                let noCmt = form.closest(".post-modal").querySelector(".no-cmt");
                if(noCmt){
                    noCmt.remove();
                }

            }
            else{
                console.log("Error:", data.message);
            }
        }

    };

    xhr.open("POST", "index.php?controller=comment&action=addComment", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        console.log("postId:", postId);
        console.log("content:", content);
        console.log("parentId:", parentId);
    xhr.send(
        "postId=" + encodeURIComponent(postId) +
        "&content=" + encodeURIComponent(content) +
        "&parentId=" + encodeURIComponent(parentId)
    );
});
</script>


<!-- Delete Comment Script -->

<script>

document.addEventListener("click", function(e){

    if(!e.target.classList.contains("delete-btn-cmt")) return;

    let commentId = e.target.getAttribute("data-commentid");

    showConfirm("Delete this comment?", function(result) {

        if(!result) return;

        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function(){

            if(xhr.readyState === 4 && xhr.status === 200){

                let response = JSON.parse(xhr.responseText);

                try{
                    response = JSON.parse(xhr.responseText);
                }catch(e){
                    alert("Server error");
                    return;
                }

                if(response.status === "success"){

                    alert("Comment deleted");

                    let commentItem = e.target.closest(".comment-item");
                    if(commentItem){
                        commentItem.remove();
                    }
                    location.reload();

                }else{
                    alert(response.message || "Delete failed");
                }

            }

        };

        xhr.open("POST","index.php?controller=comment&action=deleteComment",true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send("commentId=" + encodeURIComponent(commentId));

    });

});

</script>

<!-- comment tree toggle -->

<script>

document.addEventListener("click", function(e){

    if(!e.target.classList.contains("toggle-replies")) return;

    let id = e.target.dataset.commentid;
    let box = document.getElementById("replies-" + id);

    if(box.classList.contains("d-none")){
        box.classList.remove("d-none");
        e.target.textContent = "Hide replies";
    }
    else{
        box.classList.add("d-none");
        e.target.textContent = "View replies";
    }

});

</script>

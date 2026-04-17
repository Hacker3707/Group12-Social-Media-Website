//<!-- == React + Remove react Comment Script == -->

document.addEventListener("click", function(e){

    let btn = e.target.closest(".like-btn-cmt");
    if(!btn) return;

    let commentId = btn.dataset.commentId;

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){

            console.log(xhr.responseText);

            let data = JSON.parse(xhr.responseText); // ✅ FIX QUAN TRỌNG

            if(data){
                let badge = btn.querySelector(".like-count-cmt");
                let icon  = btn.querySelector("i");

                badge.textContent = data.total;

                if(data.reacted === 1){
                    icon.classList.replace("bi-heart","bi-heart-fill");
                } else {
                    icon.classList.replace("bi-heart-fill","bi-heart");
                }
            }
        }
    };

    xhr.open("POST", "index.php?controller=reaction&action=action_forReaction", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.send(
        "commentId=" + encodeURIComponent(commentId) +
        "&type=like"
    );
});

//              <!-- Reply Button Script -->

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





//            <!-- Send Comment + Reply Button Script -->


document.addEventListener("submit", function(e){

    let form = e.target;

    if(!form.classList.contains("comment-form")) return;

    e.preventDefault();

    let postId = form.postId.value;
    let content = form.commentContent.value;
    let parentId = form.parentId.value || 0;

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

                let container;

                if(parseInt(parentId) === 0){
                    container = form.closest(".post-modal").querySelector(".comment-list");
                } else {
                    container = document.getElementById("replies-" + parentId);

                    if(!container){
                        // 🔥 tạo luôn reply container nếu chưa có
                        let parentComment = document.querySelector(`.comment-item[data-comment-id="${parentId}"]`);

                        if(parentComment){

                            let containerDiv = document.createElement("div");
                            containerDiv.className = "reply-container";
                            containerDiv.id = "replies-" + parentId;

                            parentComment.appendChild(containerDiv); // ✅ đúng cây

                            container = containerDiv;
                        }
                    }

                    if(container){
                        container.classList.remove("d-none");
                    }
                }

                // ✅ check sau khi gán
                if(!container){
                    console.log("Container not found!");
                    return;
                }

                container.insertAdjacentHTML("beforeend", `
                    <div class="comment-item d-flex justify-content-between"
                        data-comment-id="${c.id}">

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
                                data-comment-id="${c.id}">
                                <i class="bi bi-heart"></i>
                                <span class="badge badge-light like-count-cmt">0</span>
                            </button>
                        </div>
                    </div>

                    <div class="reply-container d-none" id="replies-${c.id}"></div>
                `);
                // reset form
                form.commentContent.value = "";

                let noCmt = form.closest(".post-modal").querySelector(".no-cmt");

                if(noCmt){
                    noCmt.remove();
                }

                container.scrollIntoView({ behavior: "smooth", block: "end" });

                form.parentId.value = 0;

                let modal = $(form).closest(".post-modal");

                modal.find(".reply-preview").addClass("d-none");

                modal.find(".commentContent")
                    .attr("placeholder","Write a comment...");

                modal.find(".cmt-btn")
                    .text("Comment")
                    .removeClass("reply-cmt-btn");

                modal.find(".reply-btn-disabled")
                    .removeClass("reply-btn-disabled");

                let badgeCmt = form.closest(".post-modal").querySelector(".badge-cmt");

                let current = parseInt(badgeCmt.textContent) || 0;
                badgeCmt.textContent = current + 1;

                // Cập nhật badge comment ở postview cũng
                let postViewBadge = document.querySelector(`button[data-target="#postModal${postId}"] .badge-cmt`);
                if (postViewBadge) {
                    let currentPostView = parseInt(postViewBadge.textContent) || 0;
                    postViewBadge.textContent = currentPostView + 1;
                }

                
            }else{
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
       "&parent_comment_id=" + encodeURIComponent(parentId) // ✅ FIX 
    );
});

//<!-- Delete Comment Script -->


document.addEventListener("click", function(e){

    if(!e.target.classList.contains("delete-btn-cmt")) return;

    let commentId = e.target.getAttribute("data-comment-id");

    showConfirm("Delete this comment?", function(result) {

        if(!result) return;

        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function(){

            if(xhr.readyState === 4 && xhr.status === 200){

                let response;

                try{
                    response = JSON.parse(xhr.responseText);
                }catch(e){
                    showAlert("Error", "Oops!", "Server Error")
                    return;
                }

                if(response.status === "success"){

                    showAlert("Success", "Success", "Comment deleted !");

                    let commentItem = e.target.closest(".comment-item");
                    if(commentItem){
                        commentItem.remove();
                    }
                    

                }else{
                    showAlert("Error", "Something went wrong...", response.message || "Delete failed");
                }

            }

        };

        xhr.open("POST","index.php?controller=comment&action=deleteComment",true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send("commentId=" + encodeURIComponent(commentId));

    });

});


// <!-- comment tree toggle -->


document.addEventListener("click", function(e){

    if(!e.target.classList.contains("toggle-replies")) return;

    let id = e.target.dataset.commentId;
    let box = document.getElementById("replies-" + id);

    // 🔥 FIX: chống null
    if(!box){
        console.log("Không tìm thấy replies box:", id);
        return;
    }

    if(box.classList.contains("d-none")){
        box.classList.remove("d-none");
        e.target.textContent = "Hide replies";
    }
    else{
        box.classList.add("d-none");
        e.target.textContent = "View replies";
    }

});


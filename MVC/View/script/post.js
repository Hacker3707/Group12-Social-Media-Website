

//            <!-- Edit Post Script -->

document.addEventListener("click", function(e){
    let editBtn = e.target.closest(".edit-btn");
    if(!editBtn) return;

    let postId = editBtn.getAttribute("data-postid");
    if(!postId) return;

    window.location.href = "index.php?controller=post&action=showEditForm&id=" + encodeURIComponent(postId);
});


//           <!-- Delete Post Script -->


document.addEventListener("click", function(e){
    if(!e.target.classList.contains("delete-btn")) return;
    let postId = e.target.getAttribute("data-postid");

    showConfirm("Delete this post?", function(result) {
        if (!result) return;

        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function(){

            if(xhr.readyState === 4 && xhr.status === 200){

                if(xhr.responseText.trim() === "success"){
                    showAlert("Success", "Success", "Post deleted !");
                    location.reload();
                }
                else{
                    showAlert("Error", "Oh no!", "Delete post failed !");
                }

            }

        };
    

    xhr.open("POST", "index.php?controller=post&action=deletePost", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("postId=" + encodeURIComponent(postId));

    });

});



//           <!-- Reaction Like Post Script -->


document.addEventListener("click", function(e){
    let btn = e.target.closest(".like-btn");
    if(!btn) return;

    let postId = btn.dataset.postid;

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
    if(xhr.readyState === 4 && xhr.status === 200){

        let data = JSON.parse(xhr.responseText);

        document.querySelectorAll(`.like-btn[data-postid="${postId}"]`)
        .forEach(button => {

            let badge = button.querySelector(".like-count");
            let icon  = button.querySelector("i");

            badge.textContent = data.total;

            if(data.reacted === 1){
                icon.classList.replace("bi-heart","bi-heart-fill");
            } else {
                icon.classList.replace("bi-heart-fill","bi-heart");
            }

        });
    }
};

    xhr.open("POST", "index.php?controller=reaction&action=action_forReaction", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("postId=" + postId + "&type=like");
});


document.addEventListener("click", function(e){
    if(!e.target.classList.contains("edit-btn")) return;
    let postId = e.target.getAttribute("data-postid");

    let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function(){

            if(xhr.readyState === 4 && xhr.status === 200){

                if(xhr.responseText.trim() === "success"){
                    alert("Your post has been edited !");
                    location.reload();
                }
                else{
                    alert("Edit failed.");
                }

            }

        };
    

    xhr.open("POST", "index.php?controller=post&action=editPost", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("postId=" + encodeURIComponent(postId));
});

let offset = count($posts); // số post ban đầu
let limit = 5;
let loading = false;

function loadMorePosts(){
    if(loading) return;

    loading = true;

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){

            let container = document.getElementById("post-container");

            if(xhr.responseText.trim() === ""){
                return; // hết dữ liệu
            }

            container.insertAdjacentHTML("beforeend", xhr.responseText);

            offset += limit;
            loading = false;
        }
    };

    xhr.open("GET", `index.php?controller=post&action=loadMore&offset=${offset}&limit=${limit}`, true);
    xhr.send();
}

/* 👇 INTERSECTION OBSERVER */
let observer = new IntersectionObserver(entries => {
    if(entries[0].isIntersecting){
        loadMorePosts();
    }
});

observer.observe(document.querySelector("#load-trigger"));
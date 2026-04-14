<div id="confirmBox" class="confirm-box" style="display:none;">
    <div class="confirm-content">

        <p id="confirmMsg"></p>

        <div class="confirm-actions">
            <button id="confirmYes" class="btn btn-primary">Yes</button>
            <button id="confirmNo" class="btn btn-secondary">No</button>
        </div>

    </div>
</div>

<!-- Flexbox container for aligning the toasts -->
<div id="notiBox" aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center noti-box" style="height: 200px;">

  <!-- Then put toasts within -->
  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <i class="bi bi-check-circle-fill"></i>
      <strong class="mr-auto" id="noti-title"></strong>
      <small></small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body" id="notiMsg">
      Empty. Perhaps devs forgot to add msg?
    </div>
  </div>
</div>

<!-- Own design JS alerts -->
<script>

function showConfirm(msg, callback){

    let box = document.getElementById("confirmBox");
    let text = document.getElementById("confirmMsg");

    text.textContent = msg;
    box.style.display = "flex";

    document.getElementById("confirmYes").onclick = function(){
        box.style.display = "none";
        callback(true);
    };

    document.getElementById("confirmNo").onclick = function(){
        box.style.display = "none";
        callback(false);
    };
}

function showAlert(typeName, title, msg) {
    let text = document.getElementById("notiMsg");
    let titleN = document.getElementById("noti-title");
    let icon = document.getElementsByClassName("bi");
    let toastEl = document.querySelector(".toast");


    text.textContent = msg;
    titleN.textContent = title;

    if (typeName == "Warning"){
        icon.replace("bi-check-circle-fill", "bi-exclamation-triangle-fill");
    }

    if (typeName == "Error"){
        icon.replace("bi-check-circle-fill", "bi-exclamation-octagon-fill");
    }

    // reset màu
    toastEl.classList.remove("bg-success","bg-danger","bg-warning","text-white");

    if(typeName === "Success"){
        toastEl.classList.add("bg-success","text-white");
    }
    else if(typeName === "Error"){
        toastEl.classList.add("bg-danger","text-white");
    }
    else if(typeName === "Warning"){
        toastEl.classList.add("bg-warning");
    }

    let toast = new bootstrap.Toast(toastEl, {
        delay: 2000,
        autohide: true
    });

    toast.show();
}
</script>

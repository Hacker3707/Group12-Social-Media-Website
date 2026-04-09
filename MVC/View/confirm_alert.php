<div id="confirmBox" class="confirm-box" style="display:none;">
    <div class="confirm-content">

        <p id="confirmMsg"></p>

        <div class="confirm-actions">
            <button id="confirmYes" class="btn btn-primary">Yes</button>
            <button id="confirmNo" class="btn btn-secondary">No</button>
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

</script>
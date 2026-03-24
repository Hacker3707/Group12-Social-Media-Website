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
            <strong><?= htmlspecialchars($post->getUsername()) ?></strong>
        </div>
        <small class="text-muted">
            <?= $post->getCreatedAt() ?>
        </small>
    </div>

    <!-- Post Content -->
    <h5><?= htmlspecialchars($post->getTitle()) ?></h5>
    <p><?= htmlspecialchars($post->getContent()) ?></p>

    <!-- Post Actions -->
    <div class="mt-2">
        <button class="btn btn-sm btn-outline-primary"
        data-toggle="modal"
        data-target="#postModal<?= $post->getPostId() ?>">
            View Post
        </button>
    </div>

</div>


<!-- Modal -->
<div class="modal fade"
id="postModal<?= $post->getPostId() ?>"
tabindex="-1">

  <div class="modal-dialog modal-lg">
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

        <small class="text-muted">
            Posted at <?= $post->getCreatedAt() ?>
        </small>
      </div>

    </div>
  </div>

</div>

<?php } ?>
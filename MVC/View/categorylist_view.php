<div class="container mt-4">

<h4 class="mb-3 font-weight-bold">Category liên quan</h4>

<div class="row">

<?php if (!empty($categories)): ?>

<?php foreach ($categories as $c): ?>

<div class="col-md-3 mb-3">

<div class="card shadow-sm h-100 text-center">

<div class="card-body">

<h5 class="card-title">
📂 <?= htmlspecialchars($c->getCategoryName()) ?>
</h5>

<p class="text-muted mb-3">
Category ID: <?= $c->getCategoryId() ?>
</p>

<a href="index.php?controller=post&action=getPostsByCategoryId&category_id=<?= $c->getCategoryId() ?>"
class="btn btn-sm btn-outline-primary">
View Posts
</a>

</div>

</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="col-12">
<div class="alert alert-warning">
Không tìm thấy category phù hợp.
</div>
</div>

<?php endif; ?>

</div>

</div>
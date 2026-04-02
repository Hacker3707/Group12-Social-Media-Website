<h2>Category</h2>

<?php if(isset($categories)): ?>

    <?php foreach ($categories as $cat): ?>
        <p><?= $cat->getCategoryName() ?></p>
    <?php endforeach; ?>

<?php endif; ?>


<?php if(isset($category)): ?>

    <form method="POST" action="index.php?controller=category&action=update">
        <input type="hidden" name="categoryId" value="<?= $category->getCategoryID() ?>">
        
        <input type="text" name="categoryName" value="<?= $category->getCategoryName() ?>">
        
        <button type="submit">Update</button>
    </form>

<?php endif; ?>
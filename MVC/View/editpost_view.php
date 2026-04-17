<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="MVC/View/css/createpost.css">
</head>
<body>
    <div class="container-fluid" id="home-container">
        <div class="row" id="navbar">
            <div class="col-md-12 col-12">
                <?php include 'navbar.php'; ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-12 col-lg-2 mb-3 mb-lg-0" id="left-sidebar">
                <?php include 'leftsidebar.php'; ?>
            </div>

            <div class="col-12 col-lg-10" id="main-content">
                <div class="container mt-3">
                    <form method="POST" action="index.php?controller=post&action=updatePost">
                        <input type="hidden" name="postId" value="<?= (int)$post->getPostId() ?>">
                        <input type="hidden" name="edit_form_submit" value="1">

                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Edit Post</h4>
                            </div>

                            <div class="card-body">
                                <?php if (!empty($errorMessage)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post->getTitle() ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea name="content" class="form-control" rows="4" required><?= htmlspecialchars($post->getContent() ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-header text-white" style="background-color: seagreen">
                                Product Details
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" class="form-control" value="<?= htmlspecialchars((string)($post->getPrice() ?? '')) ?>">
                                </div>

                                <div class="form-group">
                                    <label>Condition</label>
                                    <?php $currentCondition = (string)($post->getCondition() ?? 'good'); ?>
                                    <select name="condition" class="form-control">
                                        <option value="new" <?= $currentCondition === 'new' ? 'selected' : '' ?>>New</option>
                                        <option value="like_new" <?= $currentCondition === 'like_new' ? 'selected' : '' ?>>Like New</option>
                                        <option value="very_good" <?= $currentCondition === 'very_good' ? 'selected' : '' ?>>Very Good</option>
                                        <option value="good" <?= $currentCondition === 'good' ? 'selected' : '' ?>>Good</option>
                                        <option value="fair" <?= $currentCondition === 'fair' ? 'selected' : '' ?>>Fair</option>
                                        <option value="for_parts" <?= $currentCondition === 'for_parts' ? 'selected' : '' ?>>For Parts</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Location</label>
                                    <?php $currentLocation = (string)($post->getLocation() ?? 'other'); ?>
                                    <select name="location" class="form-control">
                                        <option value="hcm" <?= $currentLocation === 'hcm' ? 'selected' : '' ?>>Ho Chi Minh</option>
                                        <option value="hanoi" <?= $currentLocation === 'hanoi' ? 'selected' : '' ?>>Ha Noi</option>
                                        <option value="danang" <?= $currentLocation === 'danang' ? 'selected' : '' ?>>Da Nang</option>
                                        <option value="other" <?= $currentLocation === 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Brand</label>
                                    <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars((string)($post->getBrand() ?? '')) ?>">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <?php $currentStatus = (string)($post->getStatus() ?? 'selling'); ?>
                                    <select name="status" class="form-control">
                                        <option value="selling" <?= $currentStatus === 'selling' ? 'selected' : '' ?>>Selling</option>
                                        <option value="reserved" <?= $currentStatus === 'reserved' ? 'selected' : '' ?>>Reserved</option>
                                        <option value="sold" <?= $currentStatus === 'sold' ? 'selected' : '' ?>>Sold</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end mb-4">
                            <a href="index.php?controller=post&action=showHome" class="btn btn-secondary mr-sm-2 mb-2 mb-sm-0">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row" id="footer">
            <div class="col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

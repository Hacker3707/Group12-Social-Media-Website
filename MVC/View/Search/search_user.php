<!DOCTYPE html>
<head>
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">

</head>
<body>
    <div class="container-fluid" id = "home-container">

        <div class="row" id = "navbar">
            <div class= "col-md-12 col-12">
                <?php include_once __DIR__ . "/../navbar.php"; ?>
            </div>
        </div>

        <div class="row" id = "middle-content">
            <div class= "col-md-2 col-12" id = "left-sidebar">
                <?php include_once __DIR__ . "/../leftsidebar.php"; ?>
            </div>
            <div class= "col-md-10 col-12" id = "main-content">
                <?php
                include_once __DIR__ . "/../userlist_view.php";
                ?>

                <?php if (($totalPages ?? 1) > 1): ?>
                    <nav aria-label="User search pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php $prevPage = max(1, ($currentPage ?? 1) - 1); ?>
                            <li class="page-item <?= ($currentPage ?? 1) <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="index.php?controller=search&action=searchUsers&searchResults=<?= urlencode($keyword ?? '') ?>&page=<?= $prevPage ?>">Previous</a>
                            </li>

                            <?php for ($p = 1; $p <= ($totalPages ?? 1); $p++): ?>
                                <li class="page-item <?= $p == ($currentPage ?? 1) ? 'active' : '' ?>">
                                    <a class="page-link" href="index.php?controller=search&action=searchUsers&searchResults=<?= urlencode($keyword ?? '') ?>&page=<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php $nextPage = min(($totalPages ?? 1), ($currentPage ?? 1) + 1); ?>
                            <li class="page-item <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="index.php?controller=search&action=searchUsers&searchResults=<?= urlencode($keyword ?? '') ?>&page=<?= $nextPage ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

        <div class="row" id = "footer">
            <div class= "col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
        
    </div>
</body>
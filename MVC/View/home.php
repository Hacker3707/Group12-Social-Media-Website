<!DOCTYPE html>
<head>
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="MVC/View/css/home.css">

</head>
<body>
    <div class="container-fluid" id="home-container">

        <div class="row" id = "navbar">
            <div class= "col-md-12 col-12 px-0">
                <?php include(__DIR__ . "/navbar.php");; ?>
            </div>
        </div>

        <div class="row" id="middle-content">
            <div class="col-12 col-lg-2 mb-3 mb-lg-0" id="left-sidebar">
                <?php include(__DIR__ . "/leftsidebar.php"); ?>
            </div>
            <div class="col-12 col-lg-10" id="main-content">
                <?php include_once(__DIR__ . "/postview.php"); ?>
            </div>
            
        </div>

        <div class="row" id="lower-bar">
            <div class="col-md-1 col-12">
                <a href="index.php?controller=post&action=showCreateForm" class="btn btn-primary" id="create-post-btn">
                    + Create Post
                </a>
            </div>
        </div>

        <div class="row" id="footer">
            <div class="col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>

    </div>

</body>
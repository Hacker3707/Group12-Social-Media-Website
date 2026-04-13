<!DOCTYPE html>
<head>
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                include_once("search_result.php");
                ?>
            </div>
        </div>

        <div class="row" id = "footer">
            <div class= "col-md-12 col-12">
                <p class="text-center">© 2026 Passo Social Media. All rights reserved.</p>
            </div>
        </div>
        
    </div>
</body>
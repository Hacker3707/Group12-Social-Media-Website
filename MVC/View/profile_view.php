<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="CSS/profile.css">
</head>

<body>
    <div class="container-fluid" id = "profile-container">

        <div class="row" id = "navbar">
            <div class= "col-md-12 col-12">
                <?php include 'navbar.php'; ?>
            </div>
        </div>

        <div class="row">
            <div class= "col-md-2 col-12">
                
            </div>

            <div class= "col-md-8 col-12" id = "profile-banner">
                Placeholder for Banner.
            </div>

            <div class= "col-md-2 col-12">
                
            </div>
        </div>

        <div class="row">
            <div class= "col-md-2 col-12">
                
            </div>

            <div class="col-md-8 col-12" id="profile-info">
                <div class="d-flex align-items-center">

                    <img src="../../Materials/Picture/Passo.png" 
                        class="rounded-circle" width="100" height="100" 
                        alt="Profile Picture">

                    <div class="ml-3 flex-grow-1">
                        <h2>Username</h2>
                        <p>Bio: This is a short bio about the user.</p>
                    </div>

                    <div class="ml-auto">
                        <button class="btn btn-primary" id="edit-profile-btn">
                            Edit Profile
                        </button>
                    </div>

                </div>
            </div>
            
            <div class= "col-md-2 col-12">
                
            </div>
        </div>

        <div class="row">
            <div class= "col-md-2 col-12">
                
            </div>

            <div class= "col-md-8 col-12" id = "profile-navbar">
                Placeholder for Profile Navigation.
            </div>

            <div class= "col-md-2 col-12">
                
            </div>
        </div>

        <div class="row" id = "middle-content">
            <div class= "col-md-2 col-12">
                
            </div>
            <div class= "col-md-8 col-12" id = "main-content">
                <h1>Profile Page</h1>
                <p>This is the profile page. You can view and edit your profile information here.</p>
            </div>
            <div class= "col-md-2 col-12">
                
            </div>

        </div>

    </div>
</body>
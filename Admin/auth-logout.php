<?php
// Initialize the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    session_destroy();
    // header("location: auth-login.php");
    // exit;
}
?>
<?php include 'partials/main.php'; ?>


    <head>
        
        <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Recover password')); ?>

        <?php include 'partials/head-css.php'; ?>

    </head>

    <body class="bg-pattern">
        <div class="bg-overlay"></div>
        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-6 col-md-8">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <div class="text-center">
                                        <a href="auth-login.php" class="">
                                            <img src="https://voldebug.in/assets/img/Voldebug_logo_black.png" alt="" height="90" class="auth-logo logo-dark mx-auto">
                                            <img src="https://voldebug.in/assets/img/Voldebug_logo_black.png" alt="" height="90" class="auth-logo logo-light mx-auto">
                                        </a>
                                    </div>
                                    <!-- end row -->
                                    <h4 class="fs-1 text-dark mt-2 mb-5 text-center">Logged Out</h4>
                                    <p class="mb-2 text-center ">For Login Click Below:</p>
                                    <a href="auth-login.php" class="fw-medium"><button class="btn btn-primary text-center"> Login  </button> </a>
                                    <!-- <form class="form-horizontal" action="index.php">
                                        <div class="row">
                                            <div class="col-md-12">

                    
                                            </div>
                                        </div>
                                    </form> -->
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            
                            <p class="text-white-50">© <script>document.write(new Date().getFullYear())</script> Voldebug. Crafted with <i class="mdi mdi-heart text-danger"></i> by Voldebug</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- end Account pages -->

        <?php include 'partials/vendor-scripts.php'; ?>

        <script src="assets/js/app.js"></script>

    </body>
</html>

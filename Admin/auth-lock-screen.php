<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>


    <head>
        
        <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Lock Screen')); ?>

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
                                <div class="">
                                    <div class="text-center">
                                        <a href="index.php" class="">
                                            <img src="https://voldebug.in/assets/img/Voldebug.png" alt="" height="24" class="auth-logo logo-dark mx-auto">
                                            <img src="https://voldebug.in/assets/img/Voldebug.png" alt="" height="24" class="auth-logo logo-light mx-auto">
                                        </a>
                                    </div>
                                    <!-- end row -->
                                    <h4 class="font-size-18 text-muted mt-2 text-center">Locked Screen</h4>
                                    <p class="mb-5 text-center">Enter your password to unlock the screen!</p>
                                    <form class="form-horizontal" action="index.php">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="user-thumb text-center m-b-30">
                                                    <img src="https://voldebug.in/assets/img/Voldebug.png" class="rounded-circle avatar-lg img-thumbnail mx-auto d-block" alt="thumbnail">
                                                </div>

                                                <div class="mt-4">
                                                    <label class="form-label" for="userpassword">Password</label>
                                                    <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                                                </div>
                                                <div class="d-grid mt-4">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Unlock</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p class="text-white-50">Not you ? return<a href="auth-login.php" class="fw-medium text-primary"> Sign In </a> </p>
                            <p class="text-white-50">© <script>document.write(new Date().getFullYear())</script> Upzet. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- end Account pages -->

        <?php include 'partials/vendor-scripts.php'; ?>

    </body>
</html>

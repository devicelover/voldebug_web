<?php
// Initialize the session
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

// Include config file
require_once "partials/config.php";
// include 'authentication.php';


// Define variables and initialize with empty values
$useremail = $password = "";
$useremail_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter useremail.";
    } else {
        $useremail = trim($_POST["useremail"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($useremail_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, useremail, password FROM users WHERE useremail = ?";

        if ($stmt = mysqli_prepare($con, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = $useremail;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if useremail exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $useremail, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["useremail"] = $useremail;
                            $_SESSION["username"] = $username;
                            

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if useremail doesn't exist
                    $useremail_err = "No account found with that useremail.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($con);
}

?>
<?php include 'partials/main.php'; ?>


    <head>
        
        <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Login')); ?>

        <?php include 'partials/head-css.php'; ?>

    </head>

    <body class="bg-pattern">
        <div class="bg-overlay"></div>
        <div class="account-pages mt-4 pt-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="">
                                    <div class="text-center">
                                        <a href="index.php" class="">
                                            <img src="https://voldebug.in/assets/img/Voldebug_logo_black.png" alt="" height="90" class="auth-logo logo-dark mx-auto">
                                            <img src="https://voldebug.in/assets/img/Voldebug_logo_black.png" alt="" height="80" class="auth-logo logo-light mx-auto">
                                        </a>
                                    </div>
                                    <!-- end row -->
                                    <h4 class="font-size-18 text-muted mt-1 text-center">Welcome Back !</h4>
                                    <p class="mb-3 text-center">Sign in to continue to Voldebug.</p>
                                    <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-4 <?= !empty($useremail_err) ? 'has-error' : ''; ?>">
                                                    <label class="form-label" for="useremail">Useremail</label>
                                                    <input type="text" class="form-control" id="useremail" name="useremail" placeholder="Enter Useremail">
                                                    <span class="text-danger"><?php echo $useremail_err; ?></span>
                                                </div>
                                                <div class="mb-4 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                                    <label class="form-label" for="userpassword">Password</label>
                                                    <input type="password" class="form-control" id="userpassword" name="password"  placeholder="Enter password">
                                                    <span class="text-danger"><?php echo $password_err; ?></span>
                                                </div>

                                                <div class="row">
                                                    <!-- <div class="col">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="customControlInline">
                                                            <label class="form-label" class="form-check-label" for="customControlInline">Remember me</label>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-6">
                                                        <div class="text-md-end mt-3 mt-md-0">
                                                            <a href="auth-recoverpw.php" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-grid mt-4">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <!-- <p class="text-white-50">Don't have an account ? <a href="auth-register.php" class="fw-medium text-primary"> Register </a> </p> -->
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

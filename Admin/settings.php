<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Settings</title>

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Settings')); ?>
        
    <!-- jvectormap -->
    <link href="assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

    <!-- Bootstrap link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


    <?php include 'partials/head-css.php'; ?>
</head>
<body>
    <?php include 'partials/body.php'; ?>
    <div id="layout-wrapper">

        <?php include 'partials/menu.php'; ?>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug' , 'title' => 'Settings')); ?>

                <?php
                $sql = "SELECT * FROM settings WHERE id = 1";
                $result = mysqli_query($con, $sql);
                $settings = mysqli_fetch_assoc($result);
                ?>
                        
                    <div class="row mb-4">
                        <div class="col-xl-11 mx-3 p-3">
                            
                            <!-- <h2 class="my-4">Settings</h2> -->
                            <form id="settingsForm" method="post" action="save_settings.php">
<?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="name">Site Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($settings['name']) ? $settings['name'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($settings['phone']) ? $settings['phone'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Company Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($settings['email']) ? $settings['email'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="map">Map</label>
                                <textarea class="form-control" id="map" name="map" rows="3"><?php echo isset($settings['map']) ? $settings['map'] : ''; ?></textarea>

                            </div>
                            <div class="form-group">
                                <label for="address">Full Address with Pincode</label>
                                <input type="text" class="form-control" id="address" name="address" 
                                value="<?php echo isset($settings['address']) ? $settings['address'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="url" class="form-control" id="facebook" name="facebook" value="<?php echo isset($settings['facebook']) ? $settings['facebook'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="github">Github</label>
                                <input type="url" class="form-control" id="github" name="github" value="<?php echo isset($settings['github']) ? $settings['github'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <input type="url" class="form-control" id="instagram" name="instagram" value="<?php echo isset($settings['instagram']) ? $settings['instagram'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="linkedin">LinkedIn</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?php echo isset($settings['linkedin']) ? $settings['linkedin'] : ''; ?>" placeholder="https://linkedin.com/company/voldebug">
                            </div>
                            <div class="form-group">
                                <label for="twitter">Twitter / X</label>
                                <input type="url" class="form-control" id="twitter" name="twitter" value="<?php echo isset($settings['twitter']) ? $settings['twitter'] : ''; ?>" placeholder="https://x.com/voldebug">
                            </div>

                            <button type="submit" class="btn btn-primary" name="submit">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS link -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <?php include 'partials/right-sidebar.php'; ?>
    <?php include 'partials/vendor-scripts.php'; ?>

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="assets/js/pages/datatables.init.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</body>
</html>
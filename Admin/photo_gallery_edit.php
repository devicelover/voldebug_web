<?php
require_once __DIR__ . '/../includes/csrf.php';
include 'partials/session.php'; 
include 'partials/main.php'; 
include 'partials/config.php'; 
?>
<?php include 'authentication.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Photo Gallery')); ?>
    
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />  
    <?php include 'partials/head-css.php'; ?>
</head>
<body>
    <?php include 'partials/body.php'; ?>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Photo Gallery')); ?>

                    <?php
                    if (isset($_GET['id'])) {
                        $user_id = $_GET['id'];
                        $sql = "SELECT * FROM photo_gallery WHERE id = '$user_id' LIMIT 1";
                        $res = $con->query($sql);

                        if (mysqli_num_rows($res) > 0) {
                            $row = mysqli_fetch_array($res);
                            ?>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="code.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id'] ?? ''); ?>">
                                                    <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($row['image'] ?? ''); ?>">
                                                    
                                                    <div class="col-md-12 mb-3">
                                                        <label for="image">Upload image</label>
                                                        <input type="file" name="image" class="form-control border-primary">
                                                        <?php if ($row['image']): ?>
                                                            <div class="mt-2">
                                                                <img src="images/gallery/<?php echo htmlspecialchars($row['image'] ?? ''); ?>"  height="200px" alt="Gallery Image" class="border border-primary border-2 rounded">
                                                            </div>
                                                        <?php else: ?>
                                                            <p>No image available</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 mt-3">
                                                    <button type="submit" name="update_photo" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div> <!-- end card -->
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <?php
                        } else {
                            echo "<h4>No Record Found</h4>";
                        }
                    }
                    ?>  
                </div> <!-- container-fluid -->
            </div> <!-- End Page-content -->

            <?php include 'partials/footer.php'; ?>
        </div> <!-- end main content-->
    </div> <!-- END layout-wrapper -->

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
</html>

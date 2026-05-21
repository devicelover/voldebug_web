<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<?php include 'authentication.php'; ?>


<?php
include 'partials/config.php';

try {
    $query = "SELECT * FROM services";
    $result = mysqli_query($con, $query);

    if (!$result) {
        throw new Exception("Query failed: " . mysqli_error($con));
    }

    $services = mysqli_fetch_all($result, MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

mysqli_close($con);
?>

<html lang="en">
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Services')); ?>

    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Admin Panel - Services</title> -->

        
    <!-- jvectormap -->
    <link href="assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

    <?php include 'partials/head-css.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


</head>
<body>
    <?php include 'partials/body.php'; ?>
    
    <!-- Begin Page -->
    <div id="layout-wrapper">

    <?php include 'partials/menu.php'; ?>
    <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug' , 'title' => 'Services')); ?>

                        <div class="row mb-4">
                            <div class="col-xl-11 mx-3 p-3">
                                <!-- <h3>Services</h3> -->
                                <form action="code.php" method="POST" class="" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                                    <div class="row mx-auto">
                                        <div class="col-lg-10">
                                            <div class="mb-3">
                                                <label class="form-label">Service Name</label>
                                                <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Enter Name" name="name">
                                            </div>
                                        </div>

                                        <div class="col-lg-10">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea type="text" class="form-control border border-primary p-2 rounded" placeholder="Enter Description" name="description"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-10">
                                            <div class="mb-3">
                                                <label class="form-label">Image</label>
                                                <input type="file" class="form-control border border-primary p-2 rounded" placeholder="Image" accept="image/*" name="image" id="image">
                                            </div>
                                        </div>

                                        <div class="col-lg-10">
                                            <div class="mb-3 ">
                                                <label class="form-label">Service Category</label>
                                                <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Service Category" name="service_category">
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-control border border-primary p-2 rounded" name="status" required>
                                                    <option value="Active" selected>Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-lg-3 mt-4">
                                            <div class="mb-3">
                                            <button type="submit" name="service" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>
                <?php include 'partials/footer.php'; ?>  
    </div>

    </div>


    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
</html>

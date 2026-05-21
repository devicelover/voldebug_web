<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


<head>

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Career')); ?>

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <?php include 'partials/head-css.php'; ?>

</head>

<?php include 'partials/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?php include 'partials/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->


    <!-- Modal -->


    <!-- Display -->

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Career')); ?>

                <div class="row">
                    <!-- <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Details About Carrer</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div> -->
                    <form action="code.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                        <div class="modal-body p-4">
                            <div class="col-md-12 mb-2">
                                <label for="">Job Name</label>
                                <input type="text" name="name" class="form-control border-primary" placeholder="Job Name"
                                    required>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="">Job Description</label>
                                    <textarea name="description" class="form-control border-primary" placeholder="Enter Description" required rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">

                                <label for="">Qualification</label>
                                <input type="text" name="qualification_info" class="form-control border-primary" placeholder="Qualification Requiments"
                                    required>
                            </div>

                        <div class="col-md-3 mt-3">
                            <button type="submit" name="add_career" class="btn btn-primary">Save </button>
                        </div>
                        </div>
                </div>

            </div>

            </form>



        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?php include 'partials/footer.php'; ?>

    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

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
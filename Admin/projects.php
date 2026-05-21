<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


<head>

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Projects')); ?>

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
< id="layout-wrapper">

    <?php include 'partials/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->


    <!-- Modal -->


    <!-- Display -->

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Project')); ?>

                <div class="row">
                    <!-- <div class="modal-header">
                        <h1 class="modal-title fs-5 p-3" id="exampleModalLabel">Add Details About Project</h1>
                    </div> -->
                    <form action="code.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                        <div class="modal-body p-4">
                            <div class="col-md-12 mb-3">
                                <label for="">Project Name</label>
                                <input type="text" name="name" class="form-control border-primary" placeholder="Product Name" required>
                            </div>

                            <div class="col-md-12 mb-3 ">
                                <div class="form-group">
                                    <label for="">Small Description</label>
                                    <textarea name="smalldescription" class="form-control border-primary"
                                        placeholder="Enter Small Description " required rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="">Long Description</label>
                                    <textarea name="longdescription" class="form-control border-primary"
                                        placeholder="Enter Long Description" required rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="">Client Name</label>
                                <input type="text" name="client_name" class="form-control border-primary" placeholder="Client Name"
                                    required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="">Link</label>
                                <input type="text" name="link" class="form-control border-primary"
                                    placeholder="enter Link of the Project " required>
                            </div>

                        
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="">Starting Date</label>
                                <input type="date" name="time" class="form-control border-primary" placeholder="Date" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="">End Date</label>
                                <input type="date" name="Finished_time" class="form-control border-primary" placeholder="Date"
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control border border-primary p-2 rounded" name="status" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Upload image</label>
                            <input type="file" name="image" accept="image/*" class="form-control border-primary" required>
                        </div>
                        <div class="col-md-3 m-1">
                            <button type="submit" name="add_project" class="btn btn-primary">Save </button>

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
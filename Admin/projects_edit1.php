<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Data Tables')); ?>

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <?php include 'partials/head-css.php'; ?>
</head>

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

                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Tables', 'title' => 'Data Tables')); ?>
                <?php
                if (isset($_GET['id'])) {
                    $user_id = $_GET['id'];
                    $sql = "SELECT * FROM projects WHERE id = '$user_id' LIMIT 1";
                    $res = $con->query($sql);

                    if (mysqli_num_rows($res) > 0) {
                        $projectitem = mysqli_fetch_array($res);
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <form action="code.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($projectitem['id'] ?? ''); ?>">
                                                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($projectitem['image'] ?? ''); ?>">

                                                <div class="col-md-12">
                                                    <label for="">Project Name</label>
                                                    <input type="text" name="name" class="form-control border-primary" value="<?= htmlspecialchars($projectitem['name'] ?? '') ?>" placeholder="Project Name" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Small Description</label>
                                                        <textarea name="smalldescription" class="form-control border-primary" placeholder="Enter Small Description" required rows="3"><?php echo htmlspecialchars($projectitem['short_description'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Long Description</label>
                                                        <textarea name="longdescription" class="form-control border-primary" placeholder="Enter Long Description" required rows="3"><?php echo htmlspecialchars($projectitem['long_description'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="">Client Name</label>
                                                    <input type="text" name="client_name" class="form-control border-primary" value="<?= htmlspecialchars($projectitem['client_name'] ?? '') ?>" placeholder="Client Name" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="">Link</label>
                                                    <input type="text" name="link" class="form-control border-primary" placeholder="Enter Link of the Project" value="<?= htmlspecialchars($projectitem['links'] ?? '') ?>">
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Starting Date</label>
                                                        <input type="date" name="time" class="form-control border-primary" value="<?= htmlspecialchars($projectitem['time'] ?? '') ?>" placeholder="Date/Time" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">End Date</label>
                                                        <input type="date" name="Finished_time" class="form-control border-primary" value="<?= htmlspecialchars($projectitem['Finished_time'] ?? '') ?>" placeholder="Date/Time" required>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mb-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-control border border-primary p-2 rounded" name="status" required>
                                                            <option value="Active" <?php if ($projectitem['toggle'] == 'Active') echo 'selected'; ?>>Active</option>
                                                            <option value="Inactive" <?php if ($projectitem['toggle'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="">Upload image</label>
                                                    <input type="file" name="image" class="form-control border-primary">
                                                    <?php if ($projectitem['image']): ?>
                                                        <div class="mt-2">
                                                            <img src="images/project_images/<?= htmlspecialchars($projectitem['image'] ?? '') ?>" height="200px" alt="Project Image" class="border border-primary border-2 rounded">
                                                        </div>
                                                    <?php else: ?>
                                                        <p>No image available</p>
                                                    <?php endif; ?>
                                                </div>

                                                    <div class="col-md-3 m-2">
                                                        <button type="submit" name="update_project" class="btn btn-primary">Update</button>
                                                    </div>
                                            </div>

                                        </form>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div>
                    <?php
                    } else {
                        echo "<h4>No Record Found</h4>";
                    }
                }
                ?>
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


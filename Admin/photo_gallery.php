<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Photo Gallery')); ?>

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
                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Photo Gallery')); ?>

                <!-- Modal
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Photos</h1>
                </div> -->
                <form action="code.php" method="post" enctype="multipart/form-data" class="mb-5">
<?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label for="image">Upload Image</label>
                            <input type="file" name="image" class="form-control border-primary" required>
                        </div>
                        <div class="col-md-3 mt-2">
                            <button type="submit" name="add_photo" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    
                </form>


                <div class="row mb-4">
                            <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-centered border table-bordered dt-responsive nowrap p-0">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Id</th>
                                                                <th scope="col">Photo</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>

                                                        <?php
                                                            $sql = "SELECT * FROM photo_gallery";
                                                            $result = $con->query($sql);
                                                             
                                                        ?>

                                                        <tbody class="text-center">
                                                            <?php foreach($result as $row): ?>
                                                            <tr>
                                                                <td> <?php echo $row['id']?> </td>
                                                                <td>
                                                                    <?php if ($row['image']) : ?>
                                                                        <div class="mt-2">
                                                                            <img src="images/gallery/<?= $row['image'] ?>" width="100px" height="100px" alt="Gallery Image">
                                                                        </div>
                                                                    <?php else : ?>
                                                                        <p>No image available</p>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="fs-1">
                                                                    <a href="photo_gallery_edit.php?id=<?= $row['id']?>"><button type="button" class="btn btn-outline-success btn-sm mr-2">Edit</button></a>
                                                                    <button class="btn btn-outline-danger btn-sm"  data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>">Delete</button>
                                                                </td>
                                                            </tr>

                                                            <!-- Delete Modal -->
                                                            <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $row['id']; ?>">Delete Photo</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete the photo?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            <form action="photo_gallery_delete.php" method="POST">
<?php echo csrf_field(); ?>
                                                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                                <button type="submit" name="delete_photo" class="btn btn-danger">Delete</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?php endforeach; ?>        
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                            <!-- end card-body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!-- end col -->
                                </div>
                            </div>
                        </div>





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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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

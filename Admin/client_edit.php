<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Client')); ?>
        
        <!-- DataTables -->
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />  

        <?php include 'partials/head-css.php'; ?>

        <style>
        .social-icon i {
        font-size: 1.7em; 
        }

        .status-circle {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
        }
        
        .status-active {
        background-color: green;
        }

        .status-inactive {
        background-color: red;
    }
    
    </style>


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

                        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug' , 'title' => 'Client')); ?>



                        <div class="row mb-4">
                            <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-centered border table-bordered dt-responsive nowrap p-0">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col"></th>
                                                                <th scope="col">Id</th>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Description</th>
                                                                <th scope="col">Email</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>

                                                        <?php
                                                            $sql = "SELECT * FROM clients";
                                                            $result = $con->query($sql);
                                                             
                                                        ?>

                                                        <tbody class="text-center">
                                                            <?php foreach($result as $row): ?>
                                                            <tr>
                                                                <td><img src="images/client_images/<?php echo htmlspecialchars($row['images'] ?? ''); ?>"  width="50" class="avatar-xs rounded-circle"></td>
                                                                <td> <?php echo htmlspecialchars($row['id'] ?? ''); ?> </td>
                                                                <td> <?php echo htmlspecialchars($row['name'] ?? ''); ?> </td>
                                                                <td><?php echo implode(' ', array_slice(explode(' ', $row['description']), 0, 2)); ?> ...</td>               
                                                                <td> <?php echo htmlspecialchars($row['email'] ?? ''); ?> </td>  
                                                                <td><span class="status-circle <?php echo $row['toggle'] == 'Active' ? 'status-active' : 'status-inactive'; ?>"></span></td>
                                                                <td>
                                                                    <a href="client_edit1.php?id=<?= $row['id']?>"><button type="button" class="btn btn-outline-success btn-sm mr-2">Edit</button></a>
                                                                    <button class="btn btn-outline-danger btn-sm"  data-toggle="modal" data-target="#deleteModal<?php echo htmlspecialchars($row['id'] ?? ''); ?>">Delete</button>
                                                                </td>
                                                            </tr>

                                                            <!-- Delete Modal -->
                                                            <div class="modal fade" id="deleteModal<?php echo htmlspecialchars($row['id'] ?? ''); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo htmlspecialchars($service['id'] ?? ''); ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo htmlspecialchars($row['id'] ?? ''); ?>">Delete Service</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete the clients <strong><?php echo htmlspecialchars($row['name'] ?? ''); ?></strong>?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            <form action="client_delete.php" method="POST">
                                                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'] ?? ''); ?>">
                                                                                <button type="submit" name="delete_client" class="btn btn-danger">Delete</button>
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

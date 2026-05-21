<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<?php include 'authentication.php'; ?>


<?php
include 'partials/config.php';

try {
    $query = "SELECT * FROM blog";
    $result = mysqli_query($con, $query);

    if (!$result) {
        throw new Exception("Query failed: " . mysqli_error($con));
    }

    $blog = mysqli_fetch_all($result, MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

mysqli_close($con);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Blog</title>

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Blog')); ?>
        
    <!-- jvectormap -->
    <link href="assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

    <?php include 'partials/head-css.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
<body>
    <?php include 'partials/body.php'; ?>
    
    <!-- Begin Page -->
    <div id="layout-wrapper">

    <?php include 'partials/menu.php'; ?>
    <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug' , 'title' => 'Blog')); ?>

                        <div class="row mb-4">
                            <div class="col-xl-12">
                                <h3>Service Blog</h3>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-centered table-bordered dt-responsive nowrap p-0">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col"></th>
                                                                <th scope="col">Id</th>
                                                                <th scope="col">Title</th>
                                                                <th scope="col">Client Name</th>
                                                                <th scope="col">Blog Category</th>
                                                                <th scope="col">Start Date</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center">
                                                            <?php foreach ($blog as $b): ?>
                                                            <tr>
                                                                <td><img src="images/blog_images/<?php echo htmlspecialchars($b['main_img'] ?? ''); ?>"  width="50" class="avatar-xs rounded-circle"></td>
                                                                <td><?php echo htmlspecialchars($b['id'] ?? ''); ?></td>
                                                                <td><?php echo htmlspecialchars($b['title'] ?? ''); ?></td>
                                                                <td><?php echo htmlspecialchars($b['client_name'] ?? ''); ?></td>
                                                                <td><?php echo htmlspecialchars($b['category'] ?? ''); ?></td>
                                                                <td><?php echo htmlspecialchars($b['date'] ?? ''); ?></td>
                                                                <td><span class="status-circle <?php echo $b['status'] == 'Active' ? 'status-active' : 'status-inactive'; ?>"></span></td>
                                                                <td>
                                                                    <a href="blog_edit1.php?id=<?php echo $b['id']; ?>"><button type="button" class="btn btn-outline-success btn-sm mr-2 m-2">Edit</button></a>
                                                                    <button class="btn btn-outline-danger btn-sm"  data-toggle="modal" data-target="#deleteModal<?php echo htmlspecialchars($b['id'] ?? ''); ?>">Delete</button>
                                                                </td>
                                                            </tr>

                                                            <!-- Delete Modal -->
                                                            <div class="modal fade" id="deleteModal<?php echo htmlspecialchars($b['id'] ?? ''); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo htmlspecialchars($b['id'] ?? ''); ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo htmlspecialchars($b['id'] ?? ''); ?>">Delete Service</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete the service <strong><?php echo htmlspecialchars($b['name'] ?? ''); ?></strong>?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            <form action="code.php" method="POST">
                                                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($b['id'] ?? ''); ?>">
                                                                                <button type="submit" name="delete_service" class="btn btn-danger">Delete</button>
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

                    </div>
                </div>
                <?php include 'partials/footer.php'; ?>  
    </div>

    </div>


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <?php include 'partials/right-sidebar.php'; ?>
    <?php include 'partials/vendor-scripts.php'; ?>

    <!-- Required datatable js -->
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

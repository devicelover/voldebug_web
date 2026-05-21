<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'authentication.php'; ?>

<?php
include 'partials/config.php';

try {
    $query = "SELECT * FROM career";
    $result = mysqli_query($con, $query);

    if (!$result) {
        throw new Exception("Query failed: " . mysqli_error($con));
    }

    $team = mysqli_fetch_all($result, MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

mysqli_close($con);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Admin Panel - Career</title> -->

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Career')); ?>
        
    <!-- jvectormap -->
    <link href="assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

    <!-- Bootstrap link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


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
<body>
    <?php include 'partials/body.php'; ?>
    <div id="layout-wrapper">

        <?php include 'partials/menu.php'; ?>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug' , 'title' => 'Career')); ?>

               
                        
                    <div class="row mb-4">
                        <div class="col-xl-12">
                            <h3>Career Details</h3>
                                    <div class="card" >
                                        <div class="card-body ">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-bordered  nowrap p-1">
                                                    <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Id</th>
                                                                <th scope="col">Job Name</th>
                                                                <th scope="col">Job Description</th>
                                                                <th scope="col">Qualification</th>
                                                                <th scope="col">Edit</th>
                                                            </tr>
                                                    </thead>
                                                    <tbody class="text-center">
                                                    <?php foreach ($team as $t): ?>

                                                    <tr>
                                                        <td><?php echo htmlspecialchars($t['id'] ?? ''); ?></td>
                                                        <td class="p-0"><?php echo htmlspecialchars($t['job_name'] ?? ''); ?></td>
                                                        <td class="p-0"><?php echo htmlspecialchars($t['job_description'] ?? ''); ?></td>
                                                        <td class="p-0"><?php echo htmlspecialchars($t['qualification'] ?? ''); ?></td>
                                                        <td class="p-3">
                                                            <a href="Career_edit1.php?id=<?php echo $t['id']; ?>"><button type="button" class="btn btn-outline-success btn-sm mr-2" >Edit</button></a>
                                                            <button class="btn btn-outline-danger btn-sm m-2"  data-toggle="modal" data-target="#deleteModal<?php echo htmlspecialchars($t['id'] ?? ''); ?>">Delete</button>
                                                        </td>
                                                    </tr>

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal<?php echo htmlspecialchars($t['id'] ?? ''); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo htmlspecialchars($t['id'] ?? ''); ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo htmlspecialchars($t['id'] ?? ''); ?>">Delete Team Member</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete the team member <strong><?php echo htmlspecialchars($t['name'] ?? ''); ?></strong>?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            <form action="code.php" method="POST">
                                                                                <?php echo csrf_field(); ?>
                                                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($t['id'] ?? ''); ?>">
                                                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
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
            </div>
        </div>
        <?php include 'partials/footer.php'; ?>
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
</html>
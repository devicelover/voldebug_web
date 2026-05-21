<?php
require_once __DIR__ . '/../includes/csrf.php';
include 'partials/session.php';
include 'partials/main.php';
include 'partials/config.php';
include 'authentication.php'; 


$t = null; // Initialize service variable

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM `career` WHERE id=$id";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        $t = mysqli_fetch_assoc($result);
        if (!$t) {
            echo "<script>alert('Team Member not found.'); window.location.href='Career_edit1.php';</script>";
            exit;
        }
    } else {
        echo "Error: " . mysqli_error($con);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $job_name = $_POST['job_name'];
        $job_description = $_POST['job_description'];
        $qualification = $_POST['qualification'];

        $query = "UPDATE career SET job_name='$job_name', job_description='$job_description', qualification='$qualification' WHERE id=$id";
        if (mysqli_query($con, $query)) {
           header("Location: Career_edit.php");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
} 
else {
    echo "<script>alert('No Member ID provided.'); window.location.href='Career_edit1.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Service</title>
    <link href="assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
    <?php include 'partials/head-css.php'; ?>
</head>
<body>
<?php include 'partials/body.php'; ?>
    
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Services')); ?>
                <h3>Edit Job Details</h3>
                <div class="row mb-4">
                    <div class="col-xl-11 mx-3 p-3">
                        <?php if ($t): ?>
                        <form action="" method="POST" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                            <div class="row mx-auto">
                            <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Job Name</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Enter Job Name" name="job_name" value="<?php echo htmlspecialchars($t['job_name'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Job Description</label>
                                        <input class="form-control border border-primary p-2 rounded" placeholder="Enter Description" name="job_description" value=
                                        "<?php echo htmlspecialchars($t['job_description'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Qualification</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Qualification" name="qualification" value="<?php echo htmlspecialchars($t['qualification'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3 mt-4">
                                    <div class="mb-3">
                                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php else: ?>
                        <p>No team member found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
</div>

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

<?php mysqli_close($con); ?>

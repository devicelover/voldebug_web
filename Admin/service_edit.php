<?php
include 'partials/session.php';
include 'partials/main.php';
include 'partials/config.php';
include 'authentication.php'; 


$service = null; // Initialize service variable

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM services WHERE id=$id";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        $service = mysqli_fetch_assoc($result);
        if (!$service) {
            echo "<script>alert('Service not found.'); window.location.href='service_edit.php';</script>";
            exit;
        }
    } else {
        echo "Error: " . mysqli_error($con);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['service_category'];
        $status = $_POST['status'];

        $image_query = "";
        if ($_FILES["image"]["name"]) {
            $target_dir = "images/service_images/";
            $image = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false && !file_exists($target_file) && $_FILES["image"]["size"] <= 500000 && 
                in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_query = ", image='$image'";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "File is not valid or already exists.";
            }
        }

        $query = "UPDATE services SET name='$name', description='$description', service_category='$category', toggle='$status' $image_query WHERE id=$id";
        if (mysqli_query($con, $query)) {
           header("Location: service_edit1.php");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
} 
else {
    echo "<script>alert('No service ID provided.'); window.location.href='service_edit.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Service Details </title>
    <link hrf="assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
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
                <h3>Edit Service</h3>
                <div class="row mb-4">
                    <div class="col-xl-11 mx-3 p-3">
                        <?php if ($service): ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row mx-auto">
                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Service Name</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Enter Name" name="name" value="<?php echo htmlspecialchars($service['name'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control border border-primary p-2 rounded" placeholder="Enter Description" name="description" required><?php echo htmlspecialchars($service['description'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <!-- <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control border border-primary p-2 rounded" name="image">
                                        <input type="hidden" name="current_image" value=" //$service['image']; ">
                                    </div>
                                </div> -->

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Service Category</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Enter Category" name="service_category" value="<?php echo htmlspecialchars($service['service_category'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control border border-primary p-2 rounded" name="status" required>
                                            <option value="Active" <?php if ($service['toggle'] == 'Active') echo 'selected'; ?>>Active</option>
                                            <option value="Inactive" <?php if ($service['toggle'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Upload image</label>
                                    <input type="file" name="image" class="form-control border border-primary">
                                    <?php if ($service['image']): ?>
                                        <div class="mt-2">
                                            <img src="images/service_images/<?= htmlspecialchars($service['image'] ?? '') ?>"  height="200px" alt="Project Image" class="border-primary border rounded border-2">
                                        </div>
                                    <?php else: ?>
                                    <p>No image available</p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-3 mt-4">
                                    <div class="mb-3">
                                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php else: ?>
                        <p>No service found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'partials/right-sidebar.php'; ?>
    <?php include 'partials/vendor-scripts.php'; ?>

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

<?php mysqli_close($con); ?>

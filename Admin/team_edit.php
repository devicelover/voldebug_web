<?php
include 'partials/session.php';
include 'partials/main.php';
include 'partials/config.php';
include 'authentication.php'; 


$t = null; // Initialize service variable

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM `team` WHERE id=$id";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        $t = mysqli_fetch_assoc($result);
        if (!$t) {
            echo "<script>alert('Team Member not found.'); window.location.href='team_edit1.php';</script>";
            exit;
        }
    } else {
        echo "Error: " . mysqli_error($con);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $designation = $_POST['designation'];     
        $email = $_POST['email'];
        $starting_date = $_POST['starting_date'];
        $ending_date = $_POST['ending_date'];
        $facebook = $_POST['facebook'];
        $github = $_POST['github'];
        $instagram = $_POST['instagram'];
        $linkedin = $_POST['linkedin'];
        $status = $_POST['status'];

        $image_query = "";
        if ($_FILES["image"]["name"]) {
            $target_dir = "images/team_images/";
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

        $query = "UPDATE `team` SET name='$name', designation='$designation', email= '$email',starting_date='$starting_date', ending_date='$ending_date', facebook='$facebook', github='$github', instagram='$instagram', linkedin='$linkedin', toggle='$status'  $image_query WHERE id=$id";
        if (mysqli_query($con, $query)) {
           header("Location: team_edit1.php");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
} else {
    echo "<script>alert('No Member ID provided.'); window.location.href='team.php';</script>";
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
                <h3>Edit Team Member Details</h3>
                <div class="row mb-4">
                    <div class="col-xl-11 mx-3 p-3">
                        <?php if ($t): ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row mx-auto">
                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Member Name</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Enter Name" name="name" value="<?php echo htmlspecialchars($t['name'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Designation</label>
                                        <input class="form-control border border-primary p-2 rounded" placeholder="Enter Designation" name="designation" value=
                                        "<?php echo htmlspecialchars($t['designation'] ?? ''); ?>">
                                    </div>
                                </div> 

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control border border-primary p-2 rounded" placeholder="Enter Email" name="email" value=
                                        "<?php echo htmlspecialchars($t['email'] ?? ''); ?>">
                                    </div>
                                </div> 

                               <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Starting Date</label>
                                        <input type="date" class="form-control border border-primary p-2 rounded" placeholder="Enter Starting Date" name="starting_date" value=
                                        "<?php echo htmlspecialchars($t['starting_date'] ?? ''); ?>">
                                    </div>
                                </div>   
                                <?php if ($t['toggle'] == 'Inactive') {?>
                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Ending Date</label>
                                        <input type="date" class="form-control border border-primary p-2 rounded" placeholder="Enter Ending Date" name="ending_date" value=
                                        "<?php echo htmlspecialchars($t['ending_date'] ?? ''); ?>">
                                    </div>
                                </div>
                                    <?php }?>
                                <!-- <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control border border-primary p-2 rounded" name="image">
                                        <input type="hidden" name="current_image" value=" echo $t['image']; ?>">
                                    </div>
                                </div> -->

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Facebook</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Facebook" name="facebook" value="<?php echo htmlspecialchars($t['facebook'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Giithub</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Github" name="github" value="<?php echo htmlspecialchars($t['github'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Linkedin</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Linkedin" name="linkedin" value="<?php echo htmlspecialchars($t['linkedin'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" class="form-control border border-primary p-2 rounded" placeholder="Instagram" name="instagram" value="<?php echo htmlspecialchars($t['instagram'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="col-lg-10">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control border border-primary p-2 rounded" name="status" required>
                                            <option value="Active" <?php if ($t['toggle'] == 'Active') echo 'selected'; ?>>Active</option>
                                            <option value="Inactive" <?php if ($t['toggle'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Upload image</label>
                                    <input type="file" name="image" class="form-control border border-primary">
                                    <?php if ($t['image']): ?>
                                        <div class="mt-2">
                                            <img src="images/team_images/<?= htmlspecialchars($t['image'] ?? '') ?>"  height="200px" alt="Project Image" class="border-primary border rounded border-2">
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

<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit Blog')); ?>

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <?php include 'partials/head-css.php'; ?>

    <!-- Include TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/rgfq0t60iqsw46ompq1ismp0ubqoi446udjzhm1uwm9vodo8/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

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

                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Tables', 'title' => 'Edit Blog')); ?>
                <?php
                if (isset($_GET['id'])) {
                    $user_id = $_GET['id'];
                    $sql = "SELECT * FROM blog WHERE id = '$user_id' LIMIT 1";
                    $res = $con->query($sql);

                    if (mysqli_num_rows($res) > 0) {
                        $blogItem = mysqli_fetch_array($res);
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <form action="code.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($blogItem['id'] ?? ''); ?>">
                                                <input type="hidden" name="main_img" value="<?php echo htmlspecialchars($blogItem['main_img'] ?? ''); ?>">

                                                <div class="col-md-12">
                                                    <label for="">Title</label>
                                                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($blogItem['title'] ?? '') ?>" placeholder="Blog Title" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="">Client Name</label>
                                                    <input type="text" name="client_name" class="form-control" value="<?= htmlspecialchars($blogItem['client_name'] ?? '') ?>" placeholder="Client Name" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Description</label>
                                                        <textarea name="longdescription" class="form-control" placeholder="Enter Long Description" id="blogarea"><?php echo htmlspecialchars($blogItem['description'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <label for="">Blog Category</label>
                                                    <input type="text" name="blog_category" class="form-control" placeholder="Blog Category" value="<?= htmlspecialchars($blogItem['category'] ?? '') ?>">
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Starting Date</label>
                                                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($blogItem['date'] ?? '') ?>" placeholder="Date/Time" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-12 mb-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-control border border-primary p-2 rounded" name="status" required>
                                                            <option value="Active" <?php if ($blogItem['status'] == 'Active') echo 'selected'; ?>>Active</option>
                                                            <option value="Inactive" <?php if ($blogItem['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="">Blog Main Image</label>
                                                    <input type="file" name="new_image" class="form-control">
                                                    <?php if ($blogItem['main_img']): ?>
                                                        <div class="mt-2">
                                                     <?php   $imgPath = $blogItem['main_img']; ?>
                                                            <img src="images/blog_images/<?= $blogItem['main_img']; ?>"  height="200px" alt="Blog Main Image" class="rounded border border-primary border-2">
                                                        </div>
                                                    <?php else: ?>
                                                        <p>No image available</p>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col-md-3 mt-3">
                                                    <button type="submit" name="update_blog" class="btn btn-primary">Update</button>
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

 <!-- Initialize TinyMCE -->
 <script>
        document.addEventListener('DOMContentLoaded', function () {
            tinymce.init({
                selector: '#blogarea',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [
                    { value: 'First.Name', title: 'First Name' },
                    { value: 'Email', title: 'Email' },
                ],
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
            });

            // Add event listener to handle form submission
            document.querySelector('form').addEventListener('submit', function (event) {
                // Trigger TinyMCE to save content back to the textarea
                tinymce.triggerSave();

                // Check if the textarea is empty
                const textarea = document.querySelector('#blogarea');
                if (!textarea.value.trim()) {
                    alert('Please fill out the long description.');
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>

</body>
</html>


<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'AI School Gallery')); ?>
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <?php include 'partials/head-css.php'; ?>
</head>

<?php include 'partials/body.php'; ?>

<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'AI School Gallery')); ?>

                <form action="code.php" method="post" enctype="multipart/form-data" class="mb-5">
<?php echo csrf_field(); ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Add Event Photo</h5>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="image">Upload Image</label>
                                    <input type="file" name="image" class="form-control border-primary" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="title">Event Title / Caption</label>
                                    <input type="text" name="title" class="form-control" placeholder="e.g. AI Workshop Session">
                                </div>
                                <div class="col-md-4 mb-2 d-flex align-items-end">
                                    <button type="submit" name="add_ai_school_photo" class="btn btn-primary">Add Photo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-centered border table-bordered dt-responsive nowrap p-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Id</th>
                                                <th>Photo</th>
                                                <th>Title</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                            $sql = "SELECT * FROM ai_school_gallery ORDER BY sort_order ASC, id DESC";
                                            $result = $con->query($sql);
                                            if ($result && mysqli_num_rows($result) > 0):
                                                while ($row = mysqli_fetch_assoc($result)):
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <?php if (!empty($row['image'])): ?>
                                                    <img src="images/ai_school_gallery/<?php echo htmlspecialchars($row['image']); ?>" width="100" height="80" alt="" style="object-fit: cover; border-radius: 6px;">
                                                    <?php else: ?>
                                                    <span class="text-muted">No image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['title'] ?? '-'); ?></td>
                                                <td>
                                                    <a href="ai_school_gallery_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm mr-2">Edit</a>
                                                    <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>">Delete</button>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Photo</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to delete this photo?</div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <form action="ai_school_gallery_delete.php" method="POST" class="d-inline">
<?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                <button type="submit" name="delete_ai_school_photo" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endwhile; else: ?>
                                            <tr><td colspan="4" class="text-center py-4">No photos yet. Add your first event photo above.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

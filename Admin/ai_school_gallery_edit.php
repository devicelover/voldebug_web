<?php
require_once __DIR__ . '/../includes/csrf.php';
include 'partials/session.php';
include 'partials/main.php';
include 'partials/config.php';
include 'authentication.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit AI School Photo')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<body>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Edit AI School Photo')); ?>

                <?php
                if (isset($_GET['id'])) {
                    $id = (int) $_GET['id'];
                    $stmt = $con->prepare("SELECT * FROM ai_school_gallery WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res && $row = $res->fetch_assoc()):
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="code.php" method="post" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id'] ?? ''); ?>">
                                    <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($row['image']); ?>">
                                    <div class="mb-3">
                                        <label for="title">Event Title / Caption</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title'] ?? ''); ?>" placeholder="e.g. AI Workshop Session">
                                    </div>
                                    <div class="mb-3">
                                        <label for="image">Replace Image (leave empty to keep current)</label>
                                        <input type="file" name="image" class="form-control border-primary">
                                        <?php if (!empty($row['image'])): ?>
                                        <div class="mt-2">
                                            <img src="images/ai_school_gallery/<?php echo htmlspecialchars($row['image']); ?>" height="200" alt="" class="border rounded">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" name="update_ai_school_photo" class="btn btn-primary">Update</button>
                                    <a href="ai_school_gallery.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <p>Photo not found.</p>
                <a href="ai_school_gallery.php" class="btn btn-primary">Back to Gallery</a>
                <?php endif;
                } else {
                    echo "<p>No ID provided.</p>";
                    echo '<a href="ai_school_gallery.php" class="btn btn-primary">Back to Gallery</a>';
                }
                ?>
            </div>
        </div>
        <?php include 'partials/footer.php'; ?>
    </div>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
<script src="assets/js/app.js"></script>
</body>
</html>

<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>


    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Data Tables')); ?>
        
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

                        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Tables' , 'title' => 'Data Tables')); ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                    <form action="code.php" method="post" >
<?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        <?php
                                        if (isset($_GET['id'])) 
                                        {
                                            $user_id = $_GET['id'];
                                            $sql="SELECT * FROM users WHERE id = '$user_id' LIMIT 1"; 
                                            $con->query($sql);   
                                            $res = $con->query($sql) ;    
                                            if (mysqli_num_rows($res)>0 ) 
                                            {
                                                foreach ($res as $row ) 
                                                {
                                                    ?> 
                                                    <input type="hidden" name=user_id value = <?php echo htmlspecialchars($row['id'] ?? ''); ?>>
                                                        <div class="form-group">
                                                        <Label> Name</Label>
                                                        <input type="text" name="name" value="<?php echo htmlspecialchars($row['username'] ?? ''); ?>" class="form-control" placeholder="Name" required >
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <Label>Phone Number</Label>
                                                            <input type="number" name="phone" value="<?php echo htmlspecialchars($row['userphone'] ?? ''); ?>" class="form-control" placeholder="phonen Number"  >
                                                        </div> 

                                                        <div class="form-group">
                                                            <Label>Email id</Label>
                                                            <input type="email" name="email" value="<?php echo htmlspecialchars($row['useremail'] ?? ''); ?>" class="form-control" placeholder="email" required >
                                                        </div>  
                                                    
                                                        <div class="form-group">
                                                            <Label>Password</Label>
                                                            <input type="password" name="password" value="<?php echo htmlspecialchars($row['password'] ?? ''); ?>" class="form-control" placeholder="Password" required>
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <Label>Give Role</Label>
                                                            <select name="role_as" class="form-control" required>
                                                                <option value="">Select</option>
                                                                <option value="0">User</option>
                                                                <option value="1">Admin</option>
                                                            </select> 
                                                        </div> -->
                                                </div>
                                        
                                                                <?php
                                                }
                                            }   
                                            else {
                                                echo "<h4>No Record Found</h4>";
                                            }                             
                                        }   

                                        
                                        ?>
                                        

                                        
                                    <div class="modal-footer">
                                        <button type="submit" name="updateUser" class="btn btn-info">Update </button>
                                    </div>
                                </form>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
        
                        

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

    </body>
</html>

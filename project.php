<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Voldebug — Cybersecurity, AI, Web Development and Digital Services">
	<meta name="keywords"
		content="Voldebug, cybersecurity, penetration testing, web development, AI school, digital services, India">
	<meta name="robots" content="index, follow">
	<meta name="author" content="Voldebug">
	<title>Voldebug - Tech That Makes You Smarter.</title>
	
	<!-- favicon -->
	<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
	<!-- bootstrap css -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<!-- Kurson Css -->
	<link rel="stylesheet" href="assets/css/kursor.css">
	<!-- slick slider css -->
	<link rel="stylesheet" href="assets/css/slick-theme.css">
	<link rel="stylesheet" href="assets/css/slick.css">
	<!-- animate css -->
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<!-- Nice select css -->
	<link rel="stylesheet" href="assets/css/nice-select.css">
	<!-- fontawesome css -->
	<link rel="stylesheet" href="assets/css/fontawesome.min.css">
	<!-- normalize css -->
	<link rel="stylesheet" href="assets/css/normalize.css">
	<!-- modal video css -->
	<link rel="stylesheet" href="assets/css/modal-video.min.css">
	<!-- Sal Animation css -->
	<link rel="stylesheet" href="assets/css/sal.css">
	<!-- swiper slider css -->
	<link rel="stylesheet" href="assets/css/swiper.min.css">
	<!-- meanmenu css -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<!-- style css -->
	<link rel="stylesheet" href="style.css">
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="page-template">
	<!-- preloader -->
	<div id="preloader" class="inso-preloader">
		<span class="loader"></span>
	</div>
	<!-- preloader end -->
	<!-- offcanvase menu -->
	<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
		include_once "offcanvase_menu.php";
	?>
	<!-- offcanvase menu end -->


	<!-- header menu -->
	<?php
		include_once "header.php";
	?>
	<!-- header menu end -->

	<!-- breadcrumb -->
	<div class="breadcrumb pt-40 pb-40">
		<div class="container">
			<div class="breadcrumb__vector">
				<img src="assets/img/animated-icon/graph.png" alt="graph">
				<img src="assets/img/animated-icon/star-icon.png" alt="graph">
			</div>
			<div class="breadcrumb__wrapper">
				<div class="breadcrumb__wrapper--text">
					<h5 class="title">Project</h5>
					<p>For businesses with digital products or services,
						ensuring a user-friendly and visually appealing
						interface is vital.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Project</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->
	<!-- our project -->
	<div class="project pt-100 pb-100">
        <div class="container">
            <div class="row">
            <?php
                $sql = "SELECT * FROM projects";
                $project = $con->query($sql);
                while ($row = mysqli_fetch_assoc($project)) {
					if($row['toggle'] == 'Active'){
            ?>
                <div class="col-lg-4 col-md-6">
                <div class="project__single">
                    <div class="project__single--box">
                    <img class="img-fluid" src="Admin/images/project_images/<?php echo $row['image'] ?>" alt="">
                    <div class="project__meta">
                        <div class="project__meta--info">
                            <span class="project-name"><a href="#"><?php echo $row["name"] ?></a></span>
                            <span class="project-by">description: Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque natus ducimus in.<?php echo $row["short_description"] ?></span>
                        </div>
                        <div class="project__meta--link">
                        <a href="project-details.php?id=<?php echo $row['id']; ?>" class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            <?php } } ?>
            </div>
        </div>
    </div>

	<!-- our project end -->

	<!-- footer -->
	<?php
		include_once "footer.php";
	?>
	<!-- footer end -->

	<!-- back to top -->
	<div class="scroll active-scroll">
		<svg class="scroll__circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
			<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
				style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 244.073px;">
			</path>
		</svg>
	</div>
	<!-- back to top end -->
	
	<!-- all jquery plugins here -->
	<!-- jquery -->
	<script src="assets/js/Jquery-3.7.0.js"></script>
	<!-- bootstrap- jquery included -->
	<script src="assets/js/bootstrap.min.js"></script>
	<!-- Kursor- jquery included -->
	<script src="assets/js/kursor.min.js"></script>
	<!-- slick slider- jquery included -->
	<script src="assets/js/slick.min.js"></script>
	<!-- niceselect - jquery included -->
	<script src="assets/js/jquery.nice-select.min.js"></script>
	<!-- imageloaded pkgd - jquery included -->
	<script src="assets/js/imagesloaded.pkgd.min.js"></script>
	<!-- appear - jquery included -->
	<script src="assets/js/appear.min.js"></script>
	<!-- modal video - jquery included -->
	<script src="assets/js/jquery-modal-video.min.js"></script>
	<!-- meamenu - jquery inclued -->
	<script src="assets/js/jquery.meanmenu.min.js"></script>
	<!-- Sal - jquery included -->
	<script src="assets/js/sal.js"></script>
	<!-- swiper - jquery included -->
	<script src="assets/js/swiper.min.js"></script>
	<!-- main js -->
	<script src="assets/js/main.js"></script>
</body>
</html>
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
					<h5 class="title">Our Team</h5>
					<p>For businesses with digital products or services,
						ensuring a user-friendly and visually appealing
						interface is vital.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Team</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- our team -->

	<div class="team pt-70 pb-70">
		<div class="container">
			<div class="row">

			<?php

				$sql = "SELECT * FROM team ";
				$team = $con->query($sql);
				while ($row = mysqli_fetch_assoc($team)) {
					if($row['toggle'] == 'Active'){
										
			?>

				<div class="col-xl-4 col-lg-6 col-md-6">
					<div class="team__single">
						<div class="team__single--box ">
							<div class="team__single--wrap">
							<img class="img-fluid" src="Admin/images/team_images/<?php echo $row['image'] ?>" alt="image">
								<div class="team-meta">
									<div class="team-meta-info">
										<a href="#"><?php echo $row["name"] ?></a>
										<span><?php echo $row["designation"] ?></span>
									</div>
									<div class="team-meta-social">
										<div class="icon"><i class="fa-solid fa-plus"></i></div>
										<div class="social-links">
											<a href="<?php echo $row["facebook"] ?>"  target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
											<a href="<?php echo $row["instagram"] ?>"  target="_blank"><i class="fa-brands fa-instagram"></i></a>
											<a href="<?php echo $row["linkedin"] ?>"  target="_blank"><i class="fa-brands fa-linkedin"></i></a>
											<a href="<?php echo $row["github"] ?>"  target="_blank"><i class="fa-brands fa-github"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } }?>
			</div>
		</div>
	</div>
	<!-- our team end -->

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
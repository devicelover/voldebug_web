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
	<title>Voldebug - Careers</title>
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
				<img loading="lazy" src="assets/img/animated-icon/graph.png" alt="graph">
				<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="graph">
			</div>
			<div class="breadcrumb__wrapper">
				<div class="breadcrumb__wrapper--text">
					<h5 class="title">Career</h5>
					<p>Help us build secure, AI-powered, production-ready software — from our Vadodara studio to clients around the world.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Career</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	

	<!-- career start -->

	<div class="career pt-30 pb-10">
		<div class="container">
		<h5 class="magic-hover">Join our Team</h5>
			<div class="row">
				<div class="col-lg-7 col-xl-8">
					
					<?php
						$sql = "SELECT * FROM career";
						$career = $con->query($sql);
						while($row = mysqli_fetch_assoc($career)){			

					?>
					<div class="career__wrapper">
						<div class="career__wrapper--single">
							<!-- single job -->
							<div class="single-job">
								<h6>Full Time / Part Time</h6>
								<h3 class="magic-hover"><?php echo $row['job_name'] ?></h3>
								<p><?php echo $row['job_description'] ?></p>
								<h6 class="qualification">Qualification: <span><?php echo $row['qualification'] ?></span></h6>
								<div class="single-job__btn">
									<a href="career-details.php?id=<?php echo $row['id']; ?>" class="btn apply-btn">Apply Now</a>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				
				<div class="col-lg-5 col-xl-4">
					<div class="sidebar">
						<div class="sidebar__widget service-details__text--content">
							<h5><span>All Job Options</span></h5>
							<div class="sidebar__widget--category">
								<ul>
									<?php
										$sql = "SELECT * FROM career";
										$career = $con->query($sql);
										while ($row = mysqli_fetch_assoc($career)) {
									?>
									<li class="cat-item"><a href="career-details.php?id=<?php echo $row['id']; ?>"><?php echo $row['job_name']; ?> <span><i class="fa-regular fa-arrow-right-long"></i></span></a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="our__info">
						<div class="our__info--text">
							<h3 class="magic-hover">Work with a team that ships secure software and trains the next generation of engineers.</h3>
								<p>At Voldebug we work across cybersecurity, AI, web development and digital services — and we run a hands-on AI &amp; security school alongside the studio. Interns and full-time engineers get real projects, a dedicated mentor, and a GitHub repo tracked from day one.<br><br> Every intern leaves with a verifiable, digitally-signed completion letter and concrete open-source work they can point to. If that sounds like the kind of place you want to grow in, pick a role and apply — we reply to every application.</p>
						</div>
						<div class="our__info--facility">

							<div class="our__info--facility--item">
								<div class="our__info--facility--icon">
									<i class="fa-solid fa-award"></i>
								</div>
								<div class="our__info--facility--text">
									<h6>Real projects</h6><p>Not toy exercises — you work on code that ships to real clients and users.</p>
								</div>
							</div>
							<div class="our__info--facility--item">
								<div class="our__info--facility--icon">
									<i class="fa-light fa-hand-holding-heart"></i>
								</div>
								<div class="our__info--facility--text">
									<h6>Dedicated mentor</h6><p>Each intern is paired with an engineer who reviews your PRs and unblocks you.</p>
								</div>
							</div>
							<div class="our__info--facility--item">
								<div class="our__info--facility--icon">
									<i class="fa-sharp fa-regular fa-heart"></i>
								</div>
								<div class="our__info--facility--text">
									<h6>Verifiable credential</h6><p>Completion letters carry a QR code anyone can scan to confirm authenticity.</p>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- career end -->

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
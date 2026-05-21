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
					<h5 class="title">Services</h5>
					<p>For businesses with digital products or services,
						ensuring a user-friendly and visually appealing
						interface is vital.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Services</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- service -->
	<div class="service pt-70 pb-70">
		<div class="container">
			<div class="row">

			<?php

				$sql = "SELECT * FROM services ";
				$service = $con->query($sql);
				while ($row = mysqli_fetch_assoc($service)) {
					if($row['toggle'] == 'Active'){
										
			?>

				<div class="col-lg-4 col-xl-4 col-md-6 mb-4">
					<div class="service__single">
						<div class="service__single--box">
							<div class="icon"><img src="Admin/images/service_images/<?php echo $row['image'] ?>" alt=""></div>
							<div class="service__single--box-meta">
								<div class="meta-text">
									<!-- <a href="#">Web Design</a> -->
									<a href="#"><span><?php echo $row["name"] ?></span></a>
									<span>Category: <?php echo $row["service_category"] ?></span>
								</div>
								<div class="meta-linkbtn">
									<a href="service-details.php?id=<?php echo $row['id']; ?>" class="link-btn"><i
											class="fa-sharp fa-light fa-arrow-right-long"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<?php } } ?>
			</div>
		</div>
	</div>
	<!-- service end -->

	<!-- work marque -->
	<div class="our-work pt-100 pb-100">
		<div class="our-work__wrapper">
			<div class="our-work__content">
			<?php
                    $sql = "SELECT * FROM services ";
                    $services = $con->query($sql);
					while ($row = mysqli_fetch_assoc($services)) {
						
					
                ?>
				<div class="single-work-item">
					<span class="title"><?php echo $row["name"] ?></span>
					<span class="start">*</span>
				</div>
				<?php } ?>
				
			</div>
		</div>
	</div>
	<!-- work marque end -->

	<!-- our faq -->
	<section class="faq pt-100 pb-100">
		<div class="container">
			<div class="section">
				<div class="section__content">
					<h6 class="section__sub">Faq</h6>
					<h3 class="section__title">Frequently Asked <strong>Question</strong></h3>
				</div>
			</div>
			<div class="row align-items-center">
				<div class="col-lg-4 col-xl-4">
					<div class="faq__image">
						<div class="faq__image-img">
							<img src="assets/img/faq.jpg" alt="faq">
						</div>
					</div>
				</div>
				<div class="col-lg-7 offset-lg-1 offset-xl-0 col-xl-8">
					<div class="faq__accordion">
						<div class="accordion accordion-flush" id="accordion">
							<div class="accordion-item">
								<div class="accordion-header">
									<div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#one"
										aria-expanded="false" aria-controls="one" role="button">
										<h6>What Are project - Based rates?</h6>
									</div>
								</div>
								<div id="one" class="accordion-collapse collapse show" data-bs-parent="#accordion">
									<div class="accordion-text">If your business hosts events or exhibitions, creative
										services can assist with event planning,
										booth design, promotional materials, and post-event marketing.
									</div>
								</div>
							</div>

							<div class="accordion-item">
								<div class="accordion-header">
									<div class="accordion-button collapsed" data-bs-toggle="collapse"
										data-bs-target="#two" aria-expanded="false" aria-controls="two" role="button">
										<h6>Developing core web applications</h6>
									</div>
								</div>
								<div id="two" class="accordion-collapse collapse" data-bs-parent="#accordion">
									<div class="accordion-text">If your business hosts events or exhibitions, creative
										services can assist with event planning,
										booth design, promotional materials, and post-event marketing.
									</div>
								</div>
							</div>

							<div class="accordion-item">
								<div class="accordion-header">
									<div class="accordion-button collapsed" data-bs-toggle="collapse"
										data-bs-target="#three" aria-expanded="false" aria-controls="three"
										role="button">
										<h6>Design should enrich our day</h6>
									</div>
								</div>
								<div id="three" class="accordion-collapse collapse" data-bs-parent="#accordion">
									<div class="accordion-text">
										If your business hosts events or exhibitions, creative
										services can assist with event planning,
										booth design, promotional materials, and post-event marketing.
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<div class="accordion-header">
									<div class="accordion-button collapsed" data-bs-toggle="collapse"
										data-bs-target="#four" aria-expanded="false" aria-controls="four" role="button">
										<h6>Are you Working with WordPress?</h6>
									</div>
								</div>
								<div id="four" class="accordion-collapse collapse" data-bs-parent="#accordion">
									<div class="accordion-text">
										If your business hosts events or exhibitions, creative
										services can assist with event planning,
										booth design, promotional materials, and post-event marketing.
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- our faq end -->

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
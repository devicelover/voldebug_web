<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
include 'config.php';

if (isset($_GET['id'])) {
    $service_id = $_GET['id'];
    $sql = "SELECT * FROM blog WHERE id = $service_id";
    $result = $con->query($sql);
    $service = $result->fetch_assoc();
} else {
    // Redirect to services page if no ID is provided
    header("Location: service.php");
    exit();
}
?>


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
					<h1 class="title"><span><?php echo $service['category']?>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Service Details</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- service details -->
	<div class="service-details m-4 pt-10 pb-10">
		<div class="container">
			<div class="row">
				<div class="col-8 service-details__text--content">
				<h3><span><?php echo $service['title']; ?></span></h3>
				<div class="service-details__img">
					<img src="Admin/images/blog_images/<?php echo $service['main_img'] ?>" alt="">
				</div>
				<p><?php echo $service['description']; ?></p>
				</div>
			<!-- </div> -->
			<!-- <div class="row">
				<div class="col-lg-8">
					<article class="service-details__text">
						<div class="service-details__text--content">
						
							<h3>Marketing<span> Strategy</span></h3>
							<p>Establishing a memorable and cohesive brand identity is crucial. Creative services can
								help you design a
								unique logo, choose brand colors and fonts, and create brand guidelines that ensure
								consistency in all
								your marketing materials.
							</p>
							<h3>A Marketing <span>strategy typically</span> includes several kay
								<span>components</span>
							</h3>
							<ul class="service-details__keypoints">
								<li>From designing marketing materials like brochures, flyers. and posters to creating
									eye-catching social
									media graphics and web assets, graphic design services can help you visually
									communicate.
								</li>
								<li>Content is king in the digital age. Creative services can help you create engaging
									written content,
									stunning images, and captivating videos that resonate with your target audience.
								</li>
								<li>Your website is often the first point of contact with potential customers. Expert
									web designers can
									create user-friendly, visually appealing websites that reflect your brand and
									visitors into customers.
								</li>
								<li>Your website is often the first point of contact with potential customers. Expert
									web designers can
									create user-friendly, visually appealing websites that reflect your brand and
									visitors into customers.
								</li>
								<li>Crafting compelling and persuasive copy for your website, advertisements, and other
									marketing
									materials is crucial. Professional copywriters can help you convey your message
									effectively.
								</li>
								<li>If your business uses printed materials, such as business cards, brochures, or
									banners, creative services
									can design and produce them to ensure they make a positive impact.
								</li>
							</ul>
						</div>
					</article> -->
					<!-- <div class="service-details__faq">
						<div class="section">
							<div class="section__content">
								<h3 class="section__title">Frequently Asked <strong>Question</strong></h3>
							</div>
						</div>
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
										<div class="accordion-text">If your business hosts events or exhibitions,
											creative
											services can assist with event planning,
											booth design, promotional materials, and post-event marketing.
										</div>
									</div>
								</div>

								<div class="accordion-item">
									<div class="accordion-header">
										<div class="accordion-button collapsed" data-bs-toggle="collapse"
											data-bs-target="#two" aria-expanded="false" aria-controls="two"
											role="button">
											<h6>Developing core web applications</h6>
										</div>
									</div>
									<div id="two" class="accordion-collapse collapse" data-bs-parent="#accordion">
										<div class="accordion-text">If your business hosts events or exhibitions,
											creative
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
											data-bs-target="#four" aria-expanded="false" aria-controls="four"
											role="button">
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
					</div> -->
				<!-- </div> -->
				<div class="col-lg-4">
					<div class="sidebar">
						<!-- <div class="sidebar__widget">
							<div class="sidebar__widget--search">
								<form action="#">
									<input type="text" placeholder="Search Here" id="search" name="s">
									<button type="submit"><i class="fa-regular fa-search"></i></button>
								</form>
							</div>
						</div> -->

						<div class="sidebar__widget service-details__text--content">
							<h5><span>All Blogs</span></h5>
							<div class="sidebar__widget--category">
								<ul>
								<?php

									$sql = "SELECT * FROM blog ";
									$service = $con->query($sql);
									while ($row = mysqli_fetch_assoc($service)) {
															
								?>
									<li class="cat-item"><a href="service-details.php?id=<?php echo $row['id']?>"><?php echo $row['title']?> <span><i
													class="fa-regular fa-arrow-right-long"></i></span></a></li>
									
											
									<?php } ?>
								</ul>
							</div>
						</div>

						<!-- <div class="sidebar__widget">
							<h5 class="text-center">Have a Project?</h5>
							<div class="sidebar__widget--button">
								<a href="contact.html" class="btn contact">Contact Us</a>
							</div>
						</div> -->

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- service details end -->

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
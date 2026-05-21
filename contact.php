
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
	<div class="breadcrumb pt-80 pb-80">
		<div class="container">
			<div class="breadcrumb__vector">
				<img src="assets/img/animated-icon/graph.png" alt="graph">
				<img src="assets/img/animated-icon/star-icon.png" alt="graph">
			</div>
			<div class="breadcrumb__wrapper">
				<div class="breadcrumb__wrapper--text">
					<h5 class="title">Contact Us</h5>
					<p>For businesses with digital products or services,
						ensuring a user-friendly and visually appealing
						interface is vital.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Contact Us</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- contact us -->
	<?php
		$sql = "SELECT * FROM settings ";
		$settings = $con->query($sql);
		$row = mysqli_fetch_assoc($settings);
	?>
	<div class="contact pt-80 pb-80">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<div class="contact__single">
						<div class="contact__single--item">
							<div class="icon">
								<i class="fa-solid fa-phone"></i>
							</div>
							
							<div class="content">
								<h6 class="title">Phone</h6>
								<a href="tel:+<?php echo $row["phone"] ?>">+<?php echo $row["phone"] ?></a>
								<a href="mailto:<?php echo $row["email"] ?>"><?php echo $row["email"] ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact__single">
						<div class="contact__single--item">
							<div class="icon">
								<i class="fa-solid fa-location-dot"></i>
							</div>
							<div class="content">
								<h6 class="title">Address</h6>
								<p><?php echo $row["address"] ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact__single">
						<div class="contact__single--item">
							<div class="icon">
								<i class="fa-regular fa-clock"></i>
							</div>
							<div class="content">
								<h6 class="title">Opening Hours</h6>
								<p><?php echo $row["opening_day"] ?></p>
								<p><?php echo $row["opening_hours"] ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="contact__form">
					<div class="comment__template contact__template">
						<div class="comment__template--box input__form">
							<form action="mail-sender.php" method="post" class="contact__php">
								<div class="input-group">
									<div class="single-input">
										<label for="name">Full Name</label>
										<input type="text" id="name" name="name" placeholder="Your Name" required="">
									</div>
									
								</div>
								<div class="input-group">
									<div class="single-input">
										<label for="email">Email Address</label>
										<input type="text" id="email" name="email" placeholder="Your email" required="">
									</div>
									<div class="single-input">
										<label for="phone">Phone Number</label>
										<input type="number" id="phone" name="phone" placeholder="Your Number" required="" min="1">
									</div>
									<!-- <div class="single-input">
										<label for="skype">Skype Address</label>
										<input type="text" id="skype" name="skype" placeholder="Your skype">
									</div> -->
								</div>
								<div class="textarea">
									<label for="msg">Message</label>
									<textarea id="msg" name="msg" placeholder="Your Review" required=""></textarea>
								</div>
								<div class="submit-btn">
									<button type="submit" class="main-btn">Send Message</button>
								</div>
							</form>
							<div class="row">
								<div class="col-12">
									<div class="alert alert-success contact__msg" style="display: none" role="alert">
										Your message was sent successfully.
									</div>
								</div>
							</div>
							<!-- end message -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- contact us end -->
	<!-- map -->
	<div class="contact__map">
		<div class="container-fluid w-90">
			<div id="map">
				<?php echo $row["map"] ?>
			</div>
		</div>
	</div>
	<!-- map end -->

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
	<!-- ajax contact form js -->
	<script src="assets/js/ajax-contact.js"></script>
	<!-- ajax contact form js -->
	<script src="assets/js/leaflet-map.js"></script>
	<!-- custom map js -->
	<!-- if you use leaflet map just uncomment down js -->
	<!-- <script src="assets/js/leaflet-custom-map.js"></script> -->
	<!-- Sal - jquery included -->
	<script src="assets/js/sal.js"></script>
	<!-- swiper - jquery included -->
	<script src="assets/js/swiper.min.js"></script>
	<!-- main js -->
	<script src="assets/js/main.js"></script>

</body>

</html>

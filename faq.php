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
	<title>Voldebug - Startup and Technology Website HTML5 Template.</title>
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
	<div class="o-hidden">
		<div class="offcanvase">
			<div class="offcanvase__menu">
				<div class="offcanvase__menu--content">
					<div class="offcanvase__menu--top mb-30 d-flex justify-content-between">
						<div class="offcanvase__menu--logo">
							<div class="offcanvase__logo">
								<a href="index.php">
									<img loading="lazy" src="assets/img/logo/logo.svg" alt="insoand">
								</a>
							</div>
						</div>
						<div class="offcanvase__menu--close-icon">
							<div class="close-menu pointer"><i class="fa-sharp fa-regular fa-xmark"></i></div>
						</div>
					</div>
					<div class="offcanvase-menu o-hidden mb-30"></div>
					<div class="offcanvase__button mb-30">
						<a class="login" href="login.html">Login</a>
						<a class="signup" href="register.html">Sign Up</a>
					</div>
					<div class="offcanvase__menu--contact center">
						<h4 class="offcanvase__menu--contact-title mb-20">Contact Us</h4>
						<div class="offcanvase__menu--contact-text">
							<ul>
								<li><a href="tel:+8801755202096">+8801755202096</a></li>
								<li><a href="mailto:contact@insomniacafe.org">contact@insomniacafe.org</a></li>
							</ul>
							<p>Kushtia Sador, Kushtia, Bangladesh</p>
						</div>
					</div>
					<div class="offcanvase__menu--social">
						<ul class="d-flex justify-content-center gap-3">
							<li class="social-item"><a href="#" target="_blank"><i
										class="fa-brands fa-facebook"></i></a></li>
							<li class="social-item"><a href="#" target="_blank"><i class="fa-brands fa-twitter"></i></a>
							</li>
							<li class="social-item"><a href="#" target="_blank"><i class="fa-brands fa-line"></i></a>
							</li>
							<li class="social-item"><a href="#" target="_blank"><i
										class="fa-brands fa-linkedin"></i></a></li>
						</ul>
					</div>
					<div class="thanks-giving mt-5">
						<img loading="lazy" src="assets/img/thanks.jpg" alt="thank you">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="offcanvas__overlay"></div>
	<!-- offcanvase menu end -->


	<!-- header menu -->
	<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
		include_once "header.php";
	?>
	<!-- header menu end -->

	<!-- breadcrumb -->
	<div class="breadcrumb pt-80 pb-80">
		<div class="container">
			<div class="breadcrumb__vector">
				<img loading="lazy" src="assets/img/animated-icon/graph.png" alt="graph">
				<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="graph">
			</div>
			<div class="breadcrumb__wrapper">
				<div class="breadcrumb__wrapper--text">
					<h5 class="title">Faq</h5>
					<p>Common questions about working with Voldebug — pricing, timelines, ownership, support.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Faq</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- our faq -->
	<section class="faq pt-100 pb-100">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-4 col-xl-4">
					<div class="faq__image">
						<div class="faq__image-img">
							<img loading="lazy" src="assets/img/faq.jpg" alt="faq">
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

	<!-- testimonial -->
	<div class="testimonial pt-100 pb-100">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-5 col-xl-4">
					<div class="testimonial__left">
						<div class="pagination__dots"></div>
						<div class="image">
							<img loading="lazy" src="assets/img/testimonial/testimonial-two.jpg" alt="">
						</div>
					</div>
				</div>
				<div class="col-lg-7 col-xl-8">
					<div class="testimonial__right">
						<div class="testimonial__content slider">
							<div class="swiper-wrapper">
								<div class="swiper-slide">
									<div class="d-flex justify-content-between">
										<div class="rating">
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
										</div>
										<div class="quote">
											<img loading="lazy" src="assets/img/animated-icon/quote-two.png" alt="">
										</div>
									</div>
									<p>High-quality images are essential for marketing materials and websites. Whether you need product photography, headshots, or lifestyle shots, professional photographers can capture the right images for your business. Content is king in the digital age. Creative services can help you create. We dream of a world where
									and where our commitment to continues to drive innovation, inclusivity, and excellence.
									</p>
									<div class="d-flex gap-3 align-items-center mt-20">
										<div class="tauthor">
											<img loading="lazy" src="assets/img/testimonial/author-1.png" alt="">
										</div>
										<div class="tnamedes">
											<h6>Savanna Nguyen
												<span>CEO</span>
											</h6>
			
										</div>
									</div>
								</div>
								<div class="swiper-slide">
									<div class="d-flex justify-content-between">
										<div class="rating">
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
										</div>
										<div class="quote">
											<img loading="lazy" src="assets/img/animated-icon/quote-two.png" alt="">
										</div>
									</div>
									<p>High-quality images are essential for marketing materials and websites. Whether you need product photography, headshots, or lifestyle shots, professional photographers can capture the right images for your business. Content is king in the digital age. Creative services can help you create. We dream of a world where
									and where our commitment to continues to drive innovation, inclusivity, and excellence.
									</p>
									<div class="d-flex gap-3 align-items-center mt-20">
										<div class="tauthor">
											<img loading="lazy" src="assets/img/testimonial/author-1.png" alt="">
										</div>
										<div class="tnamedes">
											<h6>Savanna Nguyen
												<span>CEO</span>
											</h6>
			
										</div>
									</div>
								</div>
								<div class="swiper-slide">
									<div class="d-flex justify-content-between">
										<div class="rating">
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
											<i class="fa-solid fa-star"></i>
										</div>
										<div class="quote">
											<img loading="lazy" src="assets/img/animated-icon/quote-two.png" alt="">
										</div>
									</div>
									<p>High-quality images are essential for marketing materials and websites. Whether you need product photography, headshots, or lifestyle shots, professional photographers can capture the right images for your business. Content is king in the digital age. Creative services can help you create. We dream of a world where
									and where our commitment to continues to drive innovation, inclusivity, and excellence.
									</p>
									<div class="d-flex gap-3 align-items-center mt-20">
										<div class="tauthor">
											<img loading="lazy" src="assets/img/testimonial/author-1.png" alt="">
										</div>
										<div class="tnamedes">
											<h6>Savanna Nguyen
												<span>CEO</span>
											</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="animated__line mt-50"></div>
		</div>
	</div>
	<!-- testimonial end -->

	<!-- footer -->
	<?php
		include_once "footer.php";
	?>
	<!-- footer end -->

	<!-- footer credit -->
	<div class="footer-credit">
		<div class="container">
			<div class="footer-credit--img">
				<a href="index.php"><img loading="lazy" src="assets/img/logo/footer-logo.svg" alt="footer logo"></a>
			</div>
			<div class="footer-credit__wrapper">
				<div class="copy-right">
					All Rights Reserved <a href="#">Voldebug</a>
				</div>
				<div class="footer-links">
					<ul>
						<li><a href="faq.html">Terms of use</a></li>
						<li><a href="faq.html">Privacy Policy</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- footer credit end -->

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
	<!-- main js -->
	<script src="assets/js/main.js"></script>

</body>

</html>
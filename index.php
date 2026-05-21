
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

<body class="home">
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
		include "header.php";
	?>
	<!-- header menu end -->

	<!-- hero area -->
	<div class="hero pt-100 pb-100">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="hero__text">
						<div class="row">
							<div class="col-lg-7">

								<div class="hero__text--content relative">
									<h2>
										Tech That Makes You <em>Smarter</em>
									<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="graph">

									</h2>
									<p>Voldebug Innovations Pvt. Ltd is a company that specializes in providing high-quality
										 web development and mobile app development solutions for businesses of all sizes. 
										 Our mission is to help our clients achieve their online goals by delivering innovative and user-friendly 
										 websites and applications that are tailored to their unique needs and preferences.</p>
									<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="graph">
							<img loading="lazy" src="assets/img/animated-icon/star.png" alt="">


								</div>
							</div>
							<!-- <div class="col-lg-1"></div> -->
							<div class="col-lg-5 hero__image">
							<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="graph">
							<img loading="lazy" src="assets/img/animated-icon/star.png" alt="">
							
							<img loading="lazy" src="image/Voldebug.png" width="500px" alt="graph">
								<!-- <img loading="lazy" src="assets/img/animated-icon/graph.png" alt="graph"> -->
								
							</div>
						</div>
							<div class="hero__button">
								<a href="#" class="rounded-btn">Get in Touch <span><i
										class="fa-sharp fa-light fa-arrow-right-long"></i></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- hero area end -->

	<!-- hero slider -->
	<!-- <div class="hero-slider slider">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="slider__wrapper">
				<?php
                    $sql = "SELECT * FROM photo_gallery ";
                    $gallery = $con->query($sql);
					while ($row = mysqli_fetch_assoc($gallery)) {
                ?>
						<div class="slider__single">
							<img loading="lazy" src="Admin/images/gallery/<?php echo $row['image']; ?>"
								alt="slider">
						</div>
				<?php } ?>
						
					</div>
				</div>
			</div>
		</div>
	</div> -->
	<!-- hero slider end -->

	<!-- opening hour -->
	<!-- <div class="information pt-100 pb-100">
		<div class="information__wrapper animated-marque">
			<div class="information__content">
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6>OPen Hours: <span>SUN - Mon</span>: 9am - 6pm, Thursday: 9am - 5pm, Friday: <span>Closed</span>
					</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Office:- </span>Horinaraonpur Road, Harinarayanpur, Kushtia, Bangladesh</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Get in touch:- </span>Phone: +8801925202096</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Email:- </span>contact@insomniacafe.org</h6>
				</div>

				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6>OPen Hours: <span>SUN - Mon</span>: 9am - 6pm, Thursday: 9am - 5pm, Friday: <span>Closed</span>
					</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Office:- </span>Horinaraonpur Road, Harinarayanpur, Kushtia, Bangladesh</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Get in touch:- </span>Phone: +8801925202096</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Email:- </span>contact@insomniacafe.org</h6>
				</div>

				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6>OPen Hours: <span>SUN - Mon</span>: 9am - 6pm, Thursday: 9am - 5pm, Friday: <span>Closed</span>
					</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Office:- </span>Horinaraonpur Road, Harinarayanpur, Kushtia, Bangladesh</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Get in touch:- </span>Phone: +8801925202096</h6>
				</div>
				<div class="single-information">
					<div class="icon"><img loading="lazy" src="assets/img/animated-icon/star.png" alt="star"></div>
					<h6> <span>Email:- </span>contact@insomniacafe.org</h6>
				</div>
			</div>
		</div>
	</div> -->
	<!-- opening hour end-->

	<!-- About us -->
	<section class="about ">
		<div class="container">
			<div class="row">
				<div class="about__small d-md-block d-lg-none mb-50">
					<div class="about__small--img">
						<div class="about__wrapper--vector">
							<img loading="lazy" src="assets/img/animated-icon/graph.png" alt="">
							<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="">
						</div>
						<div class="about-img">
						<img loading="lazy" data-sal="slide-up" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="Admin/images/gallery/1717475710.jpg" alt="design" title="Design Team">
							<img loading="lazy" data-sal="slide-down" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="Admin/images/gallery/1718785219.jpg" alt="reasearch" title="Reasearch Team">
							<img loading="lazy" data-sal="flip-left" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="Admin/images/gallery/1717478315.jpg" alt="marketing team" title="Marketing Team">
							<img loading="lazy" data-sal="fade" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="image/2.png" alt="developer team" title="Developer Team">
						</div>
					</div>
				</div>
				<div class="about__wrapper">
					<div class="about__wrapper--text">
						<div class="section__content">
							<h6 class="section__sub">About Us</h6>
							<h3 class="section__title">Tech That Makes You <strong>Smart</strong></h3>
							<p>Voldebug Innovations Pvt. Ltd is a company that specializes in providing high-quality web development and mobile app development solutions for businesses of all sizes. Our mission is to help our clients achieve their online goals by delivering innovative and user-friendly websites and applications that are tailored to their unique needs and preferences.</p>
						</div>
						<!-- <ul class="about__wrapper--li">
							<li>Marketing Strategy</li>
							<li>Technology Process</li>
						</ul> -->
						<div class="hero__button">
							<a href="#" class="rounded-btn">Get in Touch <span><i
										class="fa-sharp fa-light fa-arrow-right-long"></i></span></a>
						</div>
					</div>

					<div class="about__wrapper--img d-none d-lg-block">
						<div class="about__wrapper--vector">
							<img loading="lazy" src="assets/img/animated-icon/graph.png" alt="">
							<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="">
							<img loading="lazy" src="assets/img/animated-icon/star.png" alt="">
						</div>
						<div class="about-img">
							<img loading="lazy" data-sal="slide-up" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="Admin/images/gallery/1717475710.jpg" alt="design" title="Design Team">
							<img loading="lazy" data-sal="slide-down" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="Admin/images/gallery/1718785219.jpg" alt="reasearch" title="Reasearch Team">
							<img loading="lazy" data-sal="flip-left" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="Admin/images/gallery/1717478315.jpg" alt="marketing team" title="Marketing Team">
							<img loading="lazy" data-sal="fade" data-sal-delay="300" data-sal-easing="ease-In-Out-Cubic"
								src="image/2.png" alt="developer team" title="Developer Team">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- About us end --> 


	<!-- service -->
	<section class="service pt-100 pb-70">
		<div class="container">
			<div class="section">
				<div class="section__content">
					<h6 class="section__sub">Which Services We Provide</h6>
					<h3 class="section__title">Modern & Intuitive
						web <strong>Solutions</strong> Agency</h3>
				</div>
				<div class="view__all">
					<a href="service.php">View All Services</a>
				</div>
			</div>
			<div class="row">
			<?php

				$sql = "SELECT * FROM services LIMIT 6 ";
				$service = $con->query($sql);
				while($row = mysqli_fetch_assoc($service)) {
					if($row['toggle'] == 'Active'){
										
			?>
			<!-- <div class="row"> -->
				<div class="col-lg-4 col-xl-4 col-md-6 mb-4">
					<div class="service__single">
						<div class="service__single--box">
							<div class="icon"><img loading="lazy" src="Admin/images/service_images/<?php echo $row['image'] ?>" alt=""></div>
							<div class="service__single--box-meta">
								<div class="meta-text">
									<!-- <a href="#">Web Design</a> -->
									<a href="#"><?php echo $row["name"] ?></a>
									<span>Category: <?php echo $row["service_category"] ?></span>
								</div>
								<div class="meta-linkbtn">
									<a href="service-details.php" class="link-btn"><i
											class="fa-sharp fa-light fa-arrow-right-long"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			<?php } }?></div>
				</div>
		</div>
	</section>
	<!-- service end -->


	<!-- work marque -->
	<div class="our-work pt-50 pb-50">
		<div class="our-work__wrapper">
			<div class="our-work__content">
			<?php
                    $sql = "SELECT * FROM services ";
                    $services = $con->query($sql);
					while ($row = mysqli_fetch_assoc($services)) {
						if($row['toggle'] == 'Active'){	
					
                ?>
				<div class="single-work-item">
					<span class="title"><?php echo $row["name"] ?></span>
					<span class="start">*</span>
				</div>
				<?php }  }?>
				
			</div>
		</div>
	</div>
	<!-- work marque end -->

	<!-- our project -->
	<section class="project">
		<div class="container">

			<div class="section">
				<div class="section__content">
					<h6 class="section__sub">Latest Project</h6>
					<h3 class="section__title">Our Latest Awards Winning <strong>Projects</strong> </h3>
				</div>
				<div class="view__all">
					<a href="project.php">View All Projects</a>
				</div>
			</div>
			<div class="row">
					<?php
					$sql = "SELECT * FROM projects LIMIT 6 ";
					$projects = $con->query($sql);
					while($row = mysqli_fetch_assoc($projects)) {					
						if($row['toggle'] == 'Active'){
					?>
				<div class="col-lg-4 col-md-6">
					<div class="project__single" title="E-learning Website Project">
						<div class="project__single--box">
							<img loading="lazy" class="img-fluid" src="Admin/images/project_images/<?php echo $row['image'] ?>" alt="">
							<div class="project__meta">
								<div class="project__meta--info">
									<span class="project-name"><a href="project.php">
										<?php echo $row['name']; ?></a></span>
									<span class="project-by">Team Voldebug</span>
								</div>
								<div class="project__meta--link">
									<a href="project.php" class="link-btn"><i
											class="fa-sharp fa-light fa-arrow-right-long"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } }?>
				
				
			</div>
		</div>
	</section>
	<!-- our project end -->

	<!-- our team -->
	<section class="team pt-70 pb-70">
		<div class="container">
			<div class="section">
				<div class="section__content">
					<h6 class="section__sub">Our Team</h6>
					<h3 class="section__title">Our Experienced <strong>Team</strong> </h3>
				</div>
				<div class="view__all">
					<a href="team.php">View All Team</a>
				</div>
			</div>
			
			<div class="row">

					<?php
						$sql = "SELECT * FROM team LIMIT 6";
						$team = $con->query($sql);
						while ($row = mysqli_fetch_assoc($team)) {
							if($row['toggle'] == 'Active'){	
						
					?>
				<div class="col-xl-4 col-lg-6 col-md-6">
					<div class="team__single">
						<div class="team__single--box">
							<div class="team__single--wrap">
								<img loading="lazy" class="img-fluid" src="Admin/images/team_images/<?php echo $row['image'] ?>" alt="image">
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
		</div>
	</section>
	<!-- our team end -->

	<!-- testimonial -->
	<!-- <div class="testimonial">
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
											<h6>Zent Ekizie
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
											<img loading="lazy" src="assets/img/testimonial/author-2.png" alt="">
										</div>
										<div class="tnamedes">
											<h6>Tamanna Jahan
												<span>Ui Designer</span>
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
											<img loading="lazy" src="assets/img/testimonial/author-3.png" alt="">
										</div>
										<div class="tnamedes">
											<h6>Eliza Stella
												<span>Content Creator</span>
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
	</div> -->
	<!-- testimonial end -->

	<!-- blog -->
	<section class="blog pt-100 pb-70">
		<div class="container">
			<div class="section">
				<div class="section__content">
					<h6 class="section__sub">Our News Article</h6>
					<h3 class="section__title">Our Popular <strong>Blog</strong> </h3>
				</div>
				<div class="view__all">
					<a href="blog.php">View All Blog</a>
				</div>
			</div>

			<!-- blog start -->
			<div class="row">
			<?php
				$sql = "SELECT * FROM blog LIMIT 6";
				$blog = $con->query($sql);
				while ($row = mysqli_fetch_assoc($blog)) {	
					if($row['status'] == 'Active'){				
			?>

				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog.php" class="blog-img">
								<img loading="lazy" src="Admin/images/blog_images/<?php echo $row['main_img'] ?>" alt="blog">
								
							</a>
							<div class="blog__single--meta">
								<a href="blog.php" class="blog--title"><?php echo $row["title"] ?></a>
								<div class="author-date">
									<div class="author">
										<!-- <img loading="lazy" src="assets/img/author/author-1.jpg" alt="author"> -->
										<a href="blog.php"><?php echo $row["client_name"] ?></a>
									</div>
									<div class="date">
										<?php echo $row["date"] ?>
									</div>
								</div>
								<!-- <a href="blog.php"><span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span></a> -->
							</div>
						</div>
					</div>
				</div>
				<?php } }?>
			</div>
		</div>
	</div>
	</section>
	<!-- blog end -->

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
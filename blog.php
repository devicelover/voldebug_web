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
					<h5 class="title">Blog</h5>
					<p>For businesses with digital products or services,
						ensuring a user-friendly and visually appealing
						interface is vital.</p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Blog</li>
					</ul>

				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- blog -->
	<?php

$sql = "SELECT * FROM blog ";
$blog = $con->query($sql);
while ($row = mysqli_fetch_assoc($blog)) {
	if($row['status'] == 'Active'){
						
?>

	<div class="blog pt-100 pb-100">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.php?id=<?php echo $row['id']; ?>" class="blog-img">
								<img src="Admin/images/blog_images/<?php echo $row['main_img'] ?>" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.php?id=<?php echo $row['id']; ?>" class="blog--title"><?php echo $row["title"] ?></a>
								<div class="author-date">
									<div class="author">
										<!-- <img src="assets/img/author/author-1.jpg" alt="author"> -->
										<a href="blog-details.php?id=<?php echo $row['id']; ?>"><?php echo $row["client_name"] ?></a>
									</div>
									<div class="date">
									<?php echo $row['date']; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } }?>
				<!-- <div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-1-2.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">Designing immersive experiences using
									virtual reality and high-quality graphics</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-2.jpg" alt="">
										<a href="#">Shishir Ahmed</a>
									</div>
									<div class="date">
										03 Jan
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-1-3.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">Exploring the different image formats
									and their impact on quality</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-3.jpg" alt="">
										<a href="#">Imrazina</a>
									</div>
									<div class="date">
										28 Dec
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-2-1.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">A Journey through UI Excellence,
									Unveiling My Approach to Creating Visually Pleasing and Intuitive User
									Interfaces</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-2.jpg" alt="author">
										<a href="#">Ma Jessia</a>
									</div>
									<div class="date">
										03 Feb
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-2-2.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">Showcasing my UI Design Projects,
									Emphasizing the Detail and Precision in Every Pixel</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-3.jpg" alt="">
										<a href="#">Peter Jhonson</a>
									</div>
									<div class="date">
										05 Mar
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-2-3.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">An In-Depth UI Design Showcase
									Highlighting My Dedication to Optimal User Experiences</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-1.jpg" alt="">
										<a href="#">Michael Jorin</a>
									</div>
									<div class="date">
										28 Feb
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-4-2.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">A Showcase of My Development Projects,
									Diving into the Code and Technologies That Power Them</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-2.jpg" alt="author">
										<a href="#">Maria Kristina</a>
									</div>
									<div class="date">
										01 May
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-three-2.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">Unveiling the Magic Behind Web
									Development - A Detailed Exhibition of My Coding Endeavors and Solutions</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-3.jpg" alt="">
										<a href="#">Hadi</a>
									</div>
									<div class="date">
										03 Jan
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="blog__single">
						<div class="blog__single--box">
							<a href="blog-details.html" class="blog-img">
								<img src="assets/img/blog/blog-4-1.jpg" alt="blog-1">
								<span class="link-btn"><i class="fa-sharp fa-light fa-arrow-right-long"></i></span>
							</a>
							<div class="blog__single--meta">
								<a href="blog-details.html" class="blog--title">EA Journey through My Web Development
									Endeavors, Illustrating the Evolution of My Digital Creations</a>
								<div class="author-date">
									<div class="author">
										<img src="assets/img/author/author-1.jpg" alt="">
										<a href="#">Towkib</a>
									</div>
									<div class="date">
										28 Jun
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<!-- pagination -->
				<!-- <div class="pagination">
					<ul class="pagination-link">
						<li class="page-item"><a href="#">1</a></li>
						<li class="page-item"><a href="#">2</a></li>
						<li class="page-item"><a href="#">3</a></li>
						<li class="page-item">...</li>
						<li class="page-item active"><a href="#"><i class="fa-light fa-arrow-right-long"></i></a></li>
					</ul>
				</div> -->
			</div>
		</div>
	</div>
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
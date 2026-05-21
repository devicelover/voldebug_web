<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
include 'config.php';

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $sql = "SELECT * FROM projects WHERE id = $project_id";
    $result = $con->query($sql);
    $project = $result->fetch_assoc();
} else {
    // Redirect to services page if no ID is provided
    header("Location: project.php");
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
				<img loading="lazy" src="assets/img/animated-icon/graph.png" alt="graph">
				<img loading="lazy" src="assets/img/animated-icon/star-icon.png" alt="graph">
			</div>
			<div class="breadcrumb__wrapper">
				<div class="breadcrumb__wrapper--text">
					<h1 class="title"><span><?php echo $project['name'] ?></span></h1>
					<p>Short Description: <?php echo $project['short_description']?></p>
				</div>
				<div class="breadcrumb__wrapper--link">
					<ul aria-label="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Project Details</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- breadcrumb end -->

	<!-- project details -->
	<div class="project-details pt-10 pb-10 m-2">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="project-details__wrapper">
						<div class="project-details__info breadcrumb__wrapper--text">
							<h1><span><?php echo $project['name']?></span></h1>
							<ul>
								<li>
									<div class="d-flex item"><span>Client:</span> <span class="content"><?php echo $project['client_name']?></span></div>
								</li>
								<!-- <li>
									<div class="d-flex item"><span>Project:</span> <span class="content">Web
											Design</span></div>
								</li> -->
								<li>
									<div class="d-flex item"><span>Category:</span> <span class="content"><?php echo $project['category']?></span></div>
								</li>
								<li>
									<div class="d-flex item"><span>Project Link:</span> <span class="content"><a
												href="<?php echo $project['links']?>"><?php echo $project['name']?></a></span></div>
								</li>
								<li>
									<div class="d-flex item"><span>Delivered:</span> <span class="content"><?php echo $project['Finished_time']?></span></div>
								</li>
							</ul>
						</div>
						<div class="project-details__text">
							<img loading="lazy" src="Admin/images/project_images/<?php echo $project['image'] ?>" alt="app landing"
								title="App Landing Dashboard">

							<h4>Description</h4>
							<p>Long Description: <?php echo $project['long_description']?></p>
							<!-- <h4>Solutions</h4>
							<p>When seeking creative services for your business, it's essential to choose experienced
								professionals or agencies with a strong portfolio and a deep understanding of
								your industry and target audience. Collaborating with experts can help you create a
								compelling brand presence and stay ahead in a competitive market. If your
								business hosts events or exhibitions, creative services can assist with event planning,
							</p>
							<ul class="bullet-list mb-5">
								<li>Content is king in the digital age. Creative services can help you</li>
								<li>Your website is often the first point of contact with potential customers.</li>
								<li>Content is king in the digital age. Creative services can help you</li>
								<li>Your website is often the first point of contact with potential customers.</li>
							</ul>

							<img loading="lazy" src="assets/img/project/project-details-2.jpg" alt="website"
								title="e-learning Website Ui">
							<h4>Problem</h4> -->
							<!-- <p> Agency is a dynamic and innovative creative agency dedicated to transforming your
								business vision into captivating and memorable experiences. With a team of
								seasoned designers, writers, strategists, and marketers, we're here to breathe life into
								your brand and help it flourish in today's competitive landscape.
								Agency is a dynamic and innovative creative agency dedicated to transforming your
								business vision into captivating and memorable experiences. With a team of
								seasoned designers, writers, strategists, and marketers, we're here to breathe life into
								your brand and help it flourish in today's competitive landscape.
							</p> -->
							<!-- <h4>Result</h4>
							<p> Agency is a dynamic and innovative creative agency dedicated to transforming your
								business vision into captivating and memorable experiences. With a team of
								seasoned designers, writers, strategists, and marketers, we're here to breathe life into
								your brand and help it flourish in today's competitive landscape.
								<br>
								Agency is a dynamic and innovative creative agency dedicated to transforming your
								business vision into captivating and memorable experiences. With a team of
								seasoned designers, writers, strategists, and marketers, we're here to breathe life into
								your brand and help it flourish in today's competitive landscape.
							</p>
							<h4>Visual And Typography Hierarchy</h4> -->
							<!-- <div class="grid-content">
								<p>
									Agency is a dynamic and innovative creative agency dedicated to transforming your
									business vision
									into captivating and memorable experiences. With a team of seasoned designers,
									writers, strategists,
									and marketers, we're here to breathe life into your brand and help it flourish in
									today's competitive
									landscape. Agency is a dynamic and innovative creative agency dedicated
									<br>
									to transforming your business vision into captivating and memorable experiences.
									With a team of
									seasoned designers, writers, strategists, and marketers, we're here to breathe life
									into your brand
									and help it flourish in today's competitive landscape.
								</p>
								<div class="used-tool">
									<div class="tool-img">
										<img loading="lazy" src="assets/img/project/tool-icon.png" alt="">
									</div>
									<ul class="tool-info">
										<li><span class="style">Regular</span>-<span>This is a text Message</span></li>
										<li><span class="style">Medium</span>-<span>This is a text Message</span></li>
										<li><span class="style">Semibold</span>-<span>This is a text Message</span></li>
										<li><span class="style">Bold</span>-<span>This is a text Message</span></li>
									</ul>
								</div>
							</div> -->

						</div>
						<!-- <div class="block-elements">
							<ul class="pagination">
								<li class="prev">Prev
									<a href="portfolio-details.html">
										<i class="fa-solid fa-arrow-right-long"></i></a>

								</li>
								<li class="next active">Next <a href="portfolio-details-page-2.html">
										<i class="fa-solid fa-arrow-right-long"></i></a>
								</li>
							</ul>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- project details end -->


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
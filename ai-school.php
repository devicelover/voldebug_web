<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="AI Powered School programs helping students learn AI, cybersecurity, robotics, and communication skills for the future.">
	<meta name="keywords" content="AI school, AI education, cybersecurity training, robotics, future ready students, educational technology, Voldebug">
	<meta name="robots" content="index, follow">
	<title>AI Powered School | Future Ready Skills</title>
	<!-- favicon -->
	<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
	<!-- bootstrap css -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/kursor.css">
	<link rel="stylesheet" href="assets/css/slick-theme.css">
	<link rel="stylesheet" href="assets/css/slick.css">
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<link rel="stylesheet" href="assets/css/nice-select.css">
	<link rel="stylesheet" href="assets/css/fontawesome.min.css">
	<link rel="stylesheet" href="assets/css/normalize.css">
	<link rel="stylesheet" href="assets/css/modal-video.min.css">
	<link rel="stylesheet" href="assets/css/sal.css">
	<link rel="stylesheet" href="assets/css/swiper.min.css">
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<link rel="stylesheet" href="style.css">
	<style>
		.ai-school-hero {
			background: linear-gradient(135deg, #0d0c1c 0%, #1a1a2e 50%, #16213e 100%);
			position: relative;
			overflow: hidden;
			padding: 60px 0 80px;
		}
		.ai-school-hero::before {
			content: '';
			position: absolute;
			top: 0;
			right: 0;
			width: 50%;
			height: 100%;
			background: radial-gradient(circle at 70% 30%, rgba(104, 242, 160, 0.08) 0%, transparent 50%);
			pointer-events: none;
		}
		.ai-school-hero .hero-content h1 {
			font-size: clamp(1.5rem, 5vw, 3.5rem);
			font-weight: 700;
			line-height: 1.25;
			margin-bottom: 1rem;
		}
		.ai-school-hero .hero-content p {
			font-size: 1rem;
			opacity: 0.9;
			max-width: 600px;
			margin-bottom: 1.5rem;
		}
		.ai-school-hero .hero-content .main-btn {
			display: inline-block;
			min-height: 48px;
			padding: 12px 24px;
			line-height: 1.4;
		}
		.ai-school-card {
			background: rgba(26, 26, 46, 0.6);
			border: 1px solid rgba(104, 242, 160, 0.15);
			border-radius: 12px;
			padding: 1.5rem;
			height: 100%;
			transition: all 0.3s ease;
		}
		.ai-school-card:hover {
			transform: translateY(-5px);
			border-color: rgba(104, 242, 160, 0.4);
			box-shadow: 0 10px 40px rgba(104, 242, 160, 0.1);
		}
		.ai-school-card .card-icon {
			width: 48px;
			height: 48px;
			background: linear-gradient(135deg, rgba(104, 242, 160, 0.2), rgba(104, 242, 160, 0.05));
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 1rem;
			font-size: 1.25rem;
			color: #68f2a0;
		}
		.ai-school-card h4 {
			font-size: 1.125rem;
			margin-bottom: 0.5rem;
			line-height: 1.3;
		}
		.ai-school-card p {
			font-size: 0.9rem;
			opacity: 0.85;
			line-height: 1.6;
			margin: 0;
		}
		.ai-school-section-title {
			text-align: center;
			margin-bottom: 2rem;
		}
		.ai-school-section-title h6 {
			color: #68f2a0;
			font-size: 0.8rem;
			text-transform: uppercase;
			letter-spacing: 2px;
			margin-bottom: 0.5rem;
		}
		.ai-school-section-title h3 {
			font-size: clamp(1.5rem, 3vw, 2.5rem);
			line-height: 1.3;
		}
		.ai-school-gallery-wrapper {
			overflow: hidden;
			margin: 0 -15px;
			padding: 1rem 0;
		}
		.ai-school-gallery {
			display: flex;
			gap: 1rem;
			width: max-content;
			animation: ai-school-marquee 40s linear infinite;
		}
		.ai-school-gallery:hover {
			animation-play-state: paused;
		}
		@keyframes ai-school-marquee {
			0% { transform: translateX(0); }
			100% { transform: translateX(-50%); }
		}
		.ai-school-gallery-track {
			display: flex;
			gap: 1rem;
		}
		.ai-school-gallery-item {
			flex-shrink: 0;
			width: 280px;
			aspect-ratio: 4/3;
			border-radius: 12px;
			overflow: hidden;
			position: relative;
			transition: transform 0.3s ease;
			cursor: pointer;
		}
		.ai-school-gallery-item:hover {
			transform: scale(1.03);
			box-shadow: 0 8px 25px rgba(104, 242, 160, 0.2);
		}
		.ai-school-gallery-item img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
		.ai-school-gallery-item .caption {
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			padding: 0.75rem 1rem;
			background: linear-gradient(transparent, rgba(0,0,0,0.85));
			color: #fff;
			font-size: 0.85rem;
			font-weight: 500;
		}
		.ai-school-gallery-item .click-hint {
			position: absolute;
			top: 0.5rem;
			right: 0.5rem;
			background: rgba(0,0,0,0.5);
			color: #fff;
			padding: 0.25rem 0.5rem;
			border-radius: 6px;
			font-size: 0.7rem;
			opacity: 0;
			transition: opacity 0.3s;
		}
		.ai-school-gallery-item:hover .click-hint {
			opacity: 1;
		}
		.ai-school-lightbox .modal-dialog {
			max-width: 90vw;
			max-height: 90vh;
		}
		.ai-school-lightbox .modal-body {
			padding: 0;
			text-align: center;
			background: #0d0c1c;
		}
		.ai-school-lightbox .modal-body img {
			max-width: 100%;
			max-height: 85vh;
			object-fit: contain;
		}
		.ai-school-lightbox .modal-body .lightbox-caption {
			padding: 1rem;
			color: #fff;
			font-size: 1rem;
		}
		.ai-school-cta {
			background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
			border-radius: 16px;
			padding: 2.5rem 1.5rem;
			text-align: center;
		}
		.ai-school-cta h3 {
			font-size: clamp(1.25rem, 3vw, 2rem);
			line-height: 1.4;
		}
		.ai-school-cta p {
			font-size: 0.95rem;
		}
		.ai-school-cta .main-btn {
			min-height: 48px;
			padding: 12px 20px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
		}
		.ai-school-cta .d-flex {
			flex-direction: column;
			align-items: stretch;
			gap: 0.75rem;
		}
		.ai-school-cta .d-flex .main-btn {
			width: 100%;
		}
		/* Better touch targets on mobile */
		.ai-school-hero .main-btn,
		.ai-school-cta .main-btn {
			-webkit-tap-highlight-color: rgba(104, 242, 160, 0.2);
			touch-action: manipulation;
		}
		/* Tablet and up */
		@media (min-width: 576px) {
			.ai-school-hero { padding: 80px 0 100px; }
			.ai-school-hero .hero-content h1 { margin-bottom: 1.25rem; }
			.ai-school-hero .hero-content p { font-size: 1.0625rem; margin-bottom: 1.75rem; }
			.ai-school-card { padding: 1.75rem; }
			.ai-school-card .card-icon { width: 52px; height: 52px; font-size: 1.35rem; }
			.ai-school-section-title { margin-bottom: 2.5rem; }
			.ai-school-gallery-item { width: 300px; }
			.ai-school-cta { padding: 3rem 2rem; }
			.ai-school-cta .d-flex {
				flex-direction: row;
				align-items: center;
				justify-content: center;
			}
			.ai-school-cta .d-flex .main-btn { width: auto; }
		}
		@media (min-width: 768px) {
			.ai-school-hero { padding: 100px 0 120px; }
			.ai-school-hero .hero-content p { font-size: 1.125rem; margin-bottom: 2rem; }
			.ai-school-card { padding: 2rem; }
			.ai-school-card .card-icon { width: 56px; height: 56px; font-size: 1.5rem; }
			.ai-school-card h4 { font-size: 1.25rem; }
			.ai-school-card p { font-size: 0.95rem; }
			.ai-school-section-title { margin-bottom: 3rem; }
			.ai-school-gallery-item .caption { font-size: 0.9rem; padding: 1rem; }
			.ai-school-gallery-item { width: 320px; }
			.ai-school-cta { padding: 4rem 2rem; }
		}
		@media (min-width: 992px) {
			.ai-school-gallery-item { width: 350px; }
		}
		/* Prevent horizontal overflow on small screens */
		.ai-school-hero .container {
			max-width: 100%;
			overflow-x: hidden;
		}
		/* Mobile: reduce section padding */
		.ai-school-section {
			padding-top: 3rem !important;
			padding-bottom: 3rem !important;
		}
		@media (min-width: 576px) {
			.ai-school-section {
				padding-top: 4rem !important;
				padding-bottom: 4rem !important;
			}
		}
		@media (min-width: 768px) {
			.ai-school-section {
				padding-top: 5rem !important;
				padding-bottom: 5rem !important;
			}
		}
		@media (min-width: 992px) {
			.ai-school-section {
				padding-top: 6.25rem !important;
				padding-bottom: 6.25rem !important;
			}
		}
	</style>
</head>
<body class="page-template">
	<div id="preloader" class="inso-preloader">
		<span class="loader"></span>
	</div>
	<?php include_once "offcanvase_menu.php"; ?>
	<?php include_once "header.php"; ?>

	<!-- Hero Section -->
	<section class="ai-school-hero">
		<div class="container position-relative">
			<div class="row align-items-center">
				<div class="col-lg-7">
					<div class="hero-content">
						<h1>AI Powered School – Building Future Ready Students</h1>
						<p>We help schools equip students with essential AI, cybersecurity, technology, and communication skills required for the modern digital world.</p>
						<a href="contact.php" class="main-btn rounded-btn">Partner With Us <span><i class="fa-sharp fa-light fa-arrow-right-long"></i></span></a>
					</div>
				</div>
				<div class="col-lg-5 d-none d-lg-block text-center">
					<div class="hero-illustration">
						<i class="fa-solid fa-robot" style="font-size: 12rem; opacity: 0.15;"></i>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Courses Section -->
	<section class="ai-school-section pt-100 pb-100">
		<div class="container">
			<div class="ai-school-section-title">
				<h6>Curriculum</h6>
				<h3>What Students Will <strong>Learn</strong></h3>
			</div>
			<div class="row g-4">
				<div class="col-lg-4 col-md-6">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
						<h4>AI Tools</h4>
						<p>Learn tools like ChatGPT, Midjourney, Canva AI and other productivity AI tools.</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
						<h4>Cybersecurity</h4>
						<p>Basics of digital safety, ethical hacking, and online protection.</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-laptop-code"></i></div>
						<h4>Technical Skills</h4>
						<p>Google Workspace, Excel, and productivity tools.</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-comments"></i></div>
						<h4>Communication Skills</h4>
						<p>Public speaking, presentations, and confidence building.</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-briefcase"></i></div>
						<h4>Corporate Etiquette</h4>
						<p>Professional behavior and workplace soft skills.</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-microchip"></i></div>
						<h4>Robotics Basics</h4>
						<p>Introduction to robotics, automation, and future technologies.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Benefits Section -->
	<section class="ai-school-section pt-70 pb-100" style="background: rgba(26, 26, 46, 0.3);">
		<div class="container">
			<div class="ai-school-section-title">
				<h6>Partnership</h6>
				<h3>Benefits for <strong>Partner Schools</strong></h3>
			</div>
			<div class="row g-4">
				<div class="col-lg-6 col-xl-3">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-bullhorn"></i></div>
						<h4>Branding & Leads</h4>
						<p>Schools get visibility and access to new student leads.</p>
					</div>
				</div>
				<div class="col-lg-6 col-xl-3">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-book-open"></i></div>
						<h4>Extra Curriculum</h4>
						<p>Schools can offer AI and technology programs beyond standard academics.</p>
					</div>
				</div>
				<div class="col-lg-6 col-xl-3">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-trophy"></i></div>
						<h4>AI Competition Representation</h4>
						<p>Students can represent the school in AI competitions and tech events.</p>
					</div>
				</div>
				<div class="col-lg-6 col-xl-3">
					<div class="ai-school-card">
						<div class="card-icon"><i class="fa-solid fa-graduation-cap"></i></div>
						<h4>Future Ready Students</h4>
						<p>Students develop skills required for modern careers.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Events Gallery -->
	<section class="ai-school-section pt-100 pb-100">
		<div class="container">
			<div class="ai-school-section-title">
				<h6>Gallery</h6>
				<h3>Previous Workshops & <strong>Events</strong></h3>
				<p class="text-muted mt-2" style="font-size: 0.9rem;">Click any image to view larger</p>
			</div>
			<div class="ai-school-gallery-wrapper">
				<div class="ai-school-gallery">
					<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
					$gallery_sql = "SELECT * FROM ai_school_gallery ORDER BY sort_order ASC, id DESC";
					$gallery_result = @$con->query($gallery_sql);
					$gallery_items = array();
					if ($gallery_result && mysqli_num_rows($gallery_result) > 0):
						while ($gallery_row = mysqli_fetch_assoc($gallery_result)):
							$gallery_items[] = $gallery_row;
						endwhile;
					else:
						$gallery_items = array(
							array('image' => '', 'title' => 'AI Workshop Session'),
							array('image' => '', 'title' => 'Robotics Demo Day'),
							array('image' => '', 'title' => 'Cybersecurity Training'),
						);
					endif;
					foreach (array_merge($gallery_items, $gallery_items) as $gallery_row):
						$img_src = !empty($gallery_row['image']) 
							? 'Admin/images/ai_school_gallery/' . htmlspecialchars($gallery_row['image']) 
							: 'https://placehold.co/600x400/1a1a2e/68f2a0?text=' . urlencode($gallery_row['title'] ?? 'Event');
						$img_title = htmlspecialchars($gallery_row['title'] ?? 'Event');
					?>
					<div class="ai-school-gallery-item" data-img="<?php echo htmlspecialchars($img_src); ?>" data-title="<?php echo $img_title; ?>" role="button" tabindex="0">
						<img src="<?php echo $img_src; ?>" alt="<?php echo $img_title; ?>" loading="lazy" width="600" height="400">
						<span class="caption"><?php echo $img_title; ?></span>
						<span class="click-hint"><i class="fa-solid fa-expand"></i> Click to enlarge</span>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Lightbox Modal -->
	<div class="modal fade ai-school-lightbox" id="aiSchoolLightbox" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content" style="background: transparent; border: none;">
				<div class="modal-header border-0" style="position: absolute; top: 0; right: 0; z-index: 10;">
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1; font-size: 1.5rem;"></button>
				</div>
				<div class="modal-body p-0">
					<img id="lightboxImg" src="" alt="">
					<div class="lightbox-caption" id="lightboxCaption"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- CTA Section -->
	<section class="ai-school-section pt-70 pb-100">
		<div class="container">
			<div class="ai-school-cta">
				<h3 class="mb-4">Partner With Us to Build the Future of Education</h3>
				<p class="mb-4 opacity-75">Join hands with us to equip the next generation with essential skills for the digital age.</p>
				<div class="d-flex flex-wrap justify-content-center gap-3">
					<a href="contact.php" class="main-btn rounded-btn">Partner With Us <span><i class="fa-sharp fa-light fa-arrow-right-long"></i></span></a>
					<a href="contact.php" class="main-btn rounded-btn" style="background: transparent; border: 2px solid #68f2a0;">Enroll Now <span><i class="fa-sharp fa-light fa-arrow-right-long"></i></span></a>
				</div>
			</div>
		</div>
	</section>

	<?php include_once "footer.php"; ?>

	<div class="scroll active-scroll">
		<svg class="scroll__circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
			<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 244.073px;"></path>
		</svg>
	</div>

	<script src="assets/js/Jquery-3.7.0.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.meanmenu.min.js"></script>
	<script src="assets/js/main.js"></script>
	<script>
		$(function() {
			var lightboxEl = document.getElementById('aiSchoolLightbox');
			var lightboxModal = lightboxEl && typeof bootstrap !== 'undefined' ? new bootstrap.Modal(lightboxEl) : null;

			$('.ai-school-gallery-item').on('click keypress', function(e) {
				if (e.type === 'keypress' && e.which !== 13) return;
				var img = $(this).data('img');
				var title = $(this).data('title');
				$('#lightboxImg').attr('src', img).attr('alt', title);
				$('#lightboxCaption').text(title);
				if (lightboxModal) lightboxModal.show();
				else $('#aiSchoolLightbox').modal('show');
			});

			$('#aiSchoolLightbox').on('click', '.btn-close, [data-bs-dismiss="modal"]', function(e) {
				e.preventDefault();
				if (lightboxModal) lightboxModal.hide();
				else $('#aiSchoolLightbox').modal('hide');
			});
		});
	</script>
</body>
</html>

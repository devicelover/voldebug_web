<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
include 'config.php';
require_once __DIR__ . '/includes/captcha.php';
$position_name = '';
$job_id = 0;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $job_id = (int) $_GET['id'];
    $stmt = $con->prepare("SELECT job_name FROM career WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job_result = $stmt->get_result();
    if ($job_result && $job_row = $job_result->fetch_assoc()) {
        $position_name = htmlspecialchars($job_row['job_name']);
    }

    // Click tracking: record a "view" for this job. Skipped for known crawler UAs.
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (!preg_match('/(bot|crawl|spider|slurp|bing)/i', $ua)) {
        $ip     = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ipBin  = @inet_pton($ip) ?: '';
        $ref    = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 500);
        $kind   = 'view';
        $t = $con->prepare("INSERT INTO career_clicks (job_id, kind, ip, referrer) VALUES (?, ?, ?, ?)");
        $t->bind_param('isss', $job_id, $kind, $ipBin, $ref);
        @$t->execute();
    }
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
	<title>Voldebug - Apply for Career</title>
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
	
	<?php
		include_once "offcanvase_menu.php";
	?>

	<!-- header menu -->
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
					<h5 class="title">Career</h5>
					<p>Apply for a role at Voldebug. We reply to every application within 5 working days.</p>
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
<div class="centered-div">
	<div class="career pt-10 pb-10">
		<div class="container">
			<div class="apply">
				<div id="apply__title">Apply Here</div>
			</div>
			<div class="row">
					<div class="col-lg-1 col-xl-1"></div>
					<div class="col-lg-10 col-xl-10">
						<div class="career__sidebar">
							<div class="career__application">
								<form action="code.php" method="post" enctype="multipart/form-data">
									<input type="hidden" name="applied_job_id" value="<?php echo (int) $job_id; ?>">
									<div class="form-group">
										<input type="text" name="name" class="form-control" placeholder="Name*" id="name" required>
									</div>
									
									<div class="form-group">
										<input type="email" name="email" class="form-control" placeholder="Email*" id="email" required>
									</div>

									<div class="form-group">
										<input type="text" name="phone" class="form-control" placeholder="Your Phone Number*" id="post" required>
									</div>
									
									<div class="form-group">
										<input type="text" name="Position" class="form-control" placeholder="Enter Position You Want*" id="position" value="<?php echo $position_name; ?>" required>
									</div>

									<div class="form-group">
										<textarea name="description" class="form-control border-white" placeholder="Short Description about Your Self*" id="msg" required> </textarea>
									</div>

									<div class="form-group">
										<label class="mb-1">Upload Resume* (Only Pdf)</label>
										<input type="file" name="pdf" accept=".pdf,application/pdf" class="form-control" required>
									</div>
									<div class="form-group">
										<!-- Honeypot -->
                                    <div style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden" aria-hidden="true">
                                        <label>Website (leave blank)</label>
                                        <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
                                    </div>
                                    <?php echo captcha_field(); ?>
                                    <button name="client_cureer" type="submit" class="btn apply-btn">Apply Now</button>
									</div>
								</form>
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
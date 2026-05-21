<?php
$sql = "SELECT * FROM settings ";
$settings = $con->query($sql);
$row = mysqli_fetch_assoc($settings);
?>
<footer class="footer pt-100 pb-70">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 col-sm-6">
					<div class="footer__widget">
						<h5>Contact Us</h5>
						<div class="footer__widget--contact">
							<ul>
								<li><i class="fa-sharp fa-solid fa-phone"></i><a
										href="tel:+91<?php echo $row["phone"] ?>">+91 <?php echo $row["phone"] ?></a></li>
								<li><i class="fa-regular fa-envelope"></i><a
										href="mailto:<?php echo $row["email"] ?>"><?php echo $row["email"] ?></a></li>
							</ul>
							
							<div class="footer__social">
								<a href="#"><i class="fa-brands fa-facebook-f"></i></a>
								<a href="#"><i class="fa-brands fa-twitter"></i></a>
								<a href="#"><i class="fa-brands fa-instagram"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-sm-6">
					<div class="footer__widget">
						<h5>Links</h5>
						<div class="footer__widget--link">
							<ul>
								<li><a href="about.php">About Us</a></li>
								<li><a href="service.php">Service</a></li>
								<li><a href="project.php">Project</a></li>
								<li><a href="career.php">Careers</a></li>
								<li><a href="ai-school.php">AI School</a></li>
								<li><a href="blog.php">Blog</a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="col-lg-4 col-md-6 col-sm-6">
					<div class="footer__widget">
						<h5>Services</h5>
						<div class="footer__widget--link">
							<ul style="list-style:none;padding:0"><li><a href="web-development.php">Web Development</a></li><li><a href="mobile-apps.php">Mobile Apps</a></li><li><a href="cybersecurity-vadodara.php">Cybersecurity (Vadodara)</a></li><li><a href="cctv-vadodara.php">CCTV Installation (Vadodara)</a></li><li><a href="ai-school.php">AI School</a></li><li><a href="get-a-quote.php">Request a quote</a></li></ul>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!-- footer credit -->
	<div class="footer-credit">
		<div class="container">
			<div class="footer-credit--img">
				<a href="index.php"><img src="assets/img/logo/2.png" alt="footer logo"></a>
			</div>
			<div class="footer-credit__wrapper">
				<div class="copy-right">
					All Rights Reserved <a href="#">Voldebug</a>
				</div>
				<div class="footer-links">
					<ul>
						<li><a href="faq.php">Terms of use</a></li>
						<li><a href="faq.php">Privacy Policy</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- footer credit end -->
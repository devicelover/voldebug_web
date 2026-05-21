<!-- offcanvase menu -->
<?php include 'config.php'; ?>
<?php
		$sql = "SELECT * FROM settings ";
		$settings = $con->query($sql);
		$row = mysqli_fetch_assoc($settings);
	?>
<div class="o-hidden">
		<div class="offcanvase">
			<div class="offcanvase__menu">
				<div class="offcanvase__menu--content">
					<div class="offcanvase__menu--top mb-30 d-flex justify-content-between">
						<div class="offcanvase__menu--logo">
							<div class="offcanvase__logo">
								<a href="index.php">
									<img src="assets/img/logo/2.png" alt="Voldebug">
								</a>
							</div>
						</div>
						<div class="offcanvase__menu--close-icon me-2">
							<div class="close-menu pointer"><i class="fa-sharp fa-regular fa-xmark"></i></div>
						</div>
					</div>
					<div class="offcanvase-menu o-hidden mb-30"></div>
					<div class="offcanvase__button mb-30">
						<a class="login" href="contact.php">Contact Us</a>
					</div>
					<div class="offcanvase__menu--contact center">
						<h4 class="offcanvase__menu--contact-title mb-20">Contact Details</h4>
						<div class="offcanvase__menu--contact-text">
							<ul>
								<li><a href="tel:+<?php echo $row["phone"] ?>">+<?php echo $row["phone"] ?></a></li>
								<li><a href="mailto:<?php echo $row["email"] ?>"></a><?php echo $row["email"] ?></li>
							</ul>
							<p><?php echo $row["address"] ?></p>
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
					<!-- <div class="thanks-giving mt-5">
						<img src="assets/img/thanks.jpg" alt="thank you">
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<div class="offcanvas__overlay"></div>
	<!-- offcanvase menu end -->
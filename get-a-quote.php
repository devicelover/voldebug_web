<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Voldebug | Query</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets\css\main.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body>
<?php include 'header.php'; ?>

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="page-header d-flex align-items-center" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container position-relative">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-6 text-center">
              <h2>Get a Quote</h2>
            </div>
          </div>
        </div>
      </div>
      <nav>
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Get a Quote</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Get a Quote Section ======= -->
    <section id="get-a-quote" class="get-a-quote">
      <div class="container" data-aos="fade-up">

        <div class="row g-0">

          <div class="col-lg-5 quote-bg" style="background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);"></div>

          <div class="col-lg-7">
            <?php if (isset($_GET['sent']) && $_GET['sent'] == '1'): ?>
              <div class="alert alert-success mb-4">Your quote request has been sent successfully. Thank you!</div>
            <?php endif; ?>
            <form action="forms/quote.php" method="post" class="php-email-form">
              <h3>Get a quote</h3>
              <p>Got Any Query Feel Free To Contact Us</p>
              <div class="row gy-4">
                <div class="col-lg-12">
                  <h4>Your Personal Details</h4>
                </div>

                <div class="col-md-12">
                  <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>

                <div class="col-md-12 ">
                  <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="phone" placeholder="Phone" required>
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your quote request has been sent successfully. Thank you!</div>

                  <button type="submit">Get a quote</button>
                </div>

              </div>
            </form>
          </div><!-- End Quote Form -->

        </div>

      </div>
    </section><!-- End Get a Quote Section -->

  </main><!-- End #main -->

 
     <!-- ======= Contact Section ======= -->
     <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div>
           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3692.304654858523!2d73.21957987501992!3d22.266446544122203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fc5af3d0233a5%3A0xaa0d4ec89ca8df7d!2sSiddharth%20Business%20Hub!5e0!3m2!1sen!2sin!4v1711955972064!5m2!1sen!2sin" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
        </div><!-- End Google Maps -->

        <div class="row gy-4 mt-4">

          <div class="col-lg-4">

            <div class="info-item d-flex">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h4>Location:</h4>
                <p>T/F-342, Siddharth Magnum Plus, Besides, Susen - Tarsali Ring Rd, Danteshwar, Vadodara, Gujarat 390009</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h4>Email:</h4>
                <p> <a href="mailto:contactus@voldebug.in">contactus@voldebug.in</a></p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex">
              <i class="bi bi-phone flex-shrink-0"></i>
              <div>
                <h4>Call:</h4>
                <p><a href="tel:9499699693">+91 9499699693</a></p>
              </div>
            </div><!-- End Info Item -->

          </div>
          

          <div class="col-lg-8">
            <form action="send_email.php" method="post" class="php-email-form">
    <div class="row gy-4">
        <div class="col-lg-12">
            <h4>Your Personal Details</h4>
        </div>

        <div class="col-md-12">
            <input type="text" name="name" class="form-control" placeholder="Name" required>
        </div>

        <div class="col-md-12 ">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>

        <div class="col-md-12">
            <input type="text" class="form-control" name="phone" placeholder="Phone" required>
        </div>

        <div class="col-md-12">
            <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit">Send Message</button>
        </div>
    </div>
</form>

          </div><!-- End Contact Form -->
  </main><!-- End #main -->

  <?php include 'footer.php'; ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
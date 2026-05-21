<!-- Public-site responsive nav fixes (loaded with the header on every page) -->
<style>
    /* ============ Mobile / Tablet nav (≤ 991px) ============ */
    @media (max-width: 991.98px) {
        .header__wrapper { display: flex !important; align-items: center; justify-content: space-between; gap: 8px; padding: 8px 0; }
        .header__logo img { max-height: 44px; width: auto; }
        .header__menu { display: none !important; }              /* hide desktop nav */
        .header__right { display: flex; align-items: center; gap: 6px; }
        .header__right--btn .login {
            font-size: 13px; padding: 6px 12px; white-space: nowrap;
        }
        .menu-icon {
            display: inline-flex !important; align-items: center; justify-content: center;
            width: 44px; height: 44px; border: 1px solid #1a8f4a; background: #fff;
            border-radius: 8px; color: #1a8f4a; font-size: 20px; line-height: 1;
            cursor: pointer; transition: background .15s;
        }
        .menu-icon:hover, .menu-icon:focus { background: #1a8f4a; color: #fff; outline: none; }
    }
    /* ============ Phones (≤ 575px) ============ */
    @media (max-width: 575.98px) {
        .header__logo img { max-height: 38px; }
        .header__right--btn .login { font-size: 12px; padding: 5px 10px; }
        .menu-icon { width: 40px; height: 40px; font-size: 18px; }
    }
    /* ============ Very small (≤ 380px) — hide the Contact pill so logo + hamburger fit ============ */
    @media (max-width: 380px) {
        .header__right--btn { display: none; }
    }
    /* ============ Desktop (≥ 992px) — make sure hamburger stays hidden ============ */
    @media (min-width: 992px) {
        .menu-icon { display: none !important; }
    }

    /* ============ Off-canvas drawer width on tiny screens ============ */
    @media (max-width: 575.98px) {
        .offcanvase { width: 92vw !important; max-width: 360px; }
    }

    /* Mean-menu styling for the in-drawer mobile menu */
    .offcanvase-menu .mean-nav ul {
        list-style: none; padding: 0; margin: 0; background: #f5f7fb; border-radius: 8px;
    }
    .offcanvase-menu .mean-nav ul li { border-bottom: 1px solid #e8ecf3; }
    .offcanvase-menu .mean-nav ul li:last-child { border-bottom: none; }
    .offcanvase-menu .mean-nav ul li a {
        display: block; padding: 12px 16px; color: #222; font-weight: 500; text-decoration: none;
    }
    .offcanvase-menu .mean-nav ul li a:hover, .offcanvase-menu .mean-nav ul li a:focus {
        background: #1a8f4a; color: #fff;
    }
</style>

<header class="header">
    <div class="container">
        <div class="row">
            <div class="header__wrapper">
                <div class="header__logo">
                    <a href="index.php"><img src="assets/img/logo/2.png" alt="Voldebug Logo"></a>
                </div>
                <div class="header__menu">
                    <nav id="offcanvase__menu">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="service.php">Services</a></li>
                            <li><a href="project.php">Projects</a></li>
                            <li><a href="career.php">Careers</a></li>
                            <li><a href="ai-school.php">AI School</a></li>
                            <li><a href="blog.php">Blog</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="header__right">
                    <div class="header__right--btn">
                        <a class="login" href="contact.php">Contact</a>
                    </div>
                    <button class="menu-icon d-lg-none" type="button" aria-label="Open navigation menu">
                        <i class="fa-sharp fa-solid fa-bars" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

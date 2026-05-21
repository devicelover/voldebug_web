<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box text-center">
                <a href="index.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="https://voldebug.in/assets/img/Voldebug.png" alt="logo-sm-dark" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="https://voldebug.in/assets/img/Voldebug.png" alt="logo-dark" height="24">
                    </span>
                </a>

                <a href="index.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="https://voldebug.in/assets/img/Voldebug.png" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="https://voldebug.in/assets/img/Voldebug.png" alt="logo-light" height="24">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-bs-toggle="collapse"
                data-bs-target="#topnav-menu-content">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="ri-search-line"></span>
                </div>
            </form>

        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="mb-3 m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img class="" src="assets/images/flags/us.jpg" alt="Header Language" height="16">
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="assets/images/flags/spain.jpg" alt="user-image" class="me-1" height="12"> <span
                            class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="assets/images/flags/germany.jpg" alt="user-image" class="me-1" height="12"> <span
                            class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="assets/images/flags/italy.jpg" alt="user-image" class="me-1" height="12"> <span
                            class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="assets/images/flags/russia.jpg" alt="user-image" class="me-1" height="12"> <span
                            class="align-middle">Russian</span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="ri-apps-2-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end border">
                    <div class="px-lg-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/github.png" alt="Github">
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/bitbucket.png" alt="bitbucket">
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/dribbble.png" alt="dribbble">
                                    <span>Dribbble</span>
                                </a>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/dropbox.png" alt="dropbox">
                                    <span>Dropbox</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/mail_chimp.png" alt="mail_chimp">
                                    <span>Mail Chimp</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/slack.png" alt="slack">
                                    <span>Slack</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    <span class="noti-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small"> View All</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="ri-shopping-cart-line"></i>
                                        </span>
                                    </div>
                                </div>                                
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Your order is placed</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-3.jpg" class="rounded-circle avatar-xs" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">James Lemire</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">It will seem like simplified English.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title bg-success rounded-circle font-size-16">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Your item is shipped</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-4.jpg" class="rounded-circle avatar-xs" alt="user-pic">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Salena Layfield</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top">
                        <div class="d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-2.jpg"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">Kevin</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My Wallet</a>
                    <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i
                            class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock
                        screen</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#"><i
                            class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="mdi mdi-cog"></i>
                </button>
            </div>

        </div>
    </div>
</header>

<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="mdi mdi-home-variant-outline me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button">
                            <i class="mdi mdi-briefcase-variant-outline me-2"></i>UI Elements <div class="arrow-down"></div>
                        </a>

                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                            aria-labelledby="topnav-uielement">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-alerts.php" class="dropdown-item">Alerts</a>
                                        <a href="ui-badge.php" class="dropdown-item">Badge</a>
                                        <a href="ui-breadcrumb.php" class="dropdown-item">Breadcrumb</a>
                                        <a href="ui-buttons.php" class="dropdown-item">Buttons</a>
                                        <a href="ui-cards.php" class="dropdown-item">Cards</a>
                                        <a href="ui-carousel.php" class="dropdown-item">Carousel</a>
                                        <a href="ui-dropdowns.php" class="dropdown-item">Dropdowns</a>
                                        <a href="ui-grid.php" class="dropdown-item">Grid</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-images.php" class="dropdown-item">Images</a>
                                        <a href="ui-lightbox.php" class="dropdown-item">Lightbox</a>
                                        <a href="ui-modals.php" class="dropdown-item">Modals</a>
                                        <a href="ui-offcanvas.php" class="dropdown-item">Offcanvas</a>
                                        <a href="ui-rangeslider.php" class="dropdown-item">Range Slider</a>
                                        <a href="ui-session-timeout.php" class="dropdown-item">Session Timeout</a>
                                        <a href="ui-pagination.php" class="dropdown-item">Pagination</a>
                                        <a href="ui-placeholders.php" class="dropdown-item">Placeholders</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-progressbars.php" class="dropdown-item">Progress Bars</a>
                                        <a href="ui-sweet-alert.php" class="dropdown-item">Sweetalert 2</a>
                                        <a href="ui-tabs-accordions.php" class="dropdown-item">Tabs & Accordions</a>
                                        <a href="ui-typography.php" class="dropdown-item">Typography</a>
                                        <a href="ui-toasts.php" class="dropdown-item">Toasts</a>
                                        <a href="ui-video.php" class="dropdown-item">Video</a>
                                        <a href="ui-popover-tooltips.php" class="dropdown-item">Popovers &amp; Tooltips</a>
                                        <a href="ui-rating.php" class="dropdown-item">Rating</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button">
                            <i class="ri-apps-2-line me-2"></i>Apps <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">
                            <a href="calendar.php" class="dropdown-item">Calendar</a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                    role="button">
                                    Email <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a href="email-inbox.php" class="dropdown-item">Inbox</a>
                                    <a href="email-read.php" class="dropdown-item">Read Email</a>
                                    <a href="email-compose.php" class="dropdown-item">Email Compose</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i class="ri-stack-line me-2"></i>Components <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                    role="button">
                                    Forms <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                    <a href="form-elements.php" class="dropdown-item">Elements</a>
                                    <a href="form-validation.php" class="dropdown-item">Validation</a>
                                    <a href="form-advanced.php" class="dropdown-item">Advanced Plugins</a>
                                    <a href="form-editors.php" class="dropdown-item">Editors</a>
                                    <a href="form-uploads.php" class="dropdown-item">File Upload</a>
                                    <a href="form-xeditable.php" class="dropdown-item">Xeditable</a>
                                    <a href="form-wizard.php" class="dropdown-item">Wizard</a>
                                    <a href="form-mask.php" class="dropdown-item">Mask</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-table"
                                    role="button">
                                    Tables <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-table">
                                    <a href="tables-basic.php" class="dropdown-item">Basic Tables</a>
                                    <a href="tables-datatable.php" class="dropdown-item">Data Tables</a>
                                    <a href="tables-responsive.php" class="dropdown-item">Responsive Table</a>
                                    <a href="tables-editable.php" class="dropdown-item">Editable Table</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-charts"
                                    role="button">
                                    Charts <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <a href="charts-apex.php" class="dropdown-item">Apex charts</a>
                                    <a href="charts-chartjs.php" class="dropdown-item">Chartjs</a>
                                    <a href="charts-flot.php" class="dropdown-item">Flot Chart</a>
                                    <a href="charts-knob.php" class="dropdown-item">Jquery Knob Chart</a>
                                    <a href="charts-sparkline.php" class="dropdown-item">Sparkline Chart</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-icons"
                                    role="button">
                                    Icons <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                    <a href="icons-remix.php" class="dropdown-item">Remix Icons</a>
                                    <a href="icons-materialdesign.php" class="dropdown-item">Material Design</a>
                                    <a href="icons-dripicons.php" class="dropdown-item">Dripicons</a>
                                    <a href="icons-fontawesome.php" class="dropdown-item">Font Awesome</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-map"
                                    role="button">
                                    Maps <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-map">
                                    <a href="maps-google.php" class="dropdown-item">Google Maps</a>
                                    <a href="maps-vector.php" class="dropdown-item">Vector Maps</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i class="ri-file-copy-2-line me-2"></i>Pages <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                    role="button">
                                    Authentication <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="auth-login.php" class="dropdown-item">Login</a>
                                    <a href="auth-register.php" class="dropdown-item">Register</a>
                                    <a href="auth-recoverpw.php" class="dropdown-item">Recover Password</a>
                                    <a href="auth-lock-screen.php" class="dropdown-item">Lock Screen</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility"
                                    role="button">
                                    Utility <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                    <a href="pages-starter.php" class="dropdown-item">Starter Page</a>
                                    <a href="pages-maintenance.php" class="dropdown-item">Maintenance</a>
                                    <a href="pages-comingsoon.php" class="dropdown-item">Coming Soon</a>
                                    <a href="pages-timeline.php" class="dropdown-item">Timeline</a>
                                    <a href="pages-faqs.php" class="dropdown-item">FAQs</a>
                                    <a href="pages-pricing.php" class="dropdown-item">Pricing</a>
                                    <a href="pages-404.php" class="dropdown-item">Error 404</a>
                                    <a href="pages-500.php" class="dropdown-item">Error 500</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button">
                            <i class="ri-layout-3-line me-2"></i><span>Layouts</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-layout">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-verti" role="button">
                                    <span>Vertical</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-verti">
                                    <a href="layouts-light-sidebar.php" class="dropdown-item">Light Sidebar</a>
                                    <a href="layouts-compact-sidebar.php" class="dropdown-item">Compact Sidebar</a>
                                    <a href="layouts-icon-sidebar.php" class="dropdown-item">Icon Sidebar</a>
                                    <a href="layouts-boxed.php" class="dropdown-item">Boxed Width</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-hori" role="button">
                                    <span>Horizontal</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="layouts-horizontal.php" class="dropdown-item">Default</a>
                                    <a href="layouts-hori-topbar-dark.php" class="dropdown-item">Topbar Dark</a>
                                    <a href="layouts-hori-boxed-width.php" class="dropdown-item">Boxed Width</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
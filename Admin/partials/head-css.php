<!-- Bootstrap Css -->
<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

<!-- Mobile sidebar fix — drawer behaviour under 992px -->
<style>
    @media (max-width: 991.98px) {
        body.sidebar-enable .vertical-menu { transform: translateX(0); }
        .vertical-menu {
            position: fixed; left: 0; top: 0; bottom: 0; width: 240px;
            z-index: 1051; transform: translateX(-100%);
            transition: transform .25s ease; background: #fff;
            box-shadow: 0 0 18px rgba(0,0,0,.15);
        }
        .main-content { margin-left: 0 !important; }
        body.sidebar-enable::after {
            content: ''; position: fixed; inset: 0; background: rgba(0,0,0,.45);
            z-index: 1050;
        }
        #page-topbar .navbar-brand-box img { max-height: 36px; }
        .navbar-brand-box { padding: 4px 8px !important; }
        .page-content { padding-left: 8px !important; padding-right: 8px !important; }
        .table-responsive { font-size: 13px; }
    }
</style>
<script>
    // Toggle sidebar on hamburger click + close on backdrop click.
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('vertical-menu-btn');
        if (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-enable');
            });
        }
        // Click on backdrop closes the drawer.
        document.addEventListener('click', function (e) {
            if (!document.body.classList.contains('sidebar-enable')) return;
            if (e.target.closest('.vertical-menu') || e.target.closest('#vertical-menu-btn')) return;
            document.body.classList.remove('sidebar-enable');
        });
        // Close drawer when user taps a sidebar link.
        document.querySelectorAll('.vertical-menu a').forEach(function (a) {
            a.addEventListener('click', function () {
                if (window.innerWidth < 992) document.body.classList.remove('sidebar-enable');
            });
        });
    });
</script>
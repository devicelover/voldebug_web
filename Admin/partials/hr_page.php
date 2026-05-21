<?php
// Shared HR-page header/footer helpers for new admin pages.
//
// Usage:
//   <?php $PAGE_TITLE = 'Something'; require __DIR__ . '/partials/hr_page.php';
//         hr_page_open(); ?>
//   ...your page body...
//   <?php hr_page_close(); ?>

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/main.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../authentication.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/bootstrap.php';

function hr_page_open(): void {
    global $PAGE_TITLE;
    $title = $PAGE_TITLE ?? 'Admin';
    includeFileWithVariables('partials/title-meta.php', ['title' => $title]);
    echo '<head>';
    include 'partials/head-css.php';
    echo '</head>';
    include 'partials/body.php';
    echo '<div id="layout-wrapper">';
    include 'partials/menu.php';
    echo '<div class="main-content"><div class="page-content"><div class="container-fluid">';
    includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Voldebug', 'title' => $title]);
}

function hr_page_close(): void {
    echo '</div></div></div>';
    include 'partials/footer.php';
    echo '</div>';
    include 'partials/right-sidebar.php';
    include 'partials/vendor-scripts.php';
    echo '</body></html>';
}

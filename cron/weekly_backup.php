<?php
/**
 * Weekly backup cron.
 *
 * Bundles applicant resumes + issued letter PDFs into a zip and emails it to
 * the HR mailbox. The archive is then kept locally for 8 weeks and older
 * backups are deleted.
 *
 * Schedule on Hostinger (weekly, Sunday 02:00):
 *     /usr/bin/php /home/USER/public_html/cron/weekly_backup.php
 */

require __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$root        = VOLDEBUG_ROOT;
$backupDir   = $root . '/backups';
@mkdir($backupDir, 0755, true);

$stamp    = date('Y-m-d');
$zipPath  = $backupDir . "/voldebug_files_{$stamp}.zip";

$targets = [
    $root . '/Admin/letters'               => 'letters',
    $root . '/Admin/images/client_resume_pdfs' => 'resumes',
    $root . '/Admin/images/letterhead'     => 'letterhead',
];

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    fwrite(STDERR, "Could not create $zipPath\n"); exit(1);
}

foreach ($targets as $dir => $archiveRoot) {
    if (!is_dir($dir)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->isFile() && $f->getFilename() !== '.htaccess') {
            $zip->addFile($f->getPathname(), $archiveRoot . '/' . $f->getFilename());
        }
    }
}

// Also dump the 6 HR tables as CSV snapshots, so the DB state is recoverable even without mysqldump.
$tables = ['client_career', 'interns', 'letter_templates', 'letters_issued', 'email_log', 'settings'];
foreach ($tables as $t) {
    $r = $con->query("SELECT * FROM `{$t}`");
    if (!$r) continue;
    $csv = fopen('php://temp', 'w+');
    $first = true;
    while ($row = $r->fetch_assoc()) {
        if ($first) { fputcsv($csv, array_keys($row)); $first = false; }
        fputcsv($csv, array_map(fn($v) => is_null($v) ? '' : $v, $row));
    }
    rewind($csv);
    $zip->addFromString("db/{$t}.csv", stream_get_contents($csv));
    fclose($csv);
}

$zip->close();
$sizeMb = round(filesize($zipPath) / 1024 / 1024, 2);
echo "Backup: {$zipPath} ({$sizeMb} MB)\n";

// Email to HR. PHPMailer caps SMTP attachments informally at ~25MB; we skip the attach if too large.
$hrEmail = $APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in';
$company = $APP_SETTINGS['name']     ?? 'Voldebug';
$mailer  = new Mailer($con, $APP_SECRETS['smtp']);

$attach = [];
if ($sizeMb < 20) {
    $attach[] = ['path' => $zipPath, 'name' => basename($zipPath)];
    $body = "<p>Weekly backup for <strong>{$company}</strong> ({$stamp}). {$sizeMb} MB.</p>";
} else {
    $body = "<p>Weekly backup for <strong>{$company}</strong> ({$stamp}) is <strong>{$sizeMb} MB</strong> — too large to email. Archive is stored on the server at <code>{$zipPath}</code>. Please download via SFTP / hPanel File Manager.</p>";
}

$mailer->send($hrEmail, "{$company} HR", "[Backup] Weekly archive {$stamp}", $body, $attach, ['type' => 'backup', 'id' => null]);

// Prune local backups older than 8 weeks.
$cutoff = strtotime('-8 weeks');
foreach (glob($backupDir . '/voldebug_files_*.zip') as $old) {
    if (filemtime($old) < $cutoff) {
        @unlink($old);
        echo "pruned: " . basename($old) . "\n";
    }
}
echo "done.\n";

<?php
// Shared safe-upload helper for admin image uploads (logos, signatures, stamps).
// - Whitelists raster formats only (SVG is intentionally excluded).
// - Sniffs MIME with finfo AND verifies the file is actually an image via getimagesize().
// - Caps size at 4 MB.
// - Deletes any prior file on successful replacement.

if (!function_exists('safe_image_upload')) {
    /**
     * @return array{ok:bool, name:string, error?:string}
     *   On success: ['ok' => true, 'name' => '<new-filename>'].
     *   On no-file (field absent / empty upload): ['ok' => true, 'name' => $existing].
     *   On failure: ['ok' => false, 'name' => $existing, 'error' => '<why>'].
     */
    function safe_image_upload(string $field, string $targetDir, string $existing = '', int $maxBytes = 4194304): array {
        if (empty($_FILES[$field]['name'])) {
            return ['ok' => true, 'name' => $existing];
        }
        $err = (int)($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($err === UPLOAD_ERR_NO_FILE) {
            return ['ok' => true, 'name' => $existing];
        }
        if ($err !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'name' => $existing, 'error' => 'Upload failed (code ' . $err . ').'];
        }

        $tmp = (string) ($_FILES[$field]['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return ['ok' => false, 'name' => $existing, 'error' => 'Invalid upload.'];
        }
        $size = (int) ($_FILES[$field]['size'] ?? 0);
        if ($size <= 0 || $size > $maxBytes) {
            return ['ok' => false, 'name' => $existing, 'error' => 'File too large (max ' . round($maxBytes / 1048576, 1) . ' MB).'];
        }

        // Extension whitelist — SVG deliberately excluded (parser attack surface + no sanitizer here).
        $allowExt = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
        $ext = strtolower(pathinfo((string)$_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!isset($allowExt[$ext])) {
            return ['ok' => false, 'name' => $existing, 'error' => 'Only JPG / PNG / GIF / WebP allowed.'];
        }

        // MIME sniff.
        $mime = '';
        if (function_exists('finfo_open')) {
            $fi = finfo_open(FILEINFO_MIME_TYPE);
            if ($fi) { $mime = (string) finfo_file($fi, $tmp); finfo_close($fi); }
        }
        if ($mime === '' && function_exists('mime_content_type')) {
            $mime = (string) mime_content_type($tmp);
        }
        $expectedMime = $allowExt[$ext];
        // JPEG/JPG interchange
        if ($mime === 'image/jpg') $mime = 'image/jpeg';
        if ($mime !== '' && $mime !== $expectedMime) {
            return ['ok' => false, 'name' => $existing, 'error' => 'File content does not match its extension.'];
        }

        // Real-image sanity check.
        $info = @getimagesize($tmp);
        if (!$info || empty($info[0]) || empty($info[1])) {
            return ['ok' => false, 'name' => $existing, 'error' => 'File is not a valid image.'];
        }

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }
        $newName = $field . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
        $dest = rtrim($targetDir, '\\/') . DIRECTORY_SEPARATOR . $newName;
        if (!move_uploaded_file($tmp, $dest)) {
            return ['ok' => false, 'name' => $existing, 'error' => 'Could not save uploaded file.'];
        }
        if ($existing !== '') {
            $oldPath = rtrim($targetDir, '\\/') . DIRECTORY_SEPARATOR . $existing;
            if (is_file($oldPath)) @unlink($oldPath);
        }
        return ['ok' => true, 'name' => $newName];
    }
}

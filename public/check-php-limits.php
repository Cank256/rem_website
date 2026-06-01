<?php
/**
 * Temporary PHP Configuration Checker
 * Upload this to your shared hosting to verify PHP upload limits
 * DELETE THIS FILE after checking!
 */

// Security: Only allow access from specific IPs (optional - uncomment and add your IP)
// $allowed_ips = ['YOUR.IP.ADDRESS.HERE'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Upload Limits Check</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .setting {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            margin: 8px 0;
            background: #f9f9f9;
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
        }
        .setting.warning {
            border-left-color: #ff9800;
            background: #fff3e0;
        }
        .setting.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            font-family: 'Courier New', monospace;
            color: #333;
        }
        .warning-text {
            color: #ff9800;
            margin-top: 20px;
            padding: 15px;
            background: #fff3e0;
            border-radius: 4px;
            border-left: 4px solid #ff9800;
        }
        .success-text {
            color: #4CAF50;
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
        }
        .delete-warning {
            color: #f44336;
            margin-top: 30px;
            padding: 15px;
            background: #ffebee;
            border-radius: 4px;
            border: 2px solid #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 PHP Upload Configuration</h1>
        
        <?php
        $upload_max = ini_get('upload_max_filesize');
        $post_max = ini_get('post_max_size');
        $memory = ini_get('memory_limit');
        $max_execution = ini_get('max_execution_time');
        $max_input_time = ini_get('max_input_time');
        $max_file_uploads = ini_get('max_file_uploads');
        
        // Convert to bytes for comparison
        function convertToBytes($value) {
            $value = trim($value);
            $last = strtolower($value[strlen($value)-1]);
            $value = (int) $value;
            switch($last) {
                case 'g': $value *= 1024;
                case 'm': $value *= 1024;
                case 'k': $value *= 1024;
            }
            return $value;
        }
        
        $upload_bytes = convertToBytes($upload_max);
        $post_bytes = convertToBytes($post_max);
        $target_upload = 20 * 1024 * 1024; // 20MB
        $target_post = 25 * 1024 * 1024; // 25MB
        
        $upload_ok = $upload_bytes >= $target_upload;
        $post_ok = $post_bytes >= $target_post;
        $all_ok = $upload_ok && $post_ok;
        ?>
        
        <div class="setting <?php echo $upload_ok ? '' : 'error'; ?>">
            <span class="label">upload_max_filesize</span>
            <span class="value"><?php echo $upload_max; ?> <?php echo $upload_ok ? '✓' : '✗ (need 20M)'; ?></span>
        </div>
        
        <div class="setting <?php echo $post_ok ? '' : 'error'; ?>">
            <span class="label">post_max_size</span>
            <span class="value"><?php echo $post_max; ?> <?php echo $post_ok ? '✓' : '✗ (need 25M)'; ?></span>
        </div>
        
        <div class="setting">
            <span class="label">memory_limit</span>
            <span class="value"><?php echo $memory; ?></span>
        </div>
        
        <div class="setting">
            <span class="label">max_execution_time</span>
            <span class="value"><?php echo $max_execution; ?> seconds</span>
        </div>
        
        <div class="setting">
            <span class="label">max_input_time</span>
            <span class="value"><?php echo $max_input_time; ?> seconds</span>
        </div>
        
        <div class="setting">
            <span class="label">max_file_uploads</span>
            <span class="value"><?php echo $max_file_uploads; ?></span>
        </div>
        
        <div class="setting">
            <span class="label">PHP Version</span>
            <span class="value"><?php echo PHP_VERSION; ?></span>
        </div>
        
        <div class="setting">
            <span class="label">Server API</span>
            <span class="value"><?php echo php_sapi_name(); ?></span>
        </div>
        
        <?php if ($all_ok): ?>
            <div class="success-text">
                ✅ <strong>Configuration looks good!</strong> Your server can handle uploads up to <?php echo $upload_max; ?>.
            </div>
        <?php else: ?>
            <div class="warning-text">
                ⚠️ <strong>Upload limits are too low.</strong> Contact your hosting provider to increase these limits, or use their control panel (cPanel, Plesk, etc.) to adjust PHP settings.
            </div>
        <?php endif; ?>
        
        <div class="delete-warning">
            🔒 <strong>SECURITY WARNING:</strong> Delete this file (check-php-limits.php) after checking your configuration!
        </div>
    </div>
</body>
</html>

<?php
/**
 * Quick upload limits checker
 * DELETE AFTER USE!
 */

header('Content-Type: application/json');

echo json_encode([
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'upload_max_bytes' => ini_get('upload_max_filesize') . ' (' . (int)ini_get('upload_max_filesize') * 1024 * 1024 . ' bytes)',
    'post_max_size' => ini_get('post_max_size'),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'max_input_time' => ini_get('max_input_time'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'php_version' => PHP_VERSION,
    'sapi' => php_sapi_name(),
    'user_ini_filename' => ini_get('user_ini.filename'),
    'user_ini_cache_ttl' => ini_get('user_ini.cache_ttl'),
], JSON_PRETTY_PRINT);

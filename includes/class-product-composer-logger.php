<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

class Logger
{

    protected $log_file;

    public function __construct()
    {
        $upload_dir = wp_upload_dir();
        $dir = trailingslashit($upload_dir['basedir']) . 'wc-product-composer-logs/';

        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }

        $this->log_file = $dir . 'composer.log';
    }

    public function log($message)
    {
        $date = date('Y-m-d H:i:s');
        $formatted = "[{$date}] " . $message . PHP_EOL;

        file_put_contents($this->log_file, $formatted, FILE_APPEND);
    }
}

<?php

namespace WC_Product_Composer;

use WC_Logger;
use WC_Log_Levels;

if (!defined('ABSPATH')) {
    exit;
}

class Logger
{

    /**
     * Singleton instance.
     *
     * @var Logger
     */
    private static $instance = null;

    /**
     * WooCommerce logger instance.
     *
     * @var WC_Logger
     */
    private $wc_logger;

    /**
     * Log source.
     *
     * @var string
     */
    private $source = 'wc-product-composer';

    /**
     * Private constructor.
     */
    private function __construct()
    {
        $this->wc_logger = wc_get_logger();
    }

    /**
     * Get the singleton instance.
     *
     * @return Logger
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Log an info message.
     *
     * @param string $message
     */
    public function info($message)
    {
        $this->log($message, WC_Log_Levels::INFO);
    }

    /**
     * Log a warning message.
     *
     * @param string $message
     */
    public function warning($message)
    {
        $this->log($message, WC_Log_Levels::WARNING);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     */
    public function error($message)
    {
        $this->log($message, WC_Log_Levels::ERROR);
    }

    /**
     * Generic log method.
     *
     * @param string $message
     * @param string $level
     */
    public function log($message, $level = WC_Log_Levels::INFO)
    {
        $this->wc_logger->log($level, $message, ['source' => $this->source]);
    }
}

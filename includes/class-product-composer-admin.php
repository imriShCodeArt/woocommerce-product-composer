<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

class Admin
{

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_association_metabox']);
        add_action('save_post', [$this, 'save_association_meta']);
    }

    public function add_association_metabox()
    {
        // TODO: Add meta box UI
    }

    public function save_association_meta($post_id)
    {
        // TODO: Save associated products
    }
}

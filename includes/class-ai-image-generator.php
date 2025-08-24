<?php
/**
 * Core logic for WooCommerce AI Image Generator.
 *
 * @package WC_AI_Image_Generator
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class WC_AI_Image_Generator {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
    }

    public function register_menu() {
        add_menu_page(
            'AI Image Generator',
            'AI Image Generator',
            'manage_options',
            'wc-ai-image-generator',
            [ $this, 'plugin_page' ],
            'dashicons-art',
            58
        );
    }

    public function plugin_page() {
        if ( isset($_POST['generate_images']) && check_admin_referer('wc_ai_generate_images') ) {
            $this->generate_images();
        }

        echo '<div class="wrap"><h1>WooCommerce AI Image Generator</h1>';
        echo '<p>This tool scans for WooCommerce products without a featured image and tries to generate one using an AI image service.</p>';
        echo '<form method="post">';
        wp_nonce_field('wc_ai_generate_images');
        submit_button( __( 'Generate Images Now', 'wc-ai-image-generator' ) );
        echo '</form></div>';
    }

    private function generate_images() {
        if ( ! class_exists( 'WP_Query' ) ) {
            require_once ABSPATH . WPINC . '/class-wp-query.php';
        }

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'     => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ];
        $query = new WP_Query($args);

        $count = 0;

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product_id = get_the_ID();
                $title = get_the_title($product_id);

                $image_path = $this->fetch_ai_image($title);

                if ($image_path) {
                    $attached = $this->attach_image_to_product($image_path, $product_id);
                    if ( $attached ) {
                        $count++;
                    }
                }
            }
            wp_reset_postdata();
        }

        printf('<div class="notice notice-success"><p>%d product(s) updated with AI images.</p></div>', $count);
    }

    private function fetch_ai_image($title) {
        // Pollinations API Example (no key needed). You may swap to any other provider.
        // IMPORTANT: Avoid storing secrets in code. Use wp-config or environment variables if needed.
        $url = "https://image.pollinations.ai/prompt/" . rawurlencode($title);

        $response = wp_remote_get($url, ['timeout' => 60]);

        if (is_wp_error($response)) return false;

        $code = wp_remote_retrieve_response_code($response);
        if ($code !== 200) return false;

        $body = wp_remote_retrieve_body($response);
        if (!$body) return false;

        // Save image to uploads directory
        $upload_dir = wp_upload_dir();
        if ( ! empty( $upload_dir['error'] ) ) return false;

        $sanitized = sanitize_title( $title );
        if ( empty($sanitized) ) $sanitized = 'wc-ai-image';

        $file_path = trailingslashit($upload_dir['path']) . $sanitized . '-' . wp_generate_uuid4() . '.jpg';

        // Ensure directory exists
        if ( ! file_exists( $upload_dir['path'] ) ) {
            wp_mkdir_p( $upload_dir['path'] );
        }

        $written = file_put_contents($file_path, $body);
        if ( $written === false ) return false;

        return $file_path;
    }

    private function attach_image_to_product($file_path, $product_id) {
        $filetype = wp_check_filetype( basename( $file_path ), null );
        if ( empty($filetype['type']) ) {
            $filetype['type'] = 'image/jpeg';
        }

        $attachment = [
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_file_name( basename( $file_path ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $attach_id = wp_insert_attachment( $attachment, $file_path, $product_id );

        if ( is_wp_error($attach_id) || ! $attach_id ) return false;

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        set_post_thumbnail( $product_id, $attach_id );
        return true;
    }
}

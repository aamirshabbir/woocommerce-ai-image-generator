=== WooCommerce AI Image Generator ===
Contributors: amirshabbir
Tags: woocommerce, ai, product images, pollinations, automation
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically generate AI-based product images for WooCommerce products that don't have images.

== Description ==
This plugin scans your WooCommerce catalog for products that do not have a featured image and attempts to generate one using an AI image service (Pollinations by default).

== Installation ==
1. Upload the `woocommerce-ai-image-generator` folder to `/wp-content/plugins/`.
2. Activate the plugin via **Plugins → Installed Plugins**.
3. Open **AI Image Generator** from the left WP Admin menu and click **Generate Images Now**.

== Frequently Asked Questions ==
= Does it cost money? =
The default endpoint (Pollinations) is free at the time of writing. You can swap the endpoint in `class-ai-image-generator.php` to other services as needed.

= Where are images stored? =
Images are saved in the WordPress uploads directory for the current month and linked as featured images to each product.

== Changelog ==
= 1.0.0 =
* Initial public release.

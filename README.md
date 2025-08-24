# WooCommerce AI Image Generator

Automatically generates product images for WooCommerce products that are missing images — powered by an AI image service (Pollinations by default).

![Banner](assets/banner.png)

## Features
- Scan WooCommerce products without a featured image
- Generate and attach AI images automatically
- One-click action from the WordPress Admin
- No API key required for the default provider
- Extensible: swap to any image provider

## Demo
> Open **WP Admin → AI Image Generator → Generate Images Now**

## Installation
1. Upload the `woocommerce-ai-image-generator` folder to `/wp-content/plugins/`
2. Activate the plugin under **Plugins → Installed Plugins**
3. Go to **AI Image Generator** in the admin menu and click **Generate Images Now**

## Configuration
The default provider is Pollinations (no key). To change providers, edit:
```
/includes/class-ai-image-generator.php
```
and replace the `fetch_ai_image()` implementation.

## Development
- PHP 7.4+ recommended
- WordPress 6.x
- WooCommerce 7.x/8.x

### Folder Structure
```
woocommerce-ai-image-generator/
├── woocommerce-ai-image-generator.php
├── readme.txt
├── README.md
├── LICENSE
├── .gitignore
├── assets/
│   └── banner.png
└── includes/
    └── class-ai-image-generator.php
```

## Screenshots
1. Admin page: Generate images
2. Updated product with AI image

## Contributing
PRs are welcome. Please open an issue before major changes.

## License
GPL-2.0 — see [LICENSE](LICENSE).

---

**Author:** Amir Shabbir

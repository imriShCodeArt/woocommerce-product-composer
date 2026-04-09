🧩 WooCommerce Product Composer
===============================

A modular WooCommerce plugin that enables dynamic product composition—allowing customers to build structured product combinations directly from the product page.

Designed for stores that sell configurable bundles, kits, or dependent products.

* * *

🚀 Overview
-----------

WooCommerce Product Composer extends the default WooCommerce product flow by introducing product associations and a guided composition interface.

It enables:

*   Structured product bundling without relying on native WooCommerce bundle flows
*   Conditional product selection workflows
*   Enhanced cart handling for composed products

* * *

✨ Features
----------

*   Associate products via admin UI (metabox and controls)
*   Custom frontend composer interface on product pages
*   AJAX-powered interactions for a smoother user experience
*   Custom cart logic for composed products
*   Modular architecture with separated admin, frontend, and cart responsibilities
*   Template override support
*   Translation-ready structure (includes `.pot` file)
*   Logging support for debugging and diagnostics

* * *

🧱 Architecture
---------------

The plugin is organized with a clear separation of concerns.

### Core Classes

    includes/
    ├── class-product-composer-admin.php
    ├── class-product-composer-frontend.php
    ├── class-product-composer-cart.php
    ├── class-product-composer-logger.php
    ├── helpers.php

*   `class-product-composer-admin.php` — Admin UI and product associations
*   `class-product-composer-frontend.php` — Frontend rendering and interaction flow
*   `class-product-composer-cart.php` — Cart integration and composed product handling
*   `class-product-composer-logger.php` — Logging and debug utilities
*   `helpers.php` — Shared helper functions

### Assets

    assets/
    ├── css/product-composer.css
    └── js/
        ├── product-composer.js
        └── admin-associations.js

### Templates

    templates/
    ├── product-composer-section.php
    └── admin/

Templates can be overridden to support theme-level customization.

* * *

⚙️ Installation
---------------

1.  Clone or download the repository:

    git clone https://github.com/<your-username>/woocommerce-product-composer.git

2.  Place the plugin in your WordPress plugins directory:

    /wp-content/plugins/

3.  Activate the plugin from the WordPress admin dashboard.
4.  Configure product associations from the relevant product edit screens.

* * *

🖥️ Usage
---------

Once configured, the plugin enhances the product page with a composition interface that allows customers to select related or dependent products before adding them to the cart.

The exact flow depends on how product associations are defined in the admin.

* * *

🎯 Use Cases
------------

*   Build-your-own product kits
*   Accessory or add-on selection flows
*   Composite product ordering
*   Upsell flows with structured product dependencies
*   B2B and operational ordering interfaces

* * *

🛠️ Developer Notes
-------------------

The codebase is structured for maintainability, with distinct responsibilities for admin, frontend, and cart behavior. This makes the plugin easier to extend and debug.

Logging support is included, which is especially useful when diagnosing cart behavior, AJAX issues, or product relationship logic.

* * *

🌍 Internationalization
-----------------------

The plugin is translation-ready and includes a `.pot` file to support localization.

* * *

📄 License
----------

This project should include a dedicated `LICENSE` file if it is intended for public distribution. If you plan to use MIT, GPL, or another license, define it explicitly in the repository.

* * *

🤝 Contributing
---------------

Pull requests are welcome. For major changes, open an issue first to discuss the intended scope and implementation approach.

* * *

🙌 Credits
----------

Built for WooCommerce stores that need more flexible product composition flows than the default purchasing experience provides.

=== WooCommerce Products Carousel ===
Contributors: Murital M. oshiomole
Donate link: https://wpsaul.com/
Tags: woocommerce, carousel, products, slider, splide, ecommerce
Requires at least: 5.6
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight WooCommerce products carousel powered by Splide.js — fully customizable through the WordPress admin panel.

== Description ==

**WooCommerce Products Carousel** lets you display beautiful, responsive product sliders anywhere on your site with a simple shortcode.

Features:
* Display products from one or multiple categories.
* Fully responsive Splide.js carousel.
* Customize Splide options (slides per page, autoplay, arrows, pagination, gap) in admin.
* Works out of the box with WooCommerce.
* Lightweight — no external dependencies.

= Usage =

AUsage example is by adding the shortcode in any page, post, or widget:

[product_image_carousel category="shirts,men" limit="8"]


**Attributes:**
* `category` — Comma-separated category slugs.  
* `limit` — Number of products to show.

= Admin Settings =

Go to **Dashboard → Products Carousel** to configure:
* Products per slide
* Autoplay toggle
* Show/hide navigation arrows
* Show/hide pagination dots
* Gap between slides

All saved settings automatically apply to every carousel on your site.

== Installation ==

1. Upload the `woocommerce-products-carousel` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. Go to **Dashboard → Products Carousel** to adjust carousel settings.
4. Use the `[product_image_carousel]` shortcode wherever you want to display a carousel.

== Screenshots ==

1. Example product carousel on frontend.
2. Admin settings panel for carousel options.

== Frequently Asked Questions ==

= Do I need Splide.js installed separately? =
No. Splide’s JS and CSS are bundled within the plugin.

= Does it work with custom product types? =
Yes — any WooCommerce product that has a featured image will appear.

= Can I use multiple shortcodes with different categories? =
Absolutely. Each shortcode can specify its own category list and product limit.

== Changelog ==

= 1.0.0 =
* Initial release by Murital M. Oshiomole.
* Includes admin settings and frontend Splide integration.

== Upgrade Notice ==

= 1.0.0 =
Initial stable version.

== Credits ==

Developed by **Murital M. Oshiomole**  
Powered by [Splide.js](https://splidejs.com)

== License ==

This plugin is licensed under the GPLv2 or later.
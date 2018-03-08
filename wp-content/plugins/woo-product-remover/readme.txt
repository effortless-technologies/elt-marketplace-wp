=== Plugin Name ===
Contributors: mcfarhat
Donate link: http://example.com/
Tags: woocommerce, products, remove products, delete products, product remover
Requires at least: 4.3
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Woo Product Remover allows you to remove all woocommerce products from your site. It cleans up your database from products and product variations

== Description ==

Woo Product Remover allows you to, via a single click, remove all woocommerce products from your site. It cleans up your database from products, their metadata, relationships, as well as product variations and their related meta data.

It handles removing all standard woocommerce product types including simple, grouped, external and variable, as well as related variations.

Some of this work has been inspired by https://www.kingrosales.com/blog/how-to-properly-delete-all-woocommerce-products/ which has been improved upon and made to work properly within wordpress and to cleanup product variations.

If you would like some custom work done, or have an idea for a plugin you're ready to fund, check our site at www.greateck.com or contact us at info@greateck.com

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woo-product-remover` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Click on the Woo Product Remover, and then click the Delete All button
4. That's it! The plugin will let you know how many products/product variations have been deleted, if any.

== Frequently Asked Questions ==

= How do we delete products? =

Just setup the plugin, go to the Woo Product Remover screen, and click delete all. 
The plugin will confirm how many products/product variations have been deleted

= What if I don't have products, what happens? =

You don't have products, you don't need to use the plugin .. lol .. But if you do, it will inform you that 0 products have been deleted

= Does the plugin remove extra product database fields? =

The plugin cleans up your database from all product meta data that is stored by woocommerce. Deleting the products and leaving those will exhaust your resources, especially if you have a large database, which is why we handle removing them

= What types of products this plugin removes? =

The plugin removes all standard woocommerce product types, including simple, grouped, external and variable, as well as related variations.

= How fast is this plugin? =

Super fast! Since we are relying on mysql query, the plugin runs pretty quickly avoiding any layers and cleaning up all its work.

== Screenshots ==

1. Screenshot for the plugin default page
2. Screenshot for the plugin after removing all products

== Changelog ==

= 1.1.0 =
Adding support to keep categories, tags and taxonomies related to the removed products. These will be kept by default (new checkbox option) to prevent any accidental data loss.

= 1.0.0 =
* Initial Version *

== Upgrade Notice ==

= 1.1.0 =
Adding support to keep categories, tags and taxonomies related to the removed products. These will be kept by default (new checkbox option) to prevent any accidental data loss.
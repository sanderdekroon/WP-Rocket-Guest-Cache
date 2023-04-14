# WP Rocket Guest Cache

This plugin will make WP Rocket serve the guest cache to everyone, even logged-in users. Requires the **Common Cache For Logged-in Users** and **No Cache for Admins** helpers to function properly.

## 📋 TL;DR
> - A helper plugin for WP-Rocket, thus requiring WP-Rocket
> - A plugin that serves cache files generated for 'guests' to logged-in users
> - Requires two helper plugins developed by WP-Rocket (@see [installation](#Installation))
> - ☝️ **Test before use!** This helper is for a very specific use case. Please read the 'Why?' section before using it!

## 🔧 Installation
0. Have WP-Rocket installed and user cache enabled
1. Install and activate the **Common Cache For Logged-in Users** helper from [here](https://github.com/wp-media/wp-rocket-helpers/tree/master/cache/wp-rocket-cache-common-cache-loggedin)
2. Install and activate the **No Cache for Admins** helper from [here](https://github.com/wp-media/wp-rocket-helpers/tree/master/cache/wp-rocket-no-cache-for-admins/)
3. Install and activate this plugin
4. Manually delete the `/wp-content/advanced-cache.php` file from the server and visit the WP-Rocket settings page to generate a new one.

The new `advanced-cache.php` file should contain the `SdkGuestCache` class. If it doesn't, make sure you've followed the installation instructions correctly.

## 🧐 Why?
Some websites allow users to be logged-in, but do not display specific information for logged-in users. Pages that do can be whitelisted in WP-Rocket, like the cart and checkout pages of WooCommerce. This means the cache files generated for 'guests' can be served to logged-in users, as user specific information is not shown on any of those pages.

The [user cache option](https://docs.wp-rocket.me/article/313-user-cache) in WP-Rocket allows generating and serving cache files to logged-in users, but it will create separate cache files for each user. 

The [Common Cache For Logged-in Users helper](https://github.com/wp-media/wp-rocket-helpers/tree/master/cache/wp-rocket-cache-common-cache-loggedin) allows to serve a single user cache to all logged-in users. Unfortunately, this still creates separate caches for guest users and logged-in users. And more importantly: **it does not work with the preload functionality**.

This plugin solves that issue by serving the guest cache to all logged-in users. This means the cache can be preloaded *and* served to logged-in users.

The  [No Cache for Admins helper](https://github.com/wp-media/wp-rocket-helpers/tree/master/cache/wp-rocket-no-cache-for-admins/) is required to make sure no admin bars are shown on cached pages.

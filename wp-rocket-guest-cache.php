<?php

/**
 * Plugin Name: WP Rocket Guest Cache
 * Description: Serve the guest cache to everyone, even loggedin users.
 * Version: 1.0.0
 * Plugin URI: https://sanderdekroon.xyz
 * Author: sanderdekroon
 * Author URI: https://sanderdekroon.xyz
 */

namespace Sanderdekroon\WPRocket;

if (! defined('ABSPATH')) {
    die();
}

class GuestCachePlugin
{
    protected array $requiredPlugins = [
        'wp-rocket/wp-rocket.php',
        'wp-rocket-no-cache-for-admins/wp-rocket-no-cache-for-admins.php',
        'wp-rocket-cache-common-cache-loggedin/wp-rocket-cache-common-cache-loggedin.php',
    ];

    public function boot()
    {
        if ($this->isMissingRequiredPlugin()) {
            add_action('admin_notices', [$this, 'displayWarning']);

            return;
        }

        add_filter('rocket_advanced_cache_file', [$this, 'editAdvancedCache']);
    }

    public function editAdvancedCache(string $buffer): string
    {
        $classInject = '
            class SdkGuestCache extends \WP_Rocket\Buffer\Cache
            {
                protected $sdkConfig;

                public function __construct(
                    \WP_Rocket\Buffer\Tests $tests,
                    \WP_Rocket\Buffer\Config $config,
                    array $args
                ) {
                    parent::__construct($tests, $config, $args);
                    $this->sdkConfig = $config;
                }

                public function get_cache_path($args = [])
                {
                    $path = parent::get_cache_path($args);

                    if (strpos($path, "-loggedin-") === false) {
                        return $path;
                    }

                    return str_replace(
                        "-loggedin-" . $this->sdkConfig->get_config("secret_cache_key"),
                        "",
                        $path
                    );
                }
            }';

        return str_replace(
            '( new Cache(',
            $classInject . "\n\n" . '( new SdkGuestCache(',
            $buffer
        );
    }

    public function displayWarning(): void
    {
        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            __('<strong>WP Rocket Guest Cache:</strong> Some of the required plugins are not installed or active. Please install <em>WP Rocket</em> and the two helpers <em>Common Cache For Logged-in Users</em> and <em>No Cache for Admins</em>.')
        );
    }

    protected function isMissingRequiredPlugin(): bool
    {
        foreach ($this->requiredPlugins as $plugin) {
            if (! is_plugin_active($plugin)) {
                return true;
            }
        }

        return false;
    }
}

// rocket_generate_advanced_cache_file

(new GuestCachePlugin())->boot();

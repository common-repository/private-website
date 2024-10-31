<?php
/**
 * Plugin Name: Private Website - Login Required
 * Description: This plugin is straightforward to use: you only need to activate it. If you decide that you no longer want to enforce the login requirement, simply deactivate the plugin.
 * Version: 0.1
 * Author: Robin Oehler (roehler.nrw)
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: private-website
 */

// Ensure that the plugin is not accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Restrict content to logged-in users.
 * Users must log in to view the website unless they are accessing certain allowed pages
 */
function pwlr_restrict_content_to_logged_in_users() {
    // Ensure the user is not logged in and does not have admin capabilities
    if (!is_user_logged_in() && !current_user_can('manage_options')) {
        // Define allowed pages such as login, registration, and lost password pages
        $allowed_pages = array('wp-login.php', 'wp-register.php', 'wp-lostpassword.php');

        // Check if the request URI is set
        if (isset($_SERVER['REQUEST_URI'])) {
            // Get the current page name from the request URI
            $current_page = basename(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])));

            // If the current page is not in the list of allowed pages, redirect to the login page
            if (!in_array($current_page, $allowed_pages)) {
                // Redirect to the login page and include a return URL to redirect back after login
                $redirect_url = wp_login_url(get_permalink());

                // Fallback to the site URL if get_permalink() fails
                if (!$redirect_url) {
                    $redirect_url = wp_login_url();
                }

                // Perform the redirect
                wp_redirect($redirect_url);
                exit;
            }
        }
    }
}

// Hook the function to run before the template is loaded
add_action('template_redirect', 'pwlr_restrict_content_to_logged_in_users');

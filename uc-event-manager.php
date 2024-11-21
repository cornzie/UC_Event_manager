<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           UC_Event_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       UC Event Manager
 * Plugin URI:        http://udeh.ng/uc-event-manager-uri/
 * Description:       This is a simple event manager that's powered by custom WP post type
 * Version:           1.0.0
 * Author:            Cornelius Udeh
 * Author URI:        http://udeh.ng/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       uc-event-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('UC_EVENT_MANAGER_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-uc-event-manager-activator.php
 */
function activate_uc_event_manager()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-uc-event-manager-activator.php';
    UC_Event_manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-uc-event-manager-deactivator.php
 */
function deactivate_uc_event_manager()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-uc-event-manager-deactivator.php';
    UC_Event_manager_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_uc_event_manager');
register_deactivation_hook(__FILE__, 'deactivate_uc_event_manager');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-uc-event-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_uc_event_manager()
{

    $plugin = new UC_Event_Manager();
    $plugin->run();

}
run_uc_event_manager();

<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://udeh.ng
 * @since      1.0.0
 *
 * @package    UC_Event_Manager
 * @subpackage UC_Event_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    UC_Event_Manager
 * @subpackage UC_Event_Manager/includes
 * @author     Your Name <email@example.com>
 */
class UC_Event_Manager
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      UC_Event_manager_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $uc_event_manager    The string used to uniquely identify this plugin.
     */
    protected $uc_event_manager;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('UC_EVENT_MANAGER_VERSION')) {
            $this->version = UC_EVENT_MANAGER_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->uc_event_manager = 'uc-event-manager';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - UC_Event_manager_Loader. Orchestrates the hooks of the plugin.
     * - UC_Event_manager_i18n. Defines internationalization functionality.
     * - UC_Event_manager_Admin. Defines all hooks for the admin area.
     * - UC_Event_manager_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-uc-event-manager-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-uc-event-manager-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-uc-event-manager-admin.php';
        
        /**
         * The class responsible for managing event specific meta boxes on the admin side
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-uc-event-manager-metaboxes.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-uc-event-manager-public.php';

        $this->loader = new UC_Event_manager_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the UC_Event_manager_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new UC_Event_manager_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new UC_Event_manager_Admin($this->get_uc_event_manager(), $this->get_version());
        $meta_boxes = new UC_Event_Manager_Metaboxes();
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('init', $this, 'register_post_type');
        $this->loader->add_action('add_meta_boxes', $meta_boxes, 'add_event_meta_boxes');
        $this->loader->add_action('save_post', $meta_boxes, 'save_event_meta');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new UC_Event_manager_Public($this->get_uc_event_manager(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');


    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_uc_event_manager()
    {
        return $this->uc_event_manager;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    UC_Event_manager_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Registers the 'Event' custom post type.
     */
    public function register_post_type()
    {
        $labels = [
            'name'               => __('Events', 'uc-event-manager'),
            'singular_name'      => __('Event', 'uc-event-manager'),
            'menu_name'             => __('Events', 'uc-event-manager'),
            'add_new_item'       => __('Add New Event', 'uc-event-manager'),
            'edit_item'          => __('Edit Event', 'uc-event-manager'),
            'new_item'           => __('New Event', 'uc-event-manager'),
            'view_item'          => __('View Event', 'uc-event-manager'),
            'all_items'          => __('All Events', 'uc-event-manager'),
            'update_item'           => __('Update Event', 'uc-event-manager'),
            'view_Event'             => __('View Event', 'uc-event-manager'),
            'view_Events'            => __('View Events', 'uc-event-manager'),
            'search_Events'          => __('Search Event', 'uc-event-manager'),
            'not_found'             => __('No events found', 'uc-event-manager'),
            'not_found_in_trash'    => __('No events found in Trash', 'uc-event-manager'),
            'featured_image'        => __('Event Featured Image', 'uc-event-manager'),
            'set_featured_image'    => __('Set event featured image', 'uc-event-manager'),
            'remove_featured_image' => __('Remove event featured image', 'uc-event-manager'),
            'use_featured_image'    => __('Use as event featured image', 'uc-event-manager'),
            'insert_into_Event'      => __('Insert into Event', 'uc-event-manager'),
        ];

        $args = [
            'labels'                => $labels,
            'public'                => true,
            'has_archive'           => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 25,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'supports'              => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'rewrite'               => ['slug' => 'events'],
            'menu_icon'          => 'dashicons-calendar',
        ];

        register_post_type('event', $args);

    }


}

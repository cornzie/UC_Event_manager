<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    UC_Event_manager
 * @subpackage UC_Event_manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    UC_Event_manager
 * @subpackage UC_Event_manager/admin
 * @author     Cornelius Udeh <cornelius@udeh.ng>
 */
class UC_Event_Manager_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $uc_event_manager    The ID of this plugin.
     */
    private $uc_event_manager;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $uc_event_manager       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($uc_event_manager, $version)
    {

        $this->uc_event_manager = $uc_event_manager;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in UC_Event_manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The UC_Event_manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->uc_event_manager, plugin_dir_url(__FILE__) . 'css/uc-event-manager-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in UC_Event_manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The UC_Event_manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->uc_event_manager, plugin_dir_url(__FILE__) . 'js/uc-event-manager-admin.js', array('jquery'), $this->version, false);

    }

}

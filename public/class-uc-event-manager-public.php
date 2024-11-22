<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://udeh.ng
 * @since      1.0.0
 *
 * @package    UC_Event_Manager
 * @subpackage UC_Event_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    UC_Event_Manager
 * @subpackage UC_Event_Manager/public
 * @author     Your Name <email@example.com>
 */
class UC_Event_manager_Public
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
     * @param      string    $uc_event_manager       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($uc_event_manager, $version)
    {

        $this->uc_event_manager = $uc_event_manager;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->uc_event_manager, plugin_dir_url(__FILE__) . 'css/uc-event-manager-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->uc_event_manager, plugin_dir_url(__FILE__) . 'js/uc-event-manager-public.js', array('jquery'), $this->version, false);

    }

    public function register_shortcodes() {
        add_shortcode('uc_events', [$this, 'render_events']);
    }

    public function render_events($atts = []) {
        // Parse any attributes passed to the shortcode
        $atts = shortcode_atts([
            'number' => 5, // Default: show 5 events
        ], $atts, 'uc_events');
    
        // Query the events
        $query_args = [
            'post_type'      => 'event',
            'posts_per_page' => $atts['number'],
            'meta_key'       => '_uc_event_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
        ];
    
        $events = new WP_Query($query_args);
    
        if (!$events->have_posts()) {
            return '<p>No events found.</p>';
        }
    
        // Build the HTML output
        $output = '<div class="uc-events-list">';
        while ($events->have_posts()) {
            $events->the_post();
            $start_date = get_post_meta(get_the_ID(), '_uc_event_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_uc_event_end_date', true);
            $start_time = get_post_meta(get_the_ID(), '_uc_event_start_time', true);
            $end_time = get_post_meta(get_the_ID(), '_uc_event_end_time', true);
            $location = get_post_meta(get_the_ID(), '_uc_event_location', true);
    
            $output .= '<div class="uc-event">';
            $output .= '<h3>' . esc_html(get_the_title()) . '</h3>';
            $output .= '<p><strong>Start Date:</strong> ' . esc_html($start_date) . '</p>';
            $output .= '<p><strong>End Date:</strong> ' . esc_html($end_date) . '</p>';
            $output .= '<p><strong>Time:</strong> ' . esc_html($start_time) . ' - ' . esc_html($end_time) . '</p>';
            $output .= '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
            $output .= '</div>';
        }
        wp_reset_postdata();
        $output .= '</div>';
    
        return $output;
    }

}

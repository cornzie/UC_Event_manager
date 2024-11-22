<?php
/**
 * Defines and manages meta boxes for the Event custom post type.
 *
 * @package UC_Event_Manager
 * @subpackage UC_Event_manager/admin
 * @author     Cornelius Udeh <cornelius@udeh.ng>
 */

 class UC_Event_Manager_Metaboxes {
    public function add_event_meta_boxes() {
        add_meta_box(
            'uc_event_details',
            __('Event Details', 'uc-event-manager'),
            [$this, 'render_event_meta_boxes'],
            'event',
            'normal',
            'default'
        );
    }

    /**
     * Render the Event's meta fields.
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_event_meta_boxes($post) {
        // Get current meta data values
        $start_date = get_post_meta($post->ID, '_uc_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_uc_event_end_date', true);
        $start_time = get_post_meta($post->ID, '_uc_event_start_time', true);
        $end_time = get_post_meta($post->ID, '_uc_event_end_time', true);
        $location = get_post_meta($post->ID, '_uc_event_location', true);

        wp_nonce_field('uc_event_meta_box', 'uc_event_meta_box_nonce');

        // Retrieve and display errors
        $errors = get_transient('uc_event_errors_' . $post->ID);
        if ($errors) {
            error_log("Retrieved Transient Errors: " . implode(', ', $errors));
            echo '<div class="notice notice-error is-dismissible">';
            foreach ($errors as $error) {
                echo '<p>' . esc_html($error) . '</p>';
            }
            echo '</div>';
            delete_transient('uc_event_errors_' . $post->ID); // Clear the errors after displaying
        }

        // Render meta box fields
        ?>
        <div class="uc_event_meta_boxes_container">
            <div class="uc_event_meta_dates">
                <div>
                    <label for="uc_event_start_date"><?php _e('Start Date', 'uc-event-manager'); ?></label> <br>
                    <input type="date" id="uc_event_start_date" name="_uc_event_start_date" value="<?php echo esc_attr($start_date); ?>">
                </div>
                <div>
                    <label for="uc_event_end_date"><?php _e('End Date', 'uc-event-manager'); ?></label> <br>
                    <input type="date" id="uc_event_end_date" name="_uc_event_end_date" value="<?php echo esc_attr($end_date); ?>">
                </div>
            </div>
            <div class="uc_event_meta_timing">
                <div>
                    <label for="uc_event_start_time"><?php _e('Start Time', 'uc-event-manager'); ?></label> <br>
                    <input type="time" id="uc_event_start_time" name="_uc_event_start_time" value="<?php echo esc_attr($start_time); ?>">
                </div>
                <div>
                    <label for="uc_event_end_time"><?php _e('End Time', 'uc-event-manager'); ?></label> <br>
                    <input type="time" id="uc_event_end_time" name="_uc_event_end_time" value="<?php echo esc_attr($end_time); ?>">
                </div>
            </div>
            <div class="uc_event_meta_location">
                <div>
                    <label for="uc_event_location"><?php _e('Location', 'uc-event-manager'); ?></label> <br>
                    <input type="text" id="uc_event_location" name="_uc_event_location" value="<?php echo esc_attr($location); ?>">
                </div>
            </div>
        </div>
        <?php
    }

    public function save_event_meta($post_id) {

        // Skip if POST data is empty
        if (empty($_POST)) {
            return;
        }

        // Skip autosave or invalid requests
        if (! $this->isValidSaveRequest($post_id, $_POST)) {
            return;
        }

        if (! $this->validated($post_id, $_POST)) {
            return;
        }

        // Save meta fields
        $fields = [
            '_uc_event_start_date',
            '_uc_event_end_date',
            '_uc_event_location',
            '_uc_event_start_time',
            '_uc_event_end_time',
        ];
        
        foreach ($fields as $meta_key) {
            if (isset($_POST[$meta_key])) {
                $value = sanitize_text_field($_POST[$meta_key]);
                $result = update_post_meta($post_id, $meta_key, $value);
            }
        }

    }

    private function isValidSaveRequest(int $post_id, array $post_request) {

        return 
            isset($post_request['uc_event_meta_box_nonce']) &&
            wp_verify_nonce($post_request['uc_event_meta_box_nonce'], 'uc_event_meta_box') &&
            ! (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) &&
            current_user_can('edit_post', $post_id);
    }

    private function validated(int $post_id, array $post_request) {
        $errors = [];
        $start_date = isset($post_request['_uc_event_start_date']) ? sanitize_text_field($post_request['_uc_event_start_date']) : '';
        $end_date = isset($post_request['_uc_event_end_date']) ? sanitize_text_field($post_request['_uc_event_end_date']) : '';
        
        $start_time = isset($post_request['_uc_event_start_time']) ? sanitize_text_field($post_request['_uc_event_start_time']) : '';
        $end_time = isset($post_request['_uc_event_end_time']) ? sanitize_text_field($post_request['_uc_event_end_time']) : '';
        
        // Validate dates
        if ($start_date && strtotime($start_date) < strtotime('today')) {
            $errors[] = "Start date cannot be in the past.";
        }
        if ($end_date && strtotime($end_date) < strtotime('today')) {
            $errors[] = "End date cannot be in the past.";
        }
        if ($start_date && $end_date && strtotime($start_date) > strtotime($end_date)) {
            $errors[] = "Start date must be before the end date.";
        }

        // Validate times
        if ($start_time && $end_time && strtotime($start_time) >= strtotime($end_time)) {
            $errors[] = "Start time must be earlier than end time.";
        }
        
        // Log and set errors
        if (!empty($errors)) {
            error_log("Validation errors: " . implode(', ', $errors)); // Debug log
            set_transient('uc_event_errors_' . $post_id, $errors, 30); // Store errors for display
            return false;
        }

        return true;
    }
 }

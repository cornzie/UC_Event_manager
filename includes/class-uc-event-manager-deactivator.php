<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://udeh.ng
 * @since      1.0.0
 *
 * @package    UC_Event_Manager
 * @subpackage UC_Event_Manager/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    UC_Event_Manager
 * @subpackage UC_Event_Manager/includes
 * @author     Your Name <email@example.com>
 */
class UC_Event_manager_Deactivator
{

    /**
     * Unregister CPT: Events.
     *
     * Unregisters CPT: Events and flushes rewrites.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        // Unregisters cpt events 
        // Flush rewrites
    }

}

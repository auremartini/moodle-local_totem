<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local plugin "Totem: show teacher's attendences and event totem" - Version file
 *
 * @package    local_totem
 * @copyright  2022, Aureliano Martini (Liceo cantonale di Lugano 2) <aureliano.martini@edu.ti.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function local_totem_extend_navigation(global_navigation $navigation) {
    global $CFG, $PAGE;

    if (has_capability('local/totem:config', context_system::instance()) && isloggedin()) {
        // Add room planner link to navbar
        // Initialize node variables.
        $nodeurl = '/local/totem/view.php';
        $nodetitle = get_string('plugin_navbar_lavel', 'local_totem');
        $nodekey = 'totem-page';
        $icon = new pix_icon('Monitor-Vertical-icon', '', 'local_totem');
    
        // Create custom node.
        $customnode = navigation_node::create(
            $nodetitle,
            $nodeurl,
            global_navigation::TYPE_CUSTOM,
            null,
            $nodekey,
            $icon
        );
    
        // Show the custom node in Boost's nav drawer if requested.
        $customnode->showinflatnavigation = true;
    
        // For some crazy reason, if we add the child node directly to the parent node, it is not shown in the
        // course navigation section.
        // Thus, add the custom node to the given navigation_node.
        // This line generate a debug message on home (not logged in) page
        $navigation->add_node($customnode, 'home');
    
        // And change the parent node directly afterwards.
        $customnode->set_parent($navigation);
    }
}

$local_totem_config = null;
function local_totem_load_configuration() {
    global $local_totem_config;
    global $DB;
    
    $local_totem_config = array();
    //Load config
    $rs = $DB->get_records_menu('local_totem', null, 'config', 'config, value');

    foreach ($rs as $key => $element) {
        $local_totem_config['config_'.$key] = json_decode($element);
    }
}
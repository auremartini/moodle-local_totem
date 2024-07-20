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

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/config_edit_form.php');

global $DB, $OUTPUT, $PAGE;
global $local_totem_config;

require_login();

//LOAD PARAMS & OBJECTS
$date = intval(optional_param('d', '', PARAM_TEXT));

// START PAGE
$PAGE->set_context(\context_system::instance());

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/local/totem/view.php'));
$PAGE->set_title(get_string('plugin_page_title', 'local_totem'));
$PAGE->set_heading(get_string('totem:config', 'local_totem'));
$url = new moodle_url('/local/totem/view.php', ['d' => $date]);
$node = $PAGE->settingsnav->add(get_string('plugin_navbar_lavel', 'local_totem'), $url);
$node->make_active();
$editnode = $node->add(get_string('totem:config', 'local_totem'));
$editnode->make_active();

//LOAD TOTEM CONFIGURATION
local_totem_load_configuration();

// SET FORM
$form = new \local_totem\classes\config_edit_form();

//Form processing and displaying is done here
if ($form->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($url);
} else if ($data = $form->get_data()) {
    //In this case you process validated data. $form->get_data() returns data posted in form.  
    foreach ($data as $field => $value) {
        if (strpos($field, 'config_') !== 0) {
            continue;
        }
        $field = substr($field, 7);
        
        if ($DB->record_exists('local_totem', ['config' => $field])) {
            $DB->set_field('local_totem', 'value', json_encode($value), ['config' => $field]);
        } else {
            $DB->insert_record('local_totem', [
                'config' => $field,
                'value' => json_encode($value)
            ]);
        }
    }
    redirect($url);
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    
    //Set default data
    $form->set_data($local_totem_config);
    $form->set_data(['d' => $date]);
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();

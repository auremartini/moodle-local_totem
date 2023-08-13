<?php
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
require_once(__DIR__ . '/classes/event_edit_form.php');

global $DB, $OUTPUT, $PAGE, $USER;
global $local_totem_config;

//REQUIRE LOGIN TO SHOW THE CONTENT
require_login();

//LOAD PARAMS & OBJECTS
$id = optional_param('id', '', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$date = intval(optional_param('d', '', PARAM_TEXT));
$url = optional_param('url', '/local/totem/view.php', PARAM_TEXT);

// START PAGE
$PAGE->set_context(\context_system::instance());

// SET FORM
$form = new \local_totem\classes\event_edit_form();
$form->set_data(['d' => $date, 'date' => $date]);

// HANDLE EVENTS
if ($action == 'delete') {
    if ($id != 0) {
        if (!$DB->delete_records('local_totem_event', array('id' => $id))) {
            print_error('deleteeventerror', 'local_totem');
        }
    }
    $return = new moodle_url($url, ['d' => $date]);
    redirect($return);
} elseif($form->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $return = new moodle_url($url, ['d' => $form->get_submitted_data()->d]);
    redirect($return);
} else if ($record = $form->get_data()) {
    // New record or edited record
    if ($id != 0) {
        $record->editedby = $USER->id;
        $record->editeddate = time();
        if (!$DB->update_record('local_totem_event', $record)) {
            print_error('updateeventerror', 'local_totem');
        }
    } else {
        $record->createdby = $USER->id;
        $record->createddate = time();
        if (!$DB->insert_record('local_totem_event', $record)) {
            print_error('inserteventerror', 'block_totem');
        }
    }
    $return = new moodle_url($url, ['d' => $form->get_submitted_data()->date]);
    redirect($return);
} else if ($action == 'copy') {
    // Load event id data and prepare a new record
    $record = $DB->get_record('local_totem_event', array('id' => $id));
    $id = null;
    $record->id = null;
    $form->set_data($record);
    $pagetitle = get_string('totem:addevent', 'local_totem');
} else if ($action == 'eye') {
    // Update record to show to public
    $record = $DB->get_record('local_totem_event', array('id' => $id));
    $record->displayevent = 1;
    if (!$DB->update_record('local_totem_event', $record)) {
        print_error('updateeventerror', 'block_totem');
    }
    $return = new moodle_url($url, ['d' => $record->date]);
    redirect($return);
} else if ($action == 'eye-slash') {
    // Update record to hide to public
    $record = $DB->get_record('local_totem_event', array('id' => $id));
    $record->displayevent = 0;
    if (!$DB->update_record('local_totem_event', $record)) {
        print_error('updateeventerror', 'block_totem');
    }
    $return = new moodle_url($url, ['d' => $record->date]);
    redirect($return);
} else {
    if ($id) {
        // Load values to edit
        $pagetitle = get_string('totem:editevent', 'local_totem');
        $record = $DB->get_record('local_totem_event', array('id' => $id));
        $form->set_data($record);
        
        // Load creator user data
        $user = $DB->get_record('user', array('id' => $record->createdby));
        if ($user) $form->set_data(['createdbytext' => $user->firstname.' '.$user->lastname.' ['.$user->username.'] ('.date('Y-m-d H:i', $record->createddate).')']);

        // Load creator user data
        $user = $DB->get_record('user', array('id' => $record->editedby));
        if ($user) $form->set_data(['editedbytext' => $user->firstname.' '.$user->lastname.' ['.$user->username.'] ('.date('Y-m-d H:i', $record->editeddate).')']);
    } else {
        // New event
        $pagetitle = get_string('totem:addevent', 'local_totem');
    }
}

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/local/totem/event.php'));
$PAGE->set_title(get_string('plugin_page_title', 'local_totem'));
$PAGE->set_heading($pagetitle);
$url = new moodle_url('/local/totem/view.php', ['d' => $date]);
$node = $PAGE->settingsnav->add(get_string('plugin_navbar_lavel', 'local_totem'), $url);
$node->make_active();
$editnode = $node->add($pagetitle);
$editnode->make_active();

//LOAD TOTEM CONFIGURATION
local_totem_load_configuration();

//SET FORM DEFAULTS
$form->set_data([
    'url'=> $url,
    'blockteachings' => $local_totem_config['config_teachings'],
    'source' => $local_totem_config['config_source'],
    'sourceid' => ($local_totem_config['config_source'] == 0 ? $local_totem_config['config_sourceroleid'] : $local_totem_config['config_sourcecohortid'])
]);

// SET PAGE ELEMENTS (HEADER)
$PAGE->requires->js_call_amd('local_totem/event_edit_form_dynamics', 'init', array([
    'timetable' => $local_totem_config['config_timetable'],
    'eventtypelist' => $local_totem_config['config_eventtypelist'],
    'blockteachings' => $local_totem_config['config_teachings'],
    'source' => $local_totem_config['config_source'],
    'sourceid' => ($local_totem_config['config_source'] == 0 ? $local_totem_config['config_sourceroleid'] : $local_totem_config['config_sourcecohortid'])
]));

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();
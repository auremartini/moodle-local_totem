<?php
/// This file is part of Moodle - http://moodle.org/
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
require_once(__DIR__ . '/classes/totemtable.php');
require_once(__DIR__ . '/output/renderer.php');
//require_once(__DIR__ . '/classes/datepicker_form.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
require_login();

//LOAD PARAMS & OBJECTS
$date = intval(optional_param('d', '', PARAM_TEXT));
$date_to = intval(optional_param('date_to', '', PARAM_TEXT));
$eventtype = optional_param('eventtype', '', PARAM_TEXT);
$teacher = optional_param('teacher', '', PARAM_TEXT);
$teaching = optional_param('teaching', '', PARAM_TEXT);
$classsection = optional_param('classsection', '', PARAM_TEXT);
if ($date_to == 0) {
    $d = new DateTime();
    $d->setTimestamp(($date == 0 ? time() : $date));
    $d->setTime(0,0);
    $date = $d->getTimestamp()- 1*24*60*60*30;
    $date_to = $d->getTimestamp() + 1*24*60*60*31;
}

// START PAGE
$PAGE->set_context(\context_system::instance());

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/local/totem/filter.php'));
$PAGE->set_title(get_string('plugin_page_title', 'local_totem'));
$PAGE->set_heading(get_string('filterevents', 'local_totem'));
$url = new moodle_url('/local/totem/view.php', ['d' => $date]);
$node = $PAGE->settingsnav->add(get_string('plugin_navbar_lavel', 'local_totem'), $url);
$node->make_active();
$editnode = $node->add(get_string('filterevents', 'local_totem'));
$editnode->make_active();

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

// PRINT CONTENT TO PAGE
$context = context_system::instance();
echo $OUTPUT->header();

//FILTER PAGE
$totem = new \local_totem\data\totemtable([
    'url'=>'/local/totem/filter.php',
    'd' => $date,
    'date_to' => $date_to,
    'teacher' => $teacher,
    'teaching' => $teaching,
    'classsection' => $classsection,
    'showHidden' => has_capability('local/totem:editevent', $context),
    'addbtn' => has_capability('local/totem:addevent', $context),
    'showbtn' => has_capability('local/totem:editevent', $context),
    'editbtn' => has_capability('local/totem:editevent', $context),
    'copybtn' => has_capability('local/totem:addevent', $context),
    'delete' => has_capability('local/totem:deleteevent', $context)
]);

echo $PAGE->get_renderer('local_totem')->render_list($totem);

$PAGE->requires->js_call_amd('local_totem/delete_confirm', 'init', array());
echo $OUTPUT->footer();
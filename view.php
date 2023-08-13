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
 
use local_totem\classes\datepicker_form;

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/totemtable.php');
require_once(__DIR__ . '/classes/datepicker_form.php');
require_once(__DIR__ . '/output/renderer.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
//This line is disabled because teachers and students don't have an active user on the platform
//require_login();

//LOAD PARAMS
$date = intval(optional_param('d', '', PARAM_TEXT));

// START PAGE
$PAGE->set_context(\context_system::instance());

// LOAD AND HANDLE TOOLBAR FORM EVENT
$toolbar = new \local_totem\classes\datepicker_form();
if($toolbar->get_data()) $date = $toolbar->get_data()->date_search;
$toolbar->set_data(array('date_search' => $date));

//SET FIRST DATE TO RENDER
$d = new DateTime();
$d->setTimestamp(($date == 0 ? time() : $date));
$d->setTime(0,0);
$date = $d->getTimestamp();

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/local/totem/view.php'));
$PAGE->set_title(get_string('plugin_page_title', 'local_totem'));
$PAGE->set_heading(get_string('plugin_page_title', 'local_totem'));
$url = new moodle_url('/local/totem/view.php', array());
$node = $PAGE->settingsnav->add(get_string('plugin_navbar_lavel', 'local_totem'), $url);
$node->make_active();

//ADD EVENT TABLES
local_totem_load_configuration();
if(!array_key_exists('config_viewdays', $local_totem_config)) {
    redirect(new moodle_url('/local/totem/config.php'));
}

// PRINT CONTENT TO PAGE
$context = context_system::instance();
echo $OUTPUT->header();

// ADD MENU
$menu = array();
if (has_capability('local/totem:config', $context)) {
    $menu[] = array(
        'id' => 'totem_block_dropmenuitem_config',
        'icon' => 'fa-cog',
        'url' => new moodle_url('/local/totem/config.php'),
        'd' => $date,
        'title' => get_string('config', 'local_totem')
    );
}
if (has_capability('local/totem:filterview', $context)) {
    $menu[] = array(
        'id' => 'totem_block_dropmenuitem_addevent',
        'icon' => 'fa-filter',
        'url' => new moodle_url('/local/totem/filter.php'),
        'd' => $date,
        'title' => get_string('filterevents', 'local_totem')
    );
}
if (has_capability('local/totem:fullscreen', $context)) {
    $menu[] = array(
        'id' => 'totem_block_dropmenuitem_fullscreen',
        'icon' => 'fa-window-maximize',
        'url' => new moodle_url('/local/totem/fullscreen.php'),
        'd' => $date,
        'title' => get_string('fullscreen', 'local_totem'),
        'target' => '_blank'
    );
}
if (count($menu) > 0) {
    echo $PAGE->get_renderer('local_totem')->renderGearMenu(array('records' => $menu));
}

// ADD DATEPICKER
$toolbar->display();

$i = 0;
while ($i < intval($local_totem_config['config_viewdays'])) {
    $collapsible = (intval($local_totem_config['config_viewdays']) == 1 ? FALSE : TRUE);
    $collapsed = ($i==0 ? FALSE : TRUE);
    if ($local_totem_config['config_viewskipweekend'] == 0 || intval($d->format('N')) <= 5) {
        // initalise new totem object
        $totem = new \local_totem\data\totemtable([
            'url'=>'/local/totem/view.php',
            'd' => $d->getTimestamp(),
            'collapsible' => $collapsible,
            'collapsed' => $collapsed,
            'showDate' => TRUE,
            'showHidden' => has_capability('local/totem:editevent', $context),
            'addbtn' => has_capability('local/totem:addevent', $context),
            'showbtn' => has_capability('local/totem:editevent', $context),
            'editbtn' => has_capability('local/totem:editevent', $context),
            'copybtn' => has_capability('local/totem:addevent', $context),
            'delete' => has_capability('local/totem:deleteevent', $context)
        ]);
        switch($local_totem_config['config_viewtemplate']) {
            case 1:
//                echo $PAGE->get_renderer('local_totem')->render_compact($totem);
                break;
            default:
                echo $PAGE->get_renderer('local_totem')->render($totem);
        }
        $i++;
    }
    $d->modify('+1 day');
}

$PAGE->requires->js_call_amd('local_totem/delete_confirm', 'init', array());
echo $OUTPUT->footer();

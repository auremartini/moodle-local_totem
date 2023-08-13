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
 * Strings for component 'block_totem', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   block_totem
 * @copyright 2020 Aureliano Martini
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/totemtable.php');
require_once(__DIR__ . '/output/renderer.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
//This line is disabled because teachers and students don't have an active user on the platform
//require_login();

//LOAD PARAMS
$date = intval(optional_param('d', '', PARAM_TEXT));

// START PAGE
$PAGE->set_context(\context_system::instance());

//SET FIRST DATE TO RENDER
$d = new DateTime();
$d->setTimestamp(($date == 0 ? time() : $date));
$d->setTime(0,0);
$date = $d->getTimestamp();

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/local/totem/fullscreen.php'));
$PAGE->set_title(get_string('plugin_page_title', 'local_totem'));
$PAGE->set_heading(get_string('plugin_page_title', 'local_totem'));
$url = new moodle_url('/local/totem/view.php', array());
$node = $PAGE->settingsnav->add(get_string('plugin_navbar_lavel', 'local_totem'), $url);
$node->make_active();
$editnode = $node->add(get_string('fullscreen', 'local_totem'));
$editnode->make_active();

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();

$d = new DateTime();
if ($date) $d->setTimestamp($date);
$d->setTime(0,0);
echo html_writer::start_tag('div', array('data-region' => "totem_fullscreen", 'class' => 'totem-fullscreen'));
echo html_writer::end_tag('div');

$render = $PAGE->get_renderer('local_totem');

local_totem_load_configuration();

$PAGE->requires->js_call_amd('local_totem/add_totemfullscreen_dynamics', 'init', array([
    'logo' => ($PAGE->get_renderer('local_totem')->get_logo_url() ? $PAGE->get_renderer('local_totem')->get_logo_url()->__toString() : ''),
    'date' => $d->getTimestamp(),
    'offset' => 0,
    'limit' => intval($local_totem_config['config_fullscreendays']),
    'skipweekend' => $local_totem_config['config_fullscreenskipweekend'],
    'showHidden' => FALSE,
    'template' => intval($local_totem_config['config_fullscreentemplate'])
]));

echo $OUTPUT->footer();
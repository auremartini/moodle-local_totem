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

$functions = array(
    'local_totem_get_userlist' => array(
        'classname' => 'local_totem\data\userlist',
        'methodname' => 'local_totem_get_userlist',
        'classpath' => 'local/totem/classes/userlist.php',
        'description' => 'Get a user list',
        'type' => 'read',
        'ajax' => true,
//      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'capabilities' => ''
    ),
    'local_totem_get_teachinglist' => array(
        'classname' => 'local_totem\data\teachinglist',
        'methodname' => 'local_totem_get_teachinglist',
        'classpath' => 'local/totem/classes/teachinglist.php',
        'description' => 'Get the teacher list',
        'type' => 'read',
        'ajax' => true,
        //      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'capabilities' => ''
    ),
    'local_totem_get_totemtable' => array(
        'classname' => 'local_totem\data\totemtable',
        'methodname' => 'local_totem_get_totemtable',
        'classpath' => 'local/totem/classes/totemtable.php',
        'description' => 'Get a totemtable recordset',
        'type' => 'read',
        'ajax' => true,
        //      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'capabilities' => ''
    )
);
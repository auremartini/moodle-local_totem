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

//Plugin text
$string['pluginname'] = 'Totem';
$string['plugin_navbar_lavel'] = 'Teacher\'s attendences';
$string['plugin_page_title'] = 'Teacher\'s attendences';

//Administration text
$string['totem:access'] = 'Access the Teacher\'s attendences page';
$string['totem:config'] = 'Edit configuration';
$string['totem:filterview'] = 'Show filter interface';
$string['totem:addevent'] = 'Add Totem event';
$string['totem:editevent'] = 'Edit Totem event';
$string['totem:deleteevent'] = 'Delete Totem event';
$string['totem:fullscreen'] = 'Open Totem in fullscreen window';

// View page 
$string['config'] = 'Configure';
$string['filterevents'] = 'Events filter';
$string['fullscreen'] = 'Fullscreen';
$string['gotodate'] = 'Go to';
$string['addtotemevent'] = 'Add event';
$string['showtotemevent'] = 'Show to public';
$string['hidetotemevent'] = 'Hide to public';
$string['edittotemevent'] = 'Edit event';
$string['copytotemevent'] = 'Copy event';
$string['deletetotemevent'] = 'Delete event';

// Config page
$string['viewtableheader'] = 'Table view settings';
$string['viewfullscreenheader'] = 'Fullscreen settings';
$string['configteachingshow'] = 'Teachers shown as';
$string['configsourcedesc'] = 'Get teachers from';
$string['configteachingdesc'] = 'Teaching groups';
$string['configdisplaytemplate0'] = 'Default';
$string['configfulscreentemplate0'] = 'Default';
$string['configfulscreentemplate1'] = 'LiLu3';
$string['configdisplaydaysdesc'] = 'Days to show in view page';
$string['configfullscreendaysdesc'] = 'Days to show in fullscreen page';
$string['configskipweekend'] = 'Hide week-end days';
$string['configviewdisplay'] = 'Display template';
$string['configfullscreen'] = 'Fullscreen template';
$string['configeventtypelist'] = 'Event type list';
$string['configeventtypelistdefault'] = 'A|Absent teacher|background-color:red;&#13;R|Reported class|background-color:yellow;&#13;F|Free time|background-color:green;';
$string['configtimetable'] = 'Timetable';
$string['configtimetabledefault'] = '1|08:15|09:00&#13;2|09:05|09:50&#13;3|10:05|10:50&#13;4|10:55|11:40&#13;5|11:45|12:30&#13;6|12:30|13:15&#13;7|13:20|14:05&#13;8|14:10|14:55&#13;9|15:05|15:50&#13;10|15:55|16:40&#13;11|16:45|17:30';
$string['configeventmsgtemplates'] = 'Event messages templates';
$string['displaytime'] = 'Show times as';
$string['timeperiod'] = 'period';
$string['timetime'] = 'hh:mm';

// Edit event
$string['eventtype'] = 'Event type';
$string['teacher'] = 'Teacher';
$string['teaching'] = 'Teaching and/or subject';
$string['classsection'] = 'Class or section';
$string['displaydate'] = 'Display event at date';
$string['displaytime'] = 'Event time';
$string['displaytext'] = 'Display message';
$string['displayevent'] = 'Show event';
$string['createdinfo'] = 'Created by';
$string['editedinfo'] = 'Last edit by';
$string['notes'] = 'Annotations';

//Days of the week
$string['day-1'] = 'Monday';
$string['day-2'] = 'Tuesday';
$string['day-3'] = 'Wednesday';
$string['day-4'] = 'Thursday';
$string['day-5'] = 'Friday';
$string['day-6'] = 'Saturday';
$string['day-7'] = 'Sunday';

//Months
$string['month-1'] = 'January';
$string['month-2'] = 'February';
$string['month-3'] = 'March';
$string['month-4'] = 'April';
$string['month-5'] = 'May';
$string['month-6'] = 'June';
$string['month-7'] = 'July';
$string['month-8'] = 'August';
$string['month-9'] = 'September';
$string['month-10'] = 'October';
$string['month-11'] = 'November';
$string['month-12'] = 'December';

// Database
$string['inserteventerror'] = 'Error in insert record in database.';
$string['updateeventerror'] = 'Error in updating record in database.';

// Filter
$string['selectfilter'] = 'Set filter';
$string['displaydatefrom'] = 'Display date from';
$string['displaydateto'] = 'Display date to';

// User display options
$string['lastnamename'] = 'Lastname Name';
$string['lastnameinitial'] = 'Lastname N.';

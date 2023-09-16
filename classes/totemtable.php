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

namespace local_totem\data;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");
require_once(__DIR__ . '/../lib.php');

class totemtable extends \external_api implements \renderable, \templatable {
    private $data = [];
    
    /**
     * Set the initial properties for the table
     * 
     */
    public function __construct($params) {
        $this->data = $params;
        $this->set_date($params['d']);
    }
    
    public function set_date($date) {
        $d = new \DateTime();
        $d->setTimestamp($date);        
        $d->setTime(0,0);
        
        $this->data['d'] = $d->getTimestamp();
        $this->data['date'] = $d->getTimestamp();
        $this->data['date_text'] = $this->format_date($date);

        return $this->data['date'];
    }
    
    private function format_date($date) {
        $d = new \DateTime();
        $d->setTimestamp($date);
        $d->setTime(0,0);
        
        return get_string('day-'.$d->format('N'), 'local_totem').' '.$d->format('j').' '.get_string('month-'.$d->format('n'), 'local_totem').' '.$d->format('Y');
    }
    
    public function get_records($params = NULL) {
        global $DB;
        global $local_totem_config;
        
        local_totem_load_configuration();
        
        //LOAD EVENT TYPE LIST FROM CONFIG
        $list = explode("\n", $local_totem_config['config_eventtypelist']);
        $eventtypelist = array();
        foreach ($list as $item) {
            $param = explode('|', $item);
            if (count($param)==3) {
                $eventtypelist[$param[0]] = $param[2];
            }
        }

        //LOAD TIMETABLE FROM CONFIG
        $list = explode("\n", $local_totem_config['config_timetable']);
        $timestarttable = array();
        $timeendtable = array();
        foreach ($list as $item) {
            $param = explode('|', $item);
            if (count($param)==3) {
                $timestarttable[$param[0]] = $param[1];
                $timeendtable[$param[0]] = $param[2];
            }
        }
        
        //LOAD TEACHERFIELD FROM CONFIG
        $return = array();
        $sql = '';
        $rs = null;
        $teacherfield = '';
        switch ($local_totem_config['config_teachingshow']) {
            case 1: //$TEACHERSDISPLAY[1] = get_string('lastname', 'moodle');
                $teacherfield = 'u.lastname';
                break;
            case 2: //$TEACHERSDISPLAY[1] = get_string('lastnamename', 'local_totem');
                $teacherfield = 'CONCAT(u.lastname, " ", u.firstname)';
                break;
            case 3: //$TEACHERSDISPLAY[1] = get_string('lastnameinitial', 'local_totem');
                $teacherfield = 'CONCAT(u.lastname, " ", MID(u.firstname, 1, 1), ".")';
                break;
            default: //$TEACHERSDISPLAY[0] = get_string('idnumber', 'moodle');
                $teacherfield = 'u.idnumber';
        }
        
        //LOAD FILTERS
        if ($params == null) {
            $params = array();
            $params['date'] = $this->data['date'];
            if (array_key_exists('date_to', $this->data)) {
                $params['date_to'] = $this->data['date_to'];
            }
            if (array_key_exists('teacher', $this->data) && $this->data['teacher'] != '') {
                $params['teacher'] = $this->data['teacher'];
            }
            if (array_key_exists('teaching', $this->data) && $this->data['teaching'] != '') {
                $params['teaching'] = $this->data['teaching'];
            }
            if (array_key_exists('classsection', $this->data) && $this->data['classsection'] != '') {
                $params['classsection'] = $this->data['classsection'];
            }
            $params['hidden'] = ($this->data['showHidden'] == TRUE ? 0 : 1);
        }
        
        //SET FILTERS SQL
        $where = "";
        //by date
        if (array_key_exists('date_to', $params)) {
            $where = " (te.date >= :date AND te.date < :date_to)";
        } else {
            $where = " (te.date = :date)";
        }
        //by teacher
        if (array_key_exists('teacher', $params)) {
            $where .= " AND (LEFT(". $teacherfield .", " . strlen($params['teacher']) .") = :teacher)";
        }
        //by teaching
        if (array_key_exists('teaching', $params)) {
            $where .= " AND (LEFT(CONCAT(te.teaching, te.subject), " . strlen($params['teaching']) .") = :teaching)";
        }
        //by classsection
        if (array_key_exists('classsection', $params)) {
            $where .= " AND (te.section = :classsection)";
        }
        //by visibility
        $where .= " AND (te.displayevent = 1 OR te.displayevent = :hidden)";

        //SET SQL
        $sql = "SELECT te.id, te.date, te.eventtype, " . $teacherfield . " AS teacher, te.teaching, te.subject, te.section, te.timestart, te.timeend, te.time, te.displaytext, te.displayevent, te.notes
            FROM {local_totem_event} te
            LEFT JOIN {user} u ON te.userid = u.id 
            WHERE" . $where . "
            ORDER BY te.date, te.timestart, te.timeend, te.time, te.section";
        
        //LOAD TOTEM EVENTS FROM DB
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            $return[] = array(
                'id' => $record->id,
                'date' => $record->date,
                'date_text' => $this->format_date($record->date),
                'eventtype' => $record->eventtype,
                'eventtypecss' => ($record->eventtype=='' ? '' : $eventtypelist[$record->eventtype]),
                'teacher' => $record->teacher,
                'teaching' => $record->teaching,
                'subject' => $record->subject,
                'section' => $record->section,
                'timestart' => (intval($record->timestart) == 0 ? '' : ($local_totem_config['config_displayviewtime'] ? $record->timestart : $timestarttable[$record->timestart])),
                'timeend' => (intval($record->timeend) == 0 ? '' : ($local_totem_config['config_displayviewtime'] ? ($record->timeend == $record->timestart ? '' : $record->timeend) : $timeendtable[$record->timeend])),
                'time' => $record->time,
                'displayhidden' => ($record->displayevent == 1 ? FALSE : TRUE),
                'displaytext' => $record->displaytext,
                'displayevent' => ($record->displayevent == 1 ? 'eye-slash' : 'eye'),
                'notes' => $record->notes,
            );
        }
        $this->data['recordcount'] = count($return);
        $this->data['records'] = $return;

        //SET CALENDAR EVENT SQL*
        $return = array();
        $sql = '';
        $rs = null;
        $sql = "SELECT e.id, e.name, e.location, e.description, e.timestart, e.timeduration, e.visible
            FROM {event} e
            WHERE e.timestart <= :date_to AND (e.timestart + e.timeduration) >= :date_from AND (e.visible = 1 OR e.visible = :hidden)
            ORDER BY e.timestart, e.timeduration, e.name";

        //SET FILTERS
        $params = array();
        $params['date_from'] = $this->data['date'];
        $params['date_to'] = $this->data['date'] + 1*24*60*60;
        $params['hidden'] = ($this->data['showHidden'] == TRUE ? 0 : 1);
        
        //LOAD CALENDAR EVENTS FROM DB
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            $return[] = array(
                'id' => $record->id,
                'name' => $record->name,
                'location' => $record->location,
                'description' => $record->description,
                'date' => '',
                'timestart' => $record->timestart,
                'timeduration' => $record->timeduration,
                'visible' => $record->visible,
            );
        }
        $this->data['calendarcount'] = count($return);
        $this->data['calendar'] = $return;
        
        return $this->data;
    }
    
    /**
     * Export this data so it can be used as the context for a mustache template
     * 
     * @return \stdClass
     */
    public function export_for_template(\renderer_base $output = null) {
        return $this->get_records();
    }

    public static function local_totem_get_totemtable_parameters() {
        return new \external_function_parameters(array(
            'date' => new \external_value(PARAM_INT, 'Timetable start date', PARAM_REQUIRED),
            'offset' => new \external_value(PARAM_INT, 'Offset filter day', VALUE_OPTIONAL, 0),
            'skipweekend' => new \external_value(PARAM_INT, 'Skip weekend in offset', PARAM_OPTIONAL, 0),
            'logo' => new \external_value(PARAM_TEXT, 'Moodle logo', PARAM_OPTIONAL, 0)
        ));
    }
    
    public static function local_totem_get_totemtable_is_allowed_from_ajax() {
        return true;
    }
    
    public static function local_totem_get_totemtable($date, $offset, $skipweekend, $logo) {        
        //Calculate the day to show
        $d = new \DateTime();
        $d->setTimestamp($date);
        $d->setTime(0,0);
        $i=0;
        
        while($i<$offset || ($skipweekend == 1 && intval($d->format('N')) > 5)) {
            if ($skipweekend == 0 || intval($d->format('N')) <= 5) {
                $i++;
            }
            $d->modify('+1 day');
        }
        
        //get_records
        $totem = new totemtable(array(
            'd' => $d->getTimestamp(),
            'logo' => $logo
        ));

        return $totem->export_for_template();
    }
    
    public static function local_totem_get_totemtable_returns() {
        return new \external_single_structure(array(
            'logo' => new \external_value(PARAM_TEXT, 'Moodle logo url', PARAM_REQUIRED),
            'd' => new \external_value(PARAM_TEXT, 'Totemtable date', PARAM_REQUIRED),
            'date' => new \external_value(PARAM_TEXT, 'Totemtable date', PARAM_REQUIRED),
            'date_text' => new \external_value(PARAM_TEXT, 'Totemtable date extended text', PARAM_REQUIRED),
            'recordcount' => new \external_value(PARAM_TEXT, 'Totemtable record count', PARAM_REQUIRED),
            'records' => new \external_multiple_structure(new \external_single_structure(array(
                'id' => new \external_value(PARAM_INT, 'Event ID', PARAM_REQUIRED),
                'date' => new \external_value(PARAM_INT, 'Event date', PARAM_REQUIRED),
                'date_text' => new \external_value(PARAM_TEXT, 'Event date text', PARAM_REQUIRED),
                'eventtype' => new \external_value(PARAM_TEXT, 'Event type', PARAM_REQUIRED),
                'eventtypecss' => new \external_value(PARAM_TEXT, 'Event type css', PARAM_REQUIRED),
                'teacher' => new \external_value(PARAM_TEXT, 'Teacher', PARAM_REQUIRED),
                'teaching' => new \external_value(PARAM_TEXT, 'Teaching', PARAM_REQUIRED),
                'subject' => new \external_value(PARAM_TEXT, 'Subject', PARAM_REQUIRED),
                'section' => new \external_value(PARAM_TEXT, 'School section', PARAM_REQUIRED),
                'time' => new \external_value(PARAM_TEXT, 'Event time', PARAM_REQUIRED),
                'timestart' => new \external_value(PARAM_TEXT, 'Event time start', PARAM_REQUIRED),
                'timeend' => new \external_value(PARAM_TEXT, 'Event time end', PARAM_REQUIRED),
                'displayhidden' => new \external_value(PARAM_BOOL, 'Hidden', PARAM_REQUIRED),
                'displaytext' => new \external_value(PARAM_TEXT, 'Display text', PARAM_REQUIRED),
                'displayevent' => new \external_value(PARAM_TEXT, 'Show to public', PARAM_REQUIRED),
                'notes' => new \external_value(PARAM_TEXT, 'Notes', PARAM_REQUIRED)
            ))),
            'calendarcount' => new \external_value(PARAM_TEXT, 'Totemtable date extended text', PARAM_REQUIRED),
            'calendar' => new \external_multiple_structure(new \external_single_structure(array(
                'id' => new \external_value(PARAM_INT, 'Calendar event ID', PARAM_REQUIRED),
                'name' => new \external_value(PARAM_TEXT, 'Calendar event name', PARAM_REQUIRED),
                'location' => new \external_value(PARAM_TEXT, 'Calendar event location', PARAM_REQUIRED),
                'description' => new \external_value(PARAM_RAW, 'Calendar event description', PARAM_REQUIRED),
                'date' => new \external_value(PARAM_TEXT, 'Calendar event date', PARAM_REQUIRED),
                'timestart' => new \external_value(PARAM_INT, 'Calendar event start', PARAM_REQUIRED),
                'timeduration' => new \external_value(PARAM_INT, 'Calendar event duration', PARAM_REQUIRED),
                'visible' => new \external_value(PARAM_INT, 'Calendar visibility', PARAM_REQUIRED)
            )))
        ));
    }
}
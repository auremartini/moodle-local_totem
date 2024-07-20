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

class teachinglist extends \external_api {
    public static function local_totem_get_teachinglist_parameters() {
        return new \external_function_parameters(array(
            'blockteachings' => new \external_value(PARAM_TEXT, 'Block teachings IDs (comma separated)', PARAM_REQUIRED),
            'teacherid' => new \external_value(PARAM_INT, 'Teacher ID', PARAM_REQUIRED)
        ));
    }
    
    public static function local_totem_get_teachinglist_is_allowed_from_ajax() {
        return true;
    }
    
    public static function local_totem_get_teachinglist($blockteachings, $teacherid) {
        global $DB;
        $blockteachings = explode(',', $blockteachings);
        $return = array();
        $sql = '';
        $params = array();
        $rs = null;
        $sql = "SELECT c.id, c.idnumber, c.name
                FROM {cohort} c
                LEFT JOIN {cohort_members} cm ON c.id = cm.cohortid
                WHERE cm.userid = :userid
                ORDER BY c.name";
        $params['userid'] = intval($teacherid);
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            if (in_array($record->id, $blockteachings)) {
                $return[] = array(
                    'id' => $record->idnumber,
                    'name' => $record->name,
                );
            }
        }
        
        return $return;
    }
    
    public static function local_totem_get_teachinglist_returns() {
        return new \external_multiple_structure(new \external_single_structure(array(
            'id' => new \external_value(PARAM_TEXT, 'Teaching ID', PARAM_REQUIRED),
            'name' => new \external_value(PARAM_TEXT, 'Teaching name', PARAM_REQUIRED)
        )));
    }
}
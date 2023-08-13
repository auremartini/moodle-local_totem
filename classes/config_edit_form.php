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

/**
 * Form for editing HTML block instances.
 *
 * @package   block_html
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing HTML block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_totem\classes;

require_once("{$CFG->libdir}/formslib.php");

class config_edit_form extends \moodleform {
    protected function definition() {
        global $CFG, $DB;
        $mform =& $this->_form;

        $mform->addElement('hidden', 'd');
        $mform->setType('d', PARAM_INT);
                
        $mform->addElement('header', 'generalhdr', get_string('general'));
        
        // Teachers show setting
        $TEACHERSDISPLAY = array();
        $TEACHERSDISPLAY[0] = get_string('idnumber', 'moodle');
        $TEACHERSDISPLAY[1] = get_string('lastname', 'moodle');
        $mform->addElement('select', 'config_teachingshow', get_string('configteachingshow', 'local_totem'), $TEACHERSDISPLAY);
        
        // Teachers list
        $SOURCE = array();
        $SOURCE[0] = get_string('roles', 'moodle');
        $SOURCE[1] = get_string('cohorts', 'core_cohort');
        
        $sql = 'SELECT id, idnumber FROM {cohort} c
                ORDER BY c.idnumber';
        $rs = $DB->get_records_sql($sql);
        $COHORTS = array();
        foreach ($rs as $record) {
            $COHORTS[$record->id] = $record->idnumber;
        }
        
        $sql = 'SELECT r.id, r.shortname FROM {role} r
                LEFT JOIN {role_context_levels} cl ON r.id = cl.roleid
                WHERE cl.contextlevel = 10 
                ORDER BY r.shortname';
        $rs = $DB->get_records_sql($sql);
        $ROLES = array();
        foreach ($rs as $record) {
            $ROLES[$record->id] = $record->shortname;
        }
        
        $a=array();
        $a[] =& $mform->createElement('select', 'config_source', '', $SOURCE);
        $a[] =& $mform->createElement('select', 'config_sourceroleid', '', $ROLES);
        $a[] =& $mform->createElement('select', 'config_sourcecohortid', '', $COHORTS);
        $mform->addGroup($a, 'teachersdays', get_string('configsourcedesc', 'local_totem'), '', FALSE);
        $mform->setType('config_source', PARAM_INT);
        $mform->setType('config_sourceroleid', PARAM_INT);
        $mform->setType('config_sourcecohortid', PARAM_INT);
        $mform->hideIf('config_sourceroleid', 'config_source', 'neq', '0');
        $mform->hideIf('config_sourcecohortid', 'config_source', 'neq', '1');
        
        // Teaching groups list
        $mform->addElement('autocomplete', 'config_teachings', get_string('configteachingdesc', 'local_totem'), $COHORTS, array(
            'size'=>'20',
            'multiple' => TRUE
        ));
        
        // Event type list
        $mform->addElement('textarea', 'config_eventtypelist', get_string('configeventtypelist', 'local_totem'), array('cols'=>'50', 'rows'=>'4'));
        $mform->setDefault('config_eventtypelist', get_string('configeventtypelistdefault', 'local_totem'));
        
        // Timetable
        $mform->addElement('textarea', 'config_timetable', get_string('configtimetable', 'local_totem'), array('cols'=>'50', 'rows'=>'4'));
        $mform->setDefault('config_timetable', get_string('configtimetabledefault', 'local_totem'));
        $a = array();
        $a[] = $mform->createElement('radio', 'config_displayviewtime', '', get_string('timeperiod', 'local_totem'), 1);
        $a[] = $mform->createElement('radio', 'config_displayviewtime', '', get_string('timetime', 'local_totem'), 0);
        $mform->addGroup($a, 'displaytime', get_string('displaytime', 'local_totem'), array(' '), FALSE);
        $mform->setDefault('config_displayviewtime', 1);
        $mform->setType('config_displayviewtime', PARAM_INT);
        
        // Display table settings
        $mform->addElement('header', 'generalhdr', get_string('viewtableheader', 'local_totem'));
        $DISPLAYOPTION = array();
        $DISPLAYOPTION[0] = get_string('configdisplaytemplate0', 'local_totem');
//        $DISPLAYOPTION[1] = get_string('configdisplaytemplate1', 'local_totem');
        $a=array();
        $a[] =& $mform->createElement('text', 'config_viewdays', '', array('size'=>'3'));
        $a[] =& $mform->createElement('advcheckbox', 'config_viewskipweekend', '', get_string('configskipweekend', 'local_totem'), '', array(0, 1));
        $mform->addGroup($a, 'pagedays', get_string('configdisplaydaysdesc', 'local_totem'), '', FALSE);
        $mform->setType('config_viewdays', PARAM_INT);
        $mform->setType('config_viewskipweekend', PARAM_BOOL);
        $mform->setDefault('config_viewdays', 5);
        $mform->addElement('select', 'config_viewtemplate', get_string('configviewdisplay', 'local_totem'), $DISPLAYOPTION);
        $mform->setDefault('config_viewtemplate', 0);

        // Fullscreen settings
        $mform->addElement('header', 'generalhdr', get_string('viewfullscreenheader', 'local_totem'));
        $FULLSCREENOPTION = array();
        $FULLSCREENOPTION[0] = get_string('configfulscreentemplate0', 'local_totem');
//        $FULLSCREENOPTION[1] = get_string('configfulscreentemplate1', 'local_totem');
        $a=array();
        $a[] =& $mform->createElement('text', 'config_fullscreendays', '', array('size'=>'3'));
        $a[] =& $mform->createElement('advcheckbox', 'config_fullscreenskipweekend', '', get_string('configskipweekend', 'local_totem'), '', array(0, 1));
        $mform->addGroup($a, 'fullscreendays', get_string('configfullscreendaysdesc', 'local_totem'), '', FALSE);
        $mform->setType('config_fullscreendays', PARAM_INT);
        $mform->setType('config_fullscreenskipweekend', PARAM_BOOL);
        $mform->setDefault('config_fullscreendays', 3);
        $mform->addElement('select', 'config_fullscreentemplate', get_string('configfullscreen', 'local_totem'), $FULLSCREENOPTION);
        $mform->setDefault('config_fullscreentemplate', 0);
        
        $this->add_action_buttons();
    }
}
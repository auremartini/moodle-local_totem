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

class event_edit_form extends \moodleform {
    
    function definition() {
        global $CFG, $DB;
        
        $mform =& $this->_form;
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'userid');
        $mform->setType('userid', PARAM_INT);
        $mform->addElement('hidden', 'url');
        $mform->setType('url', PARAM_TEXT);
        $mform->addElement('hidden', 'd');
        $mform->setType('d', PARAM_INT);
        $mform->addElement('hidden', 'teaching');
        $mform->setType('teaching', PARAM_TEXT);
        $mform->addElement('hidden', 'eventtype');
        $mform->setType('eventtype', PARAM_TEXT);
        $mform->addElement('hidden', 'timestart');
        $mform->setType('timestart', PARAM_TEXT);
        $mform->addElement('hidden', 'timeend');
        $mform->setType('timeend', PARAM_TEXT);
        $mform->addElement('hidden', 'displaytexttemplate');
        $mform->setType('displaytexttemplate', PARAM_TEXT);
        $mform->addElement('hidden', 'blank');
        $mform->setType('blank', PARAM_TEXT);
        
        $EVENT_TYPES = array('' => '');
        
        $TEACHER_LIST = array('0' => '');
        
        $TEACHINGS = array();
        
        $TIME_START = array();
        
        $TIME_END = array();
        
        $MSG_TEMPLATES = array();
        
        $mform->addElement('header', 'generalhdr', get_string('general'));

        // add type element
        $mform->addElement('select', 'eventtypelist', get_string('eventtype', 'local_totem'), $EVENT_TYPES);
        $mform->setType('eventtypelist', PARAM_TEXT);
        
        // add teacher element
        $mform->addElement('select', 'useridlist', get_string('teacher', 'local_totem'), $TEACHER_LIST);
        $mform->setType('useridlist', PARAM_INT);
        
        // add subject element
        $a=array();
        $a[] =& $mform->createElement('select', 'teachinglist', '', $TEACHINGS);
        $a[] =& $mform->createElement('text', 'subject', '', array('size'=>'10'));
        $mform->addGroup($a, 'teachingandsubject', get_string('teaching', 'local_totem'), '', FALSE);
        $mform->setType('teaching', PARAM_TEXT);
        $mform->setType('subject', PARAM_TEXT);
        
        // add section element
        $mform->addElement('text', 'section', get_string('classsection', 'local_totem'), array('size'=>'20'));
        $mform->setType('section', PARAM_TEXT);
        
        // add date element
        $mform->addElement('date_selector', 'date', get_string('displaydate', 'local_totem'));
        $mform->setType('date', PARAM_INT);
        
        // add time element
        $a = array();
        $a[] =& $mform->createElement('select', 'timestartlist', '', $TIME_START);
        $a[] =& $mform->createElement('select', 'timeendlist', '', $TIME_START);
        $a[] =& $mform->createElement('text', 'time', '', array('size'=>'20'));
        $mform->addGroup($a, 'times', get_string('displaytime', 'local_totem'), '', FALSE);
        $mform->setType('timestart', PARAM_TEXT);
        $mform->setType('timeend', PARAM_TEXT);
        $mform->setType('time', PARAM_TEXT);
        
        // add message element
        $a = array();
        $a[] =& $mform->createElement('select', 'displaytexttemplatelist', '', $MSG_TEMPLATES);
        $a[] =& $mform->createElement('text', 'displaytext', '', array('size'=>'50'));
        $mform->addGroup($a, 'displaytextsection', get_string('displaytext', 'local_totem'), '', FALSE);
        $mform->setType('displaytexttemplatelist', PARAM_TEXT);
        $mform->setType('displaytext', PARAM_TEXT);
        
        // add displayevent element
        $a = array();
        $a[] = $mform->createElement('radio', 'displayevent', '', get_string('yes'), 1);
        $a[] = $mform->createElement('radio', 'displayevent', '', get_string('no'), 0);
        $mform->addGroup($a, 'displayeventgroup', get_string('displayevent', 'local_totem'), array(' '), FALSE);
        $mform->setDefault('displayevent', 1);
        $mform->setType('displayevent', PARAM_INT);
        
        // add section element
        $mform->addElement('textarea', 'notes', get_string('notes', 'local_totem'), array('cols'=>'50', 'rows'=>'4'));
        $mform->setType('notes', PARAM_TEXT);
        
        // add creation data
        $mform->addElement('text', 'createdbytext', get_string('createdinfo', 'local_totem'), array('size'=>'50'));
        $mform->setType('createdbytext', PARAM_TEXT);
        $mform->disabledif('createdbytext', 'blank', 'eq', '');
        
        // add editing data
        $mform->addElement('text', 'editedbytext', get_string('editedinfo', 'local_totem'), array('size'=>'50'));
        $mform->setType('editedbytext', PARAM_TEXT);
        $mform->disabledif('editedbytext', 'blank', 'eq', '');
        
        $this->add_action_buttons();
    }
}
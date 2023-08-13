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

class filter_selection extends \moodleform {
    
    function definition() {
        $mform =& $this->_form;
        $mform->addElement('hidden', 'url');
        $mform->setType('url', PARAM_TEXT);

        // add date element
        $mform->addElement('date_selector', 'date', get_string('displaydatefrom', 'local_totem'));
        $mform->setType('date', PARAM_INT);
        $mform->addElement('date_selector', 'date_to', get_string('displaydateto', 'local_totem'));
        $mform->setType('date_to', PARAM_INT);
        
        // add teacher element
        $mform->addElement('text', 'teacher', get_string('teacher', 'local_totem'), array('size'=>'20'));
        $mform->setType('teacher', PARAM_TEXT);
        
        // add subject element
        $mform->addElement('text', 'teaching', get_string('teaching', 'local_totem'), array('size'=>'20'));
        $mform->setType('teaching', PARAM_TEXT);

        // add section element
        $mform->addElement('text', 'classsection', get_string('classsection', 'local_totem'), array('size'=>'20'));
        $mform->setType('classsection', PARAM_TEXT);
        
        $this->add_action_buttons();
    }
}
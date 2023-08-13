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

namespace local_totem\output;

class renderer extends \plugin_renderer_base {
    
    public function render($totem) {
        return parent::render_from_template('local_totem/totem_table', $totem->export_for_template($this));
    }
    
    public function render_fullscreen($totem) {
        return parent::render_from_template('local_totem/totem_table_fullscreen', $totem->export_for_template($this));
    }

//    public function render_compact($totem) {
//        return parent::render_from_template('block_totem/totem_table_compact', $totem->export_for_template($this));
//    }
    
    public function render_list($totem) {
        return parent::render_from_template('local_totem/totem_list', $totem->export_for_template($this));
    }
    
//    public function open_totem($id) {
//        $footer = null;
//        $url = new \moodle_url('/blocks/totem/view.php', array('blockid' => $id));
//        $footer = '<div style="text-align:right"><form method="post" action="'.$url.'">
//                   <button type="submit" class="btn btn-secondary" title="">'.get_string('opentotempage', 'block_totem').'</button>
//                   </form></div>';
//        
//        return $footer;
//    }   

    public function renderGearMenu($config) {
        return parent::render_from_template('local_totem/dropMenu', $config);
    }
}

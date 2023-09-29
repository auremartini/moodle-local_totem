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
$string['plugin_navbar_lavel'] = 'Modifiche all\'orario';
$string['plugin_page_title'] = 'Modifiche all\'orario';

//Administration text
$string['totem:access'] = 'Accesso alla pagina delle modifiche all\'orario';
$string['totem:config'] = 'Modificare le impostazioni';
$string['totem:filterview'] = 'Visualizzare i filtri';
$string['totem:addevent'] = 'Aggiungere evento';
$string['totem:editevent'] = 'Modificare evento';
$string['totem:deleteevent'] = 'Cancellare evento';
$string['totem:fullscreen'] = 'Aprire visualizzazione a tutto schermo';

// View page 
$string['config'] = 'Impostazioni';
$string['filterevents'] = 'Filtri';
$string['fullscreen'] = 'Schermo intero';
$string['gotodate'] = 'Vai';
$string['addtotemevent'] = 'Aggiungi';
$string['showtotemevent'] = 'Mostra';
$string['hidetotemevent'] = 'Nascondi';
$string['edittotemevent'] = 'Modifica';
$string['copytotemevent'] = 'Copia';
$string['deletetotemevent'] = 'Elimina';

// Config page
$string['viewtableheader'] = 'Impostazioni visualizzazione a tabella';
$string['viewfullscreenheader'] = 'Impostazioni visualizzazione a schermo intero';
$string['configteachingshow'] = 'Mostra insegnanti come';
$string['configsourcedesc'] = 'Filtra gli insegnanti da';
$string['configteachingdesc'] = 'Materie insegnate';
$string['configdisplaytemplate0'] = 'Default';
$string['configfulscreentemplate0'] = 'Default';
$string['configdisplaydaysdesc'] = 'Giorni da mostrare';
$string['configfullscreendaysdesc'] = 'Giorni da mostrare';
$string['configskipweekend'] = 'Nascondi i fine settimana';
$string['configviewdisplay'] = 'Modello per la visualizzazione a tabella';
$string['configfullscreen'] = 'Modello per la visualizzazione a schermo intero';
$string['configeventtypelist'] = 'Tipologie';
$string['configeventtypelistdefault'] = 'A|Assenza docente|background-color:red;&#13;R|Recupero ore|background-color:yellow;&#13;L|Liberi|background-color:green;';
$string['configtimetable'] = 'Griglia oraria';
$string['configtimetabledefault'] = '1|08:15|09:00&#13;2|09:05|09:50&#13;3|10:05|10:50&#13;4|10:55|11:40&#13;5|11:45|12:30&#13;6|12:30|13:15&#13;7|13:20|14:05&#13;8|14:10|14:55&#13;9|15:05|15:50&#13;10|15:55|16:40&#13;11|16:45|17:30';
$string['configeventmsgtemplates'] = 'Lista dei messaggi preimpostati';
$string['displaytime'] = 'Mostra orari come';
$string['timeperiod'] = 'blocco griglia';
$string['timetime'] = 'hh:mm';

// Edit event
$string['eventtype'] = 'Tipologia';
$string['teacher'] = 'Docente';
$string['teaching'] = 'Materia';
$string['classsection'] = 'Sezione o gruppo';
$string['displaydate'] = 'Data';
$string['displaytime'] = 'Orario';
$string['displaytext'] = 'Messaggio';
$string['displayevent'] = 'Visibilità';
$string['createdinfo'] = 'Creato da';
$string['editedinfo'] = 'Ultima modifica';
$string['notes'] = 'Note';

//Days of the week
$string['day-1'] = 'Lunedì';
$string['day-2'] = 'Martedì';
$string['day-3'] = 'Mercoledì';
$string['day-4'] = 'Giovedì';
$string['day-5'] = 'Venerdì';
$string['day-6'] = 'Sabato';
$string['day-7'] = 'Domenica';

//Months
$string['month-1'] = 'Gennaio';
$string['month-2'] = 'Febbraio';
$string['month-3'] = 'Marzo';
$string['month-4'] = 'Aprile';
$string['month-5'] = 'Maggio';
$string['month-6'] = 'Giugno';
$string['month-7'] = 'Luglio';
$string['month-8'] = 'Agosto';
$string['month-9'] = 'Settembre';
$string['month-10'] = 'Ottobre';
$string['month-11'] = 'Novembre';
$string['month-12'] = 'Dicembre';

// Database
$string['inserteventerror'] = 'Errore nell\'aggiunta al database.';
$string['updateeventerror'] = 'Errore nella modifica al database.';

// Filter
$string['selectfilter'] = 'Imposta filtro';
$string['displaydatefrom'] = 'Mostra eventi da';
$string['displaydateto'] = 'Mostra eventi fino a';

// User display options
$string['lastnamename'] = 'Cognome Nome';
$string['lastnameinitial'] = 'Cognome N.';

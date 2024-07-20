/**
 * @module    block_totem/add_totemfullscreen_dynamics
 * @package   block_totem
 * @copyright 2020 Aureliano Martini
 * @licence   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since     3.0
 */

define(['jquery'], function() {
    return {
        init: function(params) {
            require(['local_totem/get_ajax_totemtable'], function(totemtable) {
                totemtable.load(params.date, params.offset, params.limit, params.skipweekend, params.logo, params.template);
            });
        }
    };
});
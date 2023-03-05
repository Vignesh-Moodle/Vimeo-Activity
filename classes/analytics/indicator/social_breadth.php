<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Social breadth indicator - vimeoactivity.
 *
 * @package mod_vimeoactivity
 * @author Mohammad Farouk
 * @copyright   2023 Mohammad Farouk <phun.for.physics@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_vimeoactivity\analytics\indicator;
/**
 * Activity base class.
 *
 * @package mod_vimeoactivity
 * @author Mohammad Farouk
 * @copyright   2023 Mohammad Farouk <phun.for.physics@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class social_breadth extends activity_base {

    /**
     * Returns the name.
     *
     * If there is a corresponding '_help' string this will be shown as well.
     *
     * @return \lang_string
     */
    public static function get_name() : \lang_string {
        return new \lang_string('indicator:socialbreadth', 'mod_vimeoactivity');
    }
    /**
     * Define indicator typr
     *
     * @return string
     */
    public function get_indicator_type() {
        return self::INDICATOR_SOCIAL;
    }
    /**
     * Returns the potential level of social breadth.
     *
     * @return int
     */
    public function get_social_breadth_level(\cm_info $cm) {
        return self::SOCIAL_LEVEL_1;
    }
}

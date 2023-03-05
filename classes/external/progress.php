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

namespace mod_vimeoactivity\external;

use completion_info;
use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use mod_vimeoactivity\util\watchprog;

defined('MOODLE_INTERNAL') || die();

global $USER, $config;
/**
 * Completion external api class.
 *
 * @package mod_vimeoactivity
 * @author Mohammad Farouk
 * @copyright   2023 Mohammad Farouk <phun.for.physics@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class progress extends external_api {
    /**
     * Complete parameters
     *
     * @return external_function_parameters
     */
    public static function track_parameters() {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'The course module id'),
            'progress' => new external_value(PARAM_INT, 'The progress'),
        ]);
    }

    /**
     * Complete method
     *
     * @param int $cmid
     * @param int $watchprog
     * @return array
     *
     * @throws \coding_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public static function track($cmid, $watchprog) {
        global $DB, $USER, $CFG;

        // We always must pass webservice params through validate_parameters.
        self::validate_parameters(self::track_parameters(), ['cmid' => $cmid, 'progress' => $watchprog]);

        list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'video');

        $context = \context_course::instance($course->id);

        if (!is_enrolled($context)) {
            return [
                'status' => 'notenrolled'
            ];
        }

        $progutil = new watchprog();
        $progutil->addprogress($cm->instance, $watchprog);
        $completion = new completion_info($course);
        $completion->update_state($cm, COMPLETION_COMPLETE);

        return [
            'status' => 'ok'
        ];
    }

    /**
     * Complete return fields
     *
     * @return external_single_structure
     */
    public static function track_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_TEXT, 'Operation status')
            )
        );
    }
}

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

declare(strict_types=1);

namespace mod_vimeoactivity\completion;

use core_completion\activity_custom_completion;
use mod_vimeoactivity\util\watchprog;

/**
 * Activity custom completion subclass for the Assign Tutor activity.
 *
 * Class for defining mod_vimeoactivity's custom completion rules and fetching the completion statuses
 * of the custom completion rules for a given instance and a user.
 *
 */
class custom_completion extends activity_custom_completion {

    /**
     * Fetches the completion state for a given completion rule.
     *
     * @param string $rule The completion rule.
     * @return int The completion state.
     */
    public function get_state(string $rule): int {
        global $DB;

        $this->validate_rule($rule);
        $videoid = $this->cm->instance;

        if (!$vimeoactivity = $DB->get_record('vimeoactivity', ['id' => $videoid])) {
            throw new \moodle_exception('Unable to find video with id ' . $videoid);
        }

        if ($rule == 'completionprogress') {
            $requiredprogress = $vimeoactivity->completionprogress;

            $progutil = new watchprog();

            $userhighprogress = $progutil->vimeoactivity_fetch_progress($this->userid, $videoid);

            if ($userhighprogress >= $requiredprogress) {
                return COMPLETION_COMPLETE;
            }
        }

        return COMPLETION_INCOMPLETE;
    }

    /**
     * Fetch the list of custom completion rules that this module defines.
     *
     * @return array
     */
    public static function get_defined_custom_rules(): array {
        return ['completionprogress'];
    }

    /**
     * Returns an associative array of the descriptions of custom completion rules.
     *
     * @return array
     */
    public function get_custom_rule_descriptions(): array {
        global $DB;

        $videoid = $this->cm->instance;

        if (!$vimeoactivity = $DB->get_record('vimeoactivity', ['id' => $videoid])) {
            throw new \moodle_exception('Unable to find vidoe with id ' . $videoid);
        }

        return [
            'completionprogress' => get_string('completionprogress_ruledesc',
             'mod_vimeoactivity', $vimeoactivity->completionprogress)
        ];
    }

    /**
     * Returns an array of all completion rules, in the order they should be displayed to users.
     *
     * @return array
     */
    public function get_sort_order(): array {
        return [
            'completionview',
            'completionprogress'
        ];
    }
}

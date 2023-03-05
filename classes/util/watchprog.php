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

namespace mod_vimeoactivity\util;

/**
 * watchprogress class.
 * @package mod_vimeoactivity
 * @author Mohammad Farouk
 * @copyright   2023 Mohammad Farouk <phun.for.physics@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class watchprog {
        /**
         * [vimeoactivity_fetch_progress]
         *
         * @param integer $userid
         * @param integer $videoid
         * @return object|null
         */
    public function vimeoactivity_fetch_progress($userid, $videoid) {
        // Importing all the required global
        // objects into this function scope.
        global $DB;

        // Normalizing the supplied arguments and making
        // sure they are within the required parameters.
        $userid = max(0, (integer)$userid);
        $videoid = max(0, (integer)$videoid);
        $result = null;

        // Verifying if the supplied identifiers are valid
        // (greater than zero) and, if not, there is no need
        // to even touch the database and returning a null
        // value as this function result.
        if ($userid < 1 || $videoid < 1) {
            return($result);
        }

        // Compiling the required command to
        // load this object from the database.
        $sql = "SELECT id,".
                    "user,".
                    "video,".
                    "progress,".
                    "timecreated,".
                    "timemodified ".
                "FROM {vimeoactivity_progress} ".
                "WHERE user=? ".
                "AND video=? ".
                "LIMIT 1";

        // Executing the required command to
        // load this object from the database.

        if ($record = $DB->get_record_sql($sql, [$userid, $videoid])) {
            // Normalizing the loaded object
            // attributes and making sure they
            // are within the required parameters.
            $result = new \stdClass;
            $result->id = max(0, (integer)$record->id);
            $result->user = max(0, (integer)$record->user);
            $result->video = max(0, (integer)$record->video);
            $result->progress = max(0, (integer)$record->progress);
            $result->timecreated = max(0, (integer)$record->timecreated);
            $result->timemodified = max(0, (integer)$record->timemodified);
        }

        // Returning the object loaded
        // from the database, if any,
        // as this function result.
        return($result);
    }

    /**
     * [vimeoactivity_insert_progress]
     *
     * @param integer $userid
     * @param integer $videoid
     * @param integer $value
     * @return boolean
     */
    public function vimeoactivity_save_progress($userid, $videoid, $value) {
        // Importing all the required global
        // objects into this function scope.
        global $DB;

        // Normalizing the supplied arguments and making
        // sure they are within the required parameters.
        $userid = max(0, (integer)$userid);
        $videoid = max(0, (integer)$videoid);
        $value = max(0, (integer)$value);
        $created = time();

        // Verifying if the supplied identifiers are valid
        // (greater than zero) and, if not, there is no need
        // to even touch the database and returning a false
        // boolean value as this function result.
        if ($userid < 1 || $videoid < 1) {
            return(false);
        }

            // Compiling the required command
            // to persist the supplied object
            // progress within the database.
        if ($object = vimeoactivity_fetch_progress($userid, $videoid)) {
            // Verifying if the supplied progress value is higher than
            // what we have stored within the database, if true, store
            // it, otherwise return false because it would make no sense
            // to unsee the video, and it would mess up completion rules
            // that depends on it.
            if ($value > $object->progress) {
                // Compiling the required command
                // to update the supplied object
                // within the database.
                $values = ['id' => $object->id,
                        'progress' => $value,
                        'timemodified' => $created];

                // Executing the required command
                // to update the supplied object
                // within the database.
                return((boolean)$DB->update_record_raw('vimeoactivity_progress', $values));
            }
        } else {
            // Compiling the required command
            // to update the supplied object
            // within the database.
            $values = ['user' => $userid,
                    'video' => $videoid,
                    'progress' => $value,
                    'timecreated' => $created];

            // Executing the required command
            // to update the supplied object
            // within the database.
            return((boolean)$DB->insert_record_raw('vimeoactivity_progress', $values));
        }

        // Because we were unable to execute this operation
        // successfully, returning a false boolean value as
        // this function result.
        return(false);
    }

}

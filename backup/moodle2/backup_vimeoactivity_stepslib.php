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
 * Backup steps for mod_vimeoactivity are defined here.
 *
 * @package     mod_vimeoactivity
 * @category    backup
 */

defined('MOODLE_INTERNAL') || die();

// More information about the backup process: {@link https://docs.moodle.org/dev/Backup_API}.
// More information about the restore process: {@link https://docs.moodle.org/dev/Restore_API}.

/**
 * Define the complete structure for backup, with file and id annotations.
 */
class backup_vimeoactivity_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the resulting xml file.
     *
     * @return backup_nested_element The structure wrapped by the common 'activity' element.
     */
    protected function define_structure() {

        // Build the tree with these elements with $root as the root of the backup tree.
        $vimeoactivity = new backup_nested_element('vimeoactivity', ['id'], [
            'course',
            'name',
            'intro',
            'introformat',
            'video',
            'color',
            'autoplay',
            'autoloop',
            'popupopen',
            'popupwidth',
            'popupheight',
            'completionenable',
            'visible',
            'completionprogress',
            'timecreated',
            'timemodified']);


        // Define the source tables for the elements.
        $vimeoactivity->set_source_table('vimeoactivity', ['id' => backup::VAR_ACTIVITYID]);

        return $this->prepare_activity_structure($vimeoactivity);
    }
}

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
 * This file contains the required routines for this activity module.
 *
 * @package mod_vimeoactivity
 * @author Vignesh

 * @license http://www.gnu.org/copyleft/gpl.html
 */

// Loading all libraries, classes
// and functions required by this
// class execution.
require_once(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/locallib.php');

// Capturing the supplied identifier
// to load this Vimeo video instance.
$id = required_param('id', PARAM_INT);

// Trying to load from the database
// the requested Vimeo video using
// the supplied course module id.
$module = get_coursemodule_from_id('vimeoactivity', $id, 0, false, MUST_EXIST);
$context = context_module::instance($module->id);
$course = $DB->get_record('course', ['id' => $module->course], '*', MUST_EXIST);
$video = vimeoactivity_fetch_video($module->instance);

// Because we were unable to load the
// requested Vimeo video, displaying
// an error message about this.
if (empty($video)) {
    error('The requested Vimeo video was not found.');
}

// This user needs to be authenticated
// before viewing this Vimeo video.
require_login($course, true, $module);

// This user needs to be authorized
// before viewing this Vimeo video.
require_capability('mod/vimeoactivity:view', $context);

// Marking this Vimeo video as viewed so
// any other task that depends on it, works.
$completion = new completion_info($course);
$completion->set_module_viewed($module);

// Deciding if we need to render the Moodle
// interface (with header, footer, menus,
// blocks, etc) or the full screen one.
if ($video->popupopen == false) {

    $PAGE->set_url('/mod/vimeoactivity/view.php', ['id' => $video->id]);
    $PAGE->set_title(format_string($video->name));
    $PAGE->set_heading(format_string($course->fullname));

    echo($OUTPUT->header());
    echo(vimeoactivity_render_video($video, true, true, false));
    echo($OUTPUT->footer());

} else {

    echo('<!DOCTYPE html>'."\n");
    echo('<html>'."\n");
    echo('<head>'."\n");
    echo('<title>'.htmlentities($video->name).'</title>'."\n");
    echo('</head>'."\n");
    echo('<body>'."\n");
    echo(vimeoactivity_render_video($video, true, true, true));
    echo('</body>'."\n");
    echo('</html>');

}
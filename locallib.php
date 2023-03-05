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

/**
 * [vimeoactivity_fetch_video]
 *
 * @param integer $videoid
 * @return stdclass|null
 */
function vimeoactivity_fetch_video($videoid) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $videoid = max(0, (integer)$videoid);
    $result = null;

    // Verifying if the supplied identifier is valid
    // (greater than zero) and, if false, there is no
    // need to even touch the database and returning
    // a null value as this function result.
    if ($videoid < 1) {
        return($result);
    }

    // Compiling the required command to
    // load this object from the database.
    $sql = "SELECT id,".
                "course,".
                "name,".
                "video,".
                "color,".
                "intro,".
                "introformat,".
                "autoplay,".
                "autoloop,".
                "popupopen,".
                "popupwidth,".
                "popupheight,".
                "completionenable,".
                "completionprogress,".
                "visible,".
                "timecreated,".
                "timemodified ".
            "FROM {vimeoactivity} ".
            "WHERE id=?";

    // Executing the required command to
    // load this object from the database.
    if ($record = $DB->get_record_sql($sql, [$videoid])) {
        // Normalizing the loaded object
        // attributes and making sure they
        // are within the required parameters.
        $result = new stdclass();
        $result->id = max(0, (integer)$record->id);
        $result->course = max(0, (integer)$record->course);
        $result->name = trim((string)$record->name);
        $result->video = trim((string)$record->video);
        $result->color = trim((string)$record->color);
        $result->intro = trim((string)$record->intro);
        $result->introformat = max(0, (integer)$record->introformat);
        $result->autoplay = (boolean)$record->autoplay;
        $result->autoloop = (boolean)$record->autoloop;
        $result->popupopen = (boolean)$record->popupopen;
        $result->popupwidth = max(0, (integer)$record->popupwidth);
        $result->popupheight = max(0, (integer)$record->popupheight);
        $result->completionenable = (boolean)$record->completionenable;
        $result->completionprogress = max(0, (integer)$record->completionprogress);
        $result->visible = (boolean)$record->visible;
        $result->timecreated = max(0, (integer)$record->timecreated);
        $result->timemodified = max(0, (integer)$record->timemodified);
    }

    // Returning the object loaded
    // from the database, if any,
    // as this function result.
    return($result);
}

/**
 * [vimeoactivity_save_video]
 *
 * @param stdclass $video
 * @return boolean
 */
function vimeoactivity_save_video(stdclass $video) {
    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $video->id = max(0, (integer)$video->id);

    if ($video->id > 0) {
        return(vimeoactivity_update_video($video));
    } else {
        return(vimeoactivity_insert_video($video));
    }
}

/**
 * [vimeoactivity_insert_video]
 *
 * @param stdclass $video
 * @return boolean
 */
function vimeoactivity_insert_video(stdclass $video) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Before trying to persist the supplied video within
    // the database, validating its attributes and making
    // sure they are within the required parameters.
    if (vimeoactivity_validate_video($video)) {
        $video->timecreated = time();
        $video->timemodified = 0;

        // Compiling the required command
        // to persist the supplied object
        // within the database.
        $values = ['course' => $video->course, 'name' => $video->name,
                   'video' => $video->video, 'color' => $video->color,
                   'intro' => $video->intro, 'introformat' => $video->introformat,
                   'autoplay' => $video->autoplay, 'autoloop' => $video->autoloop,
                   'popupopen' => $video->popupopen, 'popupwidth' => $video->popupwidth,
                   'popupheight' => $video->popupheight, 'completionenable' => $video->completionenable,
                   'completionprogress' => $video->completionprogress, 'visible' => $video->visible,
                   'timecreated' => $video->timecreated, 'timemodified' => $video->timemodified];

        // Executing the required command
        // to persist the supplied object
        // within the database.
        if ($result = $DB->insert_record_raw('vimeoactivity', $values)) {
            // Storing the generated id
            // within the supplied object.
            $video->id = (integer)$result;

            // Because we were able to execute this operation
            // completely and successfully, returning a true
            // boolean value as this function result.
            return(true);
        }
    }

    // Because we were unable to execute this operation
    // successfully, returning a false boolean value as
    // this function result.
    return(false);
}

/**
 * [vimeoactivity_update_video]
 *
 * @param stdclass $video
 * @return boolean
 */
function vimeoactivity_update_video(stdclass $video) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Before trying to persist the supplied video within
    // the database, validating its attributes and making
    // sure they are within the required parameters.
    if (vimeoactivity_validate_video($video)) {
        $video->timemodified = time();

        // Compiling the required command
        // to persist the supplied object
        // within the database.
        $values = ['id' => $video->id, 'course' => $video->course,
                   'name' => $video->name, 'video' => $video->video,
                   'color' => $video->color, 'intro' => $video->intro,
                   'introformat' => $video->introformat, 'autoplay' => $video->autoplay,
                   'autoloop' => $video->autoloop, 'popupopen' => $video->popupopen,
                   'popupwidth' => $video->popupwidth, 'popupheight' => $video->popupheight,
                   'completionenable' => $video->completionenable, 'completionprogress' => $video->completionprogress,
                   'visible' => $video->visible, 'timecreated' => $video->timecreated, 'timemodified' => $video->timemodified];

        // Executing the required command
        // to persist the supplied object
        // within the database.
        if ($result = $DB->update_record_raw('vimeoactivity', $values)) {
            // Because we were able to execute this operation
            // completely and successfully, returning a true
            // boolean value as this function result.
            return(true);
        }
    }

    // Because we were unable to execute this operation
    // successfully, returning a false boolean value as
    // this function result.
    return(false);
}

/**
 * [vimeoactivity_validate_video]
 *
 * @param stdclass $video
 * @return boolean
 */
function vimeoactivity_validate_video(stdclass $video) {
    // Normalizing the loaded object
    // attributes and making sure they
    // are within the required parameters.
    $video->id = isset($video->id) ? max(0, (integer)$video->id) : 0;
    $video->course = isset($video->course) ? max(0, (integer)$video->course) : 0;
    $video->name = isset($video->name) ? trim((string)$video->name) : '';
    $video->video = isset($video->video) ? trim((string)$video->video) : '';
    $video->color = isset($video->color) ? strtolower(trim((string)$video->color)) : '';
    $video->intro = isset($video->intro) ? trim((string)$video->intro) : '';
    $video->introformat = isset($video->introformat) ? max(0, (integer)$video->introformat) : 0;
    $video->autoplay = isset($video->autoplay) ? (boolean)$video->autoplay : false;
    $video->autoloop = isset($video->autoloop) ? (boolean)$video->autoloop : false;
    $video->popupopen = isset($video->popupopen) ? (boolean)$video->popupopen : false;
    $video->popupwidth = isset($video->popupwidth) ? max(0, (integer)$video->popupwidth) : 640;
    $video->popupheight = isset($video->popupheight) ? max(0, (integer)$video->popupheight) : 360;
    $video->completionenable = isset($video->completionenable) ? (boolean)$video->completionenable : false;
    $video->completionprogress = isset($video->completionprogress) ? max(0, (integer)$video->completionprogress) : 0;
    $video->visible = isset($video->visible) ? (boolean)$video->visible : true;
    $video->timecreated = isset($video->timecreated) ? max(0, (integer)$video->timecreated) : 0;
    $video->timemodified = isset($video->timemodified) ? max(0, (integer)$video->timemodified) : 0;
    if ($video->completionenable == false) {
        $video->completionprogress = 0;
    }
    $video->errors = [];

    if (empty($video->course)) {
        $video->errors['course'] = get_string('message_invalid_course', 'mod_vimeoactivity');
    }

    if (empty($video->name)) {
        $video->errors['name'] = get_string('message_invalid_name', 'mod_vimeoactivity');
    }

    if (empty($video->video)) {
        $video->errors['video'] = get_string('message_invalid_video', 'mod_vimeoactivity');
    }

    // A successful validation is
    // a validation without errors.
    return(empty($video->errors));
}

/**
 * [vimeoactivity_render_video]
 *
 * @param stdclass $video
 * @param boolean $styles
 * @param boolean $scripts
 * @param boolean $popup
 * @return string
 */
function vimeoactivity_render_video(stdclass $video, $styles = true, $scripts = true, $popup = false) {
    // Importing all the required global
    // objects into this function scope.
    global $COURSE, $USER, $CFG;

    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $courseid = isset($COURSE->id) ? max(0, (integer)$COURSE->id) : 0;
    $userid = isset($USER->id) ? max(0, (integer)$USER->id) : 0;

    // Instantiating the temporary variable that
    // will store this video rendering results.
    $output = '';

    if ($styles == true) {
        $output .= '<link href="'.$CFG->wwwroot.'/mod/vimeoactivity/style.css" rel="stylesheet" media="all"/>'."\n";
    }

    if ($scripts == true) {
        $output .= '<script type="text/javascript" src="'.$CFG->wwwroot.'/mod/vimeoactivity/script.js"></script>'."\n";
    }

    if ($video->color <> '') {
        $output .= '<div id="mod-vimeoactivity-'.
                    $video->id.'-block" class="mod-vimeoactivity-block" style="background-color:#'.
                    $video->color.'">';
        $output .= '&nbsp;';
        $output .= '</div>'."\n";
    } else {
        $output .= '<div id="mod-vimeoactivity-'.$video->id.'-block" class="mod-vimeoactivity-block">';
        $output .= '&nbsp;';
        $output .= '</div>'."\n";
    }

    if ($popup == true) {
        $output .= '<div id="mod-vimeoactivity-'.$video->id.'" class="mod-vimeoactivity-video-popup"></div>'."\n";
    } else {
        $output .= '<div id="mod-vimeoactivity-'.$video->id.'" class="mod-vimeoactivity-video"></div>'."\n";
    }

    $output .= '<script type="text/javascript">'."\n";
    $output .= 'var options={'."\n";

    if (is_numeric($video->video)) {
        $output .= ' id:'.$video->video.','."\n";
    } else {
        $output .= ' url:"'.$video->video.'",'."\n";
    }

    if ($video->color <> '') {
        $output .= ' color:"'.$video->color.'",'."\n";
    }

    if ($video->autoplay == true) {
        $output .= ' autoplay:true,'."\n";
    } else {
        $output .= ' autoplay:false,'."\n";
    }

    if ($video->autoloop == true) {
        $output .= ' loop:true,'."\n";
    } else {
        $output .= ' loop:false,'."\n";
    }
    $output .= ' title:true,'."\n";
    $output .= ' byline:false};'."\n";
    $output .= 'var vimeo_'.$video->id.'_player = new Vimeo.Player("mod-vimeoactivity-'.$video->id.'", options);'."\n";
    $output .= 'var vimeo_'.$video->id.'_progress = 0;'."\n";
    $output .= 'vimeo_'.$video->id.'_player.on("timeupdate", function(data){'."\n";
    $output .= 'var vimeo_'.$video->id.'_partial = Math.round(data.percent * 100);'."\n";
    $output .= 'if (vimeo_'.$video->id.'_partial > vimeo_'.$video->id.'_progress){'."\n";
    $output .= ' vimeo_'.$video->id.'_progress = vimeo_'.$video->id.'_partial;'."\n";
    $output .= ' if (window.XMLHttpRequest) {'."\n";
    $output .= '  request = new XMLHttpRequest();'."\n";
    $output .= ' } else {'."\n";
    $output .= '  request = new ActiveXObject("Microsoft.XMLHTTP");'."\n";
    $output .= ' }'."\n";
    $output .= ' request.open("GET","'.$CFG->wwwroot.'/mod/vimeoactivity/ping.php?courseid='.$courseid;
    $output .= '&videoid='.$video->id.'&userid='.$userid.'&value="+vimeo_'.$video->id.'_partial, true);'."\n";
    $output .= ' request.send();'."\n";
    $output .= '}'."\n";
    $output .= '});'."\n";
    $output .= '</script>'."\n";
    // Returning the compiled Vimeo
    // video as this method's result.
    return($output);
}

/**
 * [vimeoactivity_delete_video]
 *
 * @param integer $videoid
 * @return boolean
 */
function vimeoactivity_delete_video($videoid) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $videoid = max(0, (integer)$videoid);

    // Verifying if the supplied identifier is valid
    // (greater than zero) and, if false, there is no
    // need to even touch the database and returning
    // a false boolean value as this function result.
    if ($videoid < 1) {
        return(false);
    }

    // Removing the requested object from the
    // database and returning a boolean value
    // as this function result.
    if ($DB->delete_records('vimeoactivity', ['id' => $videoid])) {
        // Removing all the related
        // objects from the database.
        $DB->delete_records('vimeoactivity_progress', ['video' => $videoid]);

        // Because we were able to execute this operation
        // completely and successfully, returning a true
        // boolean value as this function result.
        return(true);
    }

    // Because we were unable to execute this operation
    // successfully, returning a false boolean value as
    // this function result.
    return(false);
}

/**
 * [vimeoactivity_count_videos]
 *
 * @param integer $courseid
 * @return integer
 */
function vimeoactivity_count_videos($courseid) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $courseid = max(0, (integer)$courseid);

    // Verifying if the supplied identifier is valid
    // (greater than zero) and, if false, there is no
    // need to even touch the database and returning
    // a zero integer value as this function result.
    if ($courseid < 1) {
        return(0);
    }

    // Compiling the required command
    // to count the requested objects
    // from the database.
    $sql = "SELECT COUNT(id) ".
            "FROM {vimeoactivity} ".
            "WHERE course=?";

    // Executing the required command
    // to count the requested objects
    // from the database.
    if ($result = $DB->get_field_sql($sql, [$courseid])) {
        return((integer)$result);
    }

    // Because we were unable to execute this
    // operation successfully, returning a zero
    // integer value as this function result.
    return(0);
}

/**
 * [vimeoactivity_fetch_videos]
 *
 * @param integer $courseid
 * @return array
 */
function vimeoactivity_fetch_videos($courseid) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $courseid = max(0, (integer)$courseid);
    $results = [];

    // Verifying if the supplied identifier is valid
    // (greater than zero) and, if false, there is no
    // need to even touch the database and returning
    // an empty array value as this function result.
    if ($courseid < 1) {
        return([]);
    }

    // Compiling the required command
    // to fetch the requested objects
    // from the database.
    $sql = "SELECT id,".
                "course,".
                "name,".
                "video,".
                "color,".
                "intro,".
                "introformat,".
                "autoplay,".
                "autoloop,".
                "popupopen,".
                "popupwidth,".
                "popupheight,".
                "completionenable,".
                "completionprogress,".
                "visible,".
                "timecreated,".
                "timemodified ".
            "FROM {vimeoactivity} ".
            "WHERE course=? ".
            "ORDER BY id ASC";

    // Executing the required command
    // to fetch the requested objects
    // from the database.
    if ($records = $DB->get_records_sql($sql, [$courseid])) {
        // Normalizing the loaded objects
        // attributes and making sure they
        // are within the required ranges.
        foreach ($records as $record) {
            $result = new stdclass();
            $result->id = max(0, (integer)$record->id);
            $result->course = max(0, (integer)$record->course);
            $result->name = trim((string)$record->name);
            $result->video = trim((string)$record->video);
            $result->color = trim((string)$record->color);
            $result->intro = trim((string)$record->intro);
            $result->introformat = max(0, (integer)$record->introformat);
            $result->autoplay = (boolean)$record->autoplay;
            $result->autoloop = (boolean)$record->autoloop;
            $result->popupopen = (boolean)$record->popupopen;
            $result->popupwidth = max(0, (integer)$record->popupwidth);
            $result->popupheight = max(0, (integer)$record->popupheight);
            $result->completionenable = (boolean)$record->completionenable;
            $result->completionprogress = max(0, (integer)$record->completionprogress);
            $result->visible = (boolean)$record->visible;
            $result->timecreated = max(0, (integer)$record->timecreated);
            $result->timemodified = max(0, (integer)$record->timemodified);
            $results[$result->id] = $result;
        }
    }

    // Returning the an array
    // with all loaded objects
    // as this function result.
    return($results);
}

/**
 * [vimeoactivity_fetch_progress]
 *
 * @param integer $userid
 * @param integer $videoid
 * @return object|null
 */
function vimeoactivity_fetch_progress($userid, $videoid) {
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
        $result = new stdclass();
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
function vimeoactivity_save_progress($userid, $videoid, $value) {
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

/**
 * [vimeoactivity_delete_progress]
 *
 * @param integer $userid
 * @param integer $videoid
 * @return boolean
 */
function vimeoactivity_delete_progress($userid, $videoid) {
    // Importing all the required global
    // objects into this function scope.
    global $DB;

    // Normalizing the supplied arguments and making
    // sure they are within the required parameters.
    $userid = max(0, (integer)$userid);
    $videoid = max(0, (integer)$videoid);

    // Verifying if the supplied identifiers are valid
    // (greater than zero) and, if not, there is no need
    // to even touch the database and returning a false
    // boolean value as this function result.
    if ($userid < 1 || $videoid < 1) {
        return(false);
    }

    // Removing the requested object from the
    // database and returning a boolean value
    // as this function result.
    return((boolean)$DB->delete_records('vimeoactivity_progress', ['user' => $userid, 'video' => $videoid]));
}

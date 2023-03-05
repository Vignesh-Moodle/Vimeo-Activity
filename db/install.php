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
 * This function is responsible for executing the
 * required routines when installing this plug-in.
 *
 * @return boolean
 */
function xmldb_vimeoactivity_install() {
    return(true);
}

/**
 * This function is responsible for executing the
 * required routines when recovering this plug-in.
 *
 * @return boolean
 */
function xmldb_vimeoactivity_install_recovery() {
    return(true);
}

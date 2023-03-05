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
defined('MOODLE_INTERNAL') || die();

// Loading all libraries, classes
// and functions required by this
// class execution.
require_once(__DIR__.'/../../course/moodleform_mod.php');
require_once(__DIR__.'/locallib.php');

/**
 * This class is responsible for defining and validating this plug-in add and edit forms.
 *
 * @license http://www.gnu.org/copyleft/gpl.html
 */
class mod_vimeoactivity_mod_form extends moodleform_mod {
    /**
     * This method is responsible for
     * registering this form inputs.
     *
     * @return void
     */
    public function definition() {
        $this->_form->addElement('header', 'general', get_string('general', 'form'));
        $this->_form->addElement('hidden', 'id');
        $this->_form->setType('id', PARAM_INT);

        $this->_form->addElement('text', 'name', get_string('label_name', 'mod_vimeoactivity'), ['size' => '240']);
        $this->_form->setType('name', PARAM_TEXT);
        $this->_form->addRule('name', null, 'required', null, 'client');
        $this->_form->addRule('name', get_string('maximumchars', '', 240), 'maxlength', 240, 'client');
        $this->_form->addHelpButton('name', 'label_name', 'mod_vimeoactivity');

        $this->_form->addElement('text', 'video', get_string('label_video', 'mod_vimeoactivity'), ['size' => '240']);
        $this->_form->setType('video', PARAM_TEXT);
        $this->_form->addRule('video', null, 'required', null, 'client');
        $this->_form->addRule('video', get_string('maximumchars', '', 240), 'maxlength', 240, 'client');
        $this->_form->addHelpButton('video', 'label_video', 'mod_vimeoactivity');

        $this->standard_intro_elements();

        $this->_form->addElement('text', 'color',  get_string('label_color', 'mod_vimeoactivity'),
            'maxlength="6" size="10"');
        $this->_form->setType('color', PARAM_TEXT);
        $this->_form->addHelpButton('color', 'label_color', 'mod_vimeoactivity');

        $this->_form->addElement('select', 'autoplay', get_string('label_autoplay', 'mod_vimeoactivity'),
            [0 => get_string('label_no', 'mod_vimeoactivity'), 1 => get_string('label_yes', 'mod_vimeoactivity')]);
        $this->_form->setType('autoplay', PARAM_INT);
        $this->_form->addHelpButton('autoplay', 'label_autoplay', 'mod_vimeoactivity');

        $this->_form->addElement('select', 'autoloop', get_string('label_autoloop', 'mod_vimeoactivity'),
            [0 => get_string('label_no', 'mod_vimeoactivity'), 1 => get_string('label_yes', 'mod_vimeoactivity')]);
        $this->_form->setType('autoloop', PARAM_INT);
        $this->_form->addHelpButton('autoloop', 'label_autoloop', 'mod_vimeoactivity');

        $this->_form->addElement('select', 'popupopen', get_string('label_popupopen', 'mod_vimeoactivity'),
            [0 => get_string('label_no', 'mod_vimeoactivity'), 1 => get_string('label_yes', 'mod_vimeoactivity')]);
        $this->_form->setType('popupopen', PARAM_INT);
        $this->_form->addHelpButton('popupopen', 'label_popupopen', 'mod_vimeoactivity');

        $this->_form->addElement('text', 'popupwidth', get_string('label_popupwidth', 'mod_vimeoactivity'),
            'maxlength="4" size="10"');
        $this->_form->setType('popupwidth', PARAM_INT);
        $this->_form->addHelpButton('popupwidth', 'label_popupwidth', 'mod_vimeoactivity');
        $this->_form->disabledIf('popupwidth', 'popupopen', 'eq', 0);
        $this->_form->setDefault('popupwidth', 640);

        $this->_form->addElement('text', 'popupheight', get_string('label_popupheight', 'mod_vimeoactivity'),
            'maxlength="4" size="10"');
        $this->_form->setType('popupheight', PARAM_INT);
        $this->_form->addHelpButton('popupheight', 'label_popupheight', 'mod_vimeoactivity');
        $this->_form->disabledIf('popupheight', 'popupopen', 'eq', 0);
        $this->_form->setDefault('popupheight', 360);

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Add elements for setting
     * the custom completion rules.
     *
     * @category completion
     * @return array List of added element names, or names of wrapping group elements.
     */
    public function add_completion_rules() {
        $group = [
            $this->_form->createElement('select', 'completionprogress', ' ', [0 => '0%',
                                                                              10 => '10%',
                                                                              20 => '20%',
                                                                              30 => '30%',
                                                                              40 => '40%',
                                                                              50 => '50%',
                                                                              60 => '60%',
                                                                              70 => '70%',
                                                                              80 => '80%',
                                                                              90 => '90%',
                                                                              100 => '100%']),
        $this->_form->createElement('checkbox', 'completionenable', ' ', get_string('label_enable', 'mod_vimeoactivity'))
        ];
        $this->_form->setType('completionprogress', PARAM_INT);
        $this->_form->addGroup($group, 'completionprogress', get_string('label_completion', 'mod_vimeoactivity'), null, false);
        $this->_form->addHelpButton('completionprogress', 'label_completion', 'mod_vimeoactivity');
        $this->_form->disabledIf('completionprogress', 'completionenable', 'notchecked');

        return ['completionprogress'];
    }

    /**
     * Called during validation to see
     * whether some module-specific
     * completion rules are selected.
     *
     * @param array $data Input data not yet validated.
     * @return bool True if one or more rules is enabled, false if none are.
     */
    public function completion_rule_enabled($data) {
        $data['completionenable'] = intval($data['completionenable']);
        $data['completionprogress'] = intval($data['completionprogress']);
        return($data['completionenable'] == 1 &&
                $data['completionprogress'] >= 0 &&
                $data['completionprogress'] <= 100);
    }
    /**
     * Allows module to modify the data returned by form get_data().
     * This method is also called in the bulk activity completion form.
     *
     * Only available on moodleform_mod.
     *
     * @param stdClass $data the form data to be modified.
     */
    // public function data_postprocessing($data) {
    //     parent::data_postprocessing($data);
    //     if (!empty($data->completionunlocked)) {
    //         // Turn off completion settings if the checkboxes aren't ticked.
    //         $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;

    //         if (!$autocompletion || empty($data->completionprogress)) {
    //             $data->completionprogress = null;
    //         }
    //     }
    // }
    /**
     * This function is responsible for validating
     * the supplied Vimeo video data and returning
     * all the validation errors as an array.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        // Normalizing the supplied data and files parameters
        // and making sure they are within the required ranges,
        // or more precisely at least an array.
        $data = (array)$data;
        $files = (array)$files;

        // Using the default validation errors
        // rules to validate the supplied data
        // and capturing the results.
        $errors = parent::validation($data, $files);

        // Transforming the supplied data
        // array into an object required by
        // the internal validation function.
        $video = (object)$data;

        // Validating the supplied Vimeo video object
        // using this module validation function and
        // merging any found validation errors into
        // the previous validation errors array.
        if (vimeoactivity_validate_video($video) == false) {
            $errors = array_merge($errors, $video->errors);
        }

        // Returning the validation errors
        // array as this function result.
        return($errors);
    }
}

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
 * This file defines the setting form for the quiz archive report.
 *
 * @package   quiz_archive
 * @copyright 2018 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use mod_quiz\local\reports\attempts_report_options_form;

defined('MOODLE_INTERNAL') || die();

// This work-around is required until Moodle 4.2 is the lowest version we support.
if (class_exists('\mod_quiz\local\reports\attempts_report_options_form')) {
    class_alias('\mod_quiz\local\reports\attempts_report_options_form', '\quiz_archive_settings_form_parent_class_alias');
} else {
    require_once($CFG->dirroot . '/mod/quiz/report/attemptsreport_form.php');
    class_alias('\mod_quiz_attempts_report_form', '\quiz_archive_settings_form_parent_class_alias');
}


/**
 * Quiz archive report settings form.
 *
 * @package   quiz_archive
 * @copyright 2018 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quiz_archive_settings_form extends quiz_archive_settings_form_parent_class_alias {

    /**
     * Definition of our form. Overriding parent method, because our form is much simpler
     * and does not have multiple sections.
     *
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'preferencesuser', get_string('reportdisplayoptions', 'quiz'));

        $this->standard_preference_fields($mform);

        $mform->addElement('submit', 'submitbutton', get_string('showreport', 'quiz'));
    }

    /**
     * Override parent function. Our form currently only has two checkboxes, so their
     * data is always valid or could at least be converted to valid values.
     *
     * @param mixed $data
     * @param mixed $files
     * @return array
     */
    public function validation($data, $files) {
        return [];
    }

    /**
     * Add the preference fields that we offer.
     *
     * @param MoodleQuickForm $mform the form
     * @return void
     */
    protected function standard_preference_fields(MoodleQuickForm $mform) {
        $mform->addElement('advcheckbox', 'showhistory', get_string('includehistory', 'quiz_archive'));
        $mform->addElement('advcheckbox', 'showright', get_string('includecorrectanswer', 'quiz_archive'));
    }

}

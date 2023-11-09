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
 * Class to store the options for a {@see quiz_archive_report}.
 *
 * @package   quiz_archive
 * @copyright 2018 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_quiz\local\reports\attempts_report_options;

defined('MOODLE_INTERNAL') || die();

// This work-around is required until Moodle 4.2 is the lowest version we support.
if (class_exists('\mod_quiz\local\reports\attempts_report_options')) {
    class_alias('\mod_quiz\local\reports\attempts_report_options', '\quiz_archive_options_parent_class_alias');
} else {
    require_once($CFG->dirroot . '/mod/quiz/report/attemptsreport_options.php');
    class_alias('\mod_quiz_attempts_report_options', '\quiz_archive_options_parent_class_alias');
}

/**
 * Class to store the options for a {@see quiz_archive_report}.
 *
 * @package   quiz_archive
 * @copyright 2018 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quiz_archive_options extends quiz_archive_options_parent_class_alias {

    /** @var bool whether to show the correct response. */
    public $showright = true;

    /** @var bool whether to show the history. */
    public $showhistory = true;

    /**
     * Constructor.
     * @param string $mode which report these options are for.
     * @param object $quiz the settings for the quiz being reported on.
     * @param object $cm the course module objects for the quiz being reported on.
     * @param object $course the course settings for the coures this quiz is in.
     */
    public function __construct($mode, $quiz, $cm, $course) {
        $this->mode   = $mode;
        $this->quiz   = $quiz;
        $this->cm     = $cm;
        $this->course = $course;
    }

    /**
     * Get the current value of the settings to pass to the settings form.
     */
    public function get_initial_form_data() {
        $toform = new stdClass();

        $toform->showright = $this->showright;
        $toform->showhistory = $this->showhistory;

        return $toform;
    }

    /**
     * Set the fields of this object from the form data.
     * @param object $fromform The data from $mform->get_data() from the settings form.
     */
    public function setup_from_form_data($fromform) {
        $this->showright = $fromform->showright;
        $this->showhistory = $fromform->showhistory;
    }

    /**
     * Set the fields of this object from the URL parameters.
     */
    public function setup_from_params() {
        $this->showright = optional_param('right', $this->showright, PARAM_BOOL);
        $this->showhistory = optional_param('history', $this->showhistory, PARAM_BOOL);
    }

    /**
     * Override parent method, because we do not have settings that are backed by
     * user-preferences.
     */
    public function setup_from_user_preferences() {
    }

    /**
     * Override parent method, because we do not have settings that are backed by
     * user-preferences.
     */
    public function update_user_preferences() {
    }

    /**
     * Override parent method, because our settings cannot be incompatible.
     */
    public function resolve_dependencies() {
    }
}

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
 * Class to store the options for a {@link quiz_archive_report}.
 *
 * @package   quiz_archive
 * @copyright 2018 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

/**
 * Class to store the options for a {@link quiz_archive_report}.
 *
 * @package   quiz_archive
 * @copyright 2018 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quiz_archive_options extends mod_quiz_attempts_report_options {

    /** @var string the report mode. */
    public $mode;

    /** @var object the settings for the quiz being reported on. */
    public $quiz;

    /** @var object the course module objects for the quiz being reported on. */
    public $cm;

    /** @var object the course settings for the course the quiz is in. */
    public $course;

    /**
     * @var array form field name => corresponding quiz_attempt:: state constant.
     */
    protected static $statefields = array(
        'stateinprogress' => quiz_attempt::IN_PROGRESS,
        'stateoverdue'    => quiz_attempt::OVERDUE,
        'statefinished'   => quiz_attempt::FINISHED,
        'stateabandoned'  => quiz_attempt::ABANDONED,
    );

    /**
     * @var string quiz_attempts_report::ALL_WITH or quiz_attempts_report::ENROLLED_WITH
     *      quiz_attempts_report::ENROLLED_WITHOUT or quiz_attempts_report::ENROLLED_ALL
     */
    public $attempts = quiz_attempts_report::ENROLLED_WITH;

    /** @var int the currently selected group. 0 if no group is selected. */
    public $group = 0;

    /**
     * @var array|null of quiz_attempt::IN_PROGRESS, etc. constants. null means
     *      no restriction.
     */
    public $states = array(quiz_attempt::IN_PROGRESS, quiz_attempt::OVERDUE,
        quiz_attempt::FINISHED, quiz_attempt::ABANDONED);

    /**
     * @var bool whether to show all finished attmepts, or just the one that gave
     *      the final grade for the user.
     */
    public $onlygraded = false;

    /** @var int Number of attempts to show per page. */
    public $pagesize = quiz_attempts_report::DEFAULT_PAGE_SIZE;

    /** @var string whether the data should be downloaded in some format, or '' to display it. */
    public $download = '';

    /** @var bool whether the current user has permission to see grades. */
    public $usercanseegrades;

    /** @var bool whether the report table should have a column of checkboxes. */
    public $checkboxcolumn = false;

    /** @var bool whether to show the question text columns. */
    public $showqtext = false;

    /** @var bool whether to show the students' response columns. */
    public $showresponses = true;

    /** @var bool whether to show the correct response columns. */
    public $showright = false;

    /** @var bool which try/tries to show responses from. */
    public $whichtries = question_attempt::LAST_TRY;

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

        $this->usercanseegrades = quiz_report_should_show_grades($quiz, context_module::instance($cm->id));
    }

    /**
     * Get the URL to show the report with these options.
     * @return moodle_url the URL.
     */
    public function get_url() {
        return new moodle_url('/mod/quiz/report.php', $this->get_url_params());
    }

    /**
     * Get the URL parameters required to show the report with these options.
     * @return array URL parameter name => value.
     */
    protected function get_url_params() {
        $params = array(
            'id'         => $this->cm->id,
            'mode'       => $this->mode,
            'attempts'   => $this->attempts,
            'onlygraded' => $this->onlygraded,
        );

        if ($this->states) {
            $params['states'] = implode('-', $this->states);
        }

        if (groups_get_activity_groupmode($this->cm, $this->course)) {
            $params['group'] = $this->group;
        }
        return $params;
    }

    /**
     * Get the current value of the settings to pass to the settings form.
     */
    public function get_initial_form_data() {
        $toform = parent::get_initial_form_data();
        $toform->qtext      = $this->showqtext;
        $toform->resp       = $this->showresponses;
        $toform->right      = $this->showright;
        if (quiz_allows_multiple_tries($this->quiz)) {
            $toform->whichtries = $this->whichtries;
        }

        return $toform;
    }

    /**
     * Set the fields of this object from the form data.
     * @param object $fromform The data from $mform->get_data() from the settings form.
     */
    public function setup_from_form_data($fromform) {
        parent::setup_from_form_data($fromform);

        $this->showqtext     = $fromform->qtext;
        $this->showresponses = $fromform->resp;
        $this->showright     = $fromform->right;
        if (quiz_allows_multiple_tries($this->quiz)) {
            $this->whichtries = $fromform->whichtries;
        }
    }

    /**
     * Set the fields of this object from the URL parameters.
     */
    public function setup_from_params() {
        parent::setup_from_params();

        $this->showqtext     = optional_param('qtext', $this->showqtext,     PARAM_BOOL);
        $this->showresponses = optional_param('resp',  $this->showresponses, PARAM_BOOL);
        $this->showright     = optional_param('right', $this->showright,     PARAM_BOOL);
        if (quiz_allows_multiple_tries($this->quiz)) {
            $this->whichtries    = optional_param('whichtries', $this->whichtries, PARAM_ALPHA);
        }
    }

    /**
     * Set the fields of this object from the user's preferences.
     * (For those settings that are backed by user-preferences).
     */
    public function setup_from_user_preferences() {
        parent::setup_from_user_preferences();

        $this->showqtext     = get_user_preferences('quiz_report_archive_qtext', $this->showqtext);
        $this->showresponses = get_user_preferences('quiz_report_archive_resp',  $this->showresponses);
        $this->showright     = get_user_preferences('quiz_report_archive_right', $this->showright);
        if (quiz_allows_multiple_tries($this->quiz)) {
            $this->whichtries    = get_user_preferences('quiz_report_archive_which_tries', $this->whichtries);
        }
    }

    /**
     * Update the user preferences so they match the settings in this object.
     * (For those settings that are backed by user-preferences).
     */
    public function update_user_preferences() {
        parent::update_user_preferences();

        set_user_preference('quiz_report_archive_qtext', $this->showqtext);
        set_user_preference('quiz_report_archive_resp',  $this->showresponses);
        set_user_preference('quiz_report_archive_right', $this->showright);
        if (quiz_allows_multiple_tries($this->quiz)) {
            set_user_preference('quiz_report_archive_which_tries', $this->whichtries);
        }
    }

    /**
     * Check the settings, and remove any 'impossible' combinations.
     */
    public function resolve_dependencies() {
        parent::resolve_dependencies();

        if (!$this->showqtext && !$this->showresponses && !$this->showright) {
            // We have to show at least something.
            $this->showresponses = true;
        }

        // We only want to show the checkbox to delete attempts
        // if the user has permissions and if the report mode is showing attempts.
        $this->checkboxcolumn = has_capability('mod/quiz:deleteattempts', context_module::instance($this->cm->id))
                && ($this->attempts != quiz_attempts_report::ENROLLED_WITHOUT);
    }
}

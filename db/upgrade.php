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
 * Update script for the quiz archive report.
 * @package   quiz_archive
 * @copyright 2020 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade function
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_quiz_archive_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2020012000) {
        // Update quiz_reports displayorder to 2000.
        $updatesql = "
            UPDATE {quiz_reports}
               SET displayorder = 2000
             WHERE name = 'archive'";
        $DB->execute($updatesql);

        upgrade_plugin_savepoint(true, 2020012000, 'quiz', 'archive');
    }

    return true;
}

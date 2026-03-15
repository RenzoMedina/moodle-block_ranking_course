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
 * Block ranking_course is defined here.
 *
 * @package     block_ranking_course
 * @copyright   2026 Renzo Medina <medinast30@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ranking_course extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_ranking_course');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        global $OUTPUT, $CFG, $COURSE, $USER, $DB;
        require_once($CFG->dirroot . '/lib/gradelib.php');
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            $courseitem = grade_item::fetch_course_item($COURSE->id);
            $grademax = (float)$courseitem->grademax;
            $ranking = [];
            $sql = "SELECT gg.userid, gg.finalgrade
                    FROM {grade_grades} gg
                    WHERE gg.itemid = :itemid
                    AND gg.finalgrade IS NOT NULL
                    ORDER BY gg.finalgrade DESC";

            $allgrades = $DB->get_records_sql($sql, ['itemid' => $courseitem->id]);
            foreach ($allgrades as $grade) {
                $student = $DB->get_record('user', ['id' => $grade->userid], 'firstname, lastname');
                $percentage = ($grademax > 0) ? round(($grade->finalgrade / $grademax) * 100, 2) : 0;
                $ranking[] = [
                    'userid'   => $grade->userid,
                    'fullname' => fullname($student),
                    'rank' => $percentage . '%',
                ];
            }
            $context = context_course::instance($COURSE->id);
            $isteacher = has_capability('moodle/grade:viewall', $context);
            $isadmin = is_siteadmin($USER->id);
            $showusers = !empty($this->config->activeusers) ? $this->config->activeusers : 0;
            $beststudents = array_slice($ranking, 0, 5);
            $medals = ['🥇' , '🥈', '🥉', '🎖️', '🏅'];
            foreach ($beststudents as $index => $student) {
                if ($index > 0  && $beststudents[$index]['rank'] === $beststudents[$index - 1]['rank']) {
                    $position = $beststudents[$index - 1]['position'];
                } else {
                    $position = $index + 1;
                }
                $medal = $medals[$index] ?? ($position . 'º');
                $beststudents[$index]['rank']          = $position . ' ' . $medal;
                $beststudents[$index]['position']      = $position;
                $beststudents[$index]['iscurrentuser'] = $iscurrentuser;
                if ($isteacher || $isadmin) {
                    $beststudents[$index]['showname'] = true;
                } else if ($showusers) {
                    $beststudents[$index]['showname'] = true;
                } else {
                    $beststudents[$index]['showname'] = $iscurrentuser;
                    if (!$iscurrentuser) {
                        $beststudents[$index]['fullname'] = get_string('hiddenuser', 'block_ranking_course');
                    }
                }
            }

            $templatedata = [
                'users' => !empty($beststudents) ? $beststudents : [],
            ];
            $this->content->text = $OUTPUT->render_from_template('block_ranking_course/main', $templatedata);
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_ranking_course');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return [
            'course-view' => true,
        ];
    }

    /**
     * Performs a self-test to check if the block is working correctly.
     * @return bool
     */
    public function self_test() {
        return true;
    }
}

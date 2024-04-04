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
 * Display all badges available
 *
 * @package    core
 * @subpackage badges
 * @copyright  2012 onwards Totara Learning Solutions Ltd {@link http://www.totaralms.com/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Yuliya Bozhko <yuliya.bozhko@totaralms.com>
 */

require_once(__DIR__ . '/../config.php');
require_once($CFG->libdir . '/badgeslib.php');
require_once($CFG->libdir . '/filelib.php');

$PAGE->set_context(context_system::instance());
$output = $PAGE->get_renderer('core', 'badges');

$PAGE->set_url('/badges/allbadges.php');
$PAGE->set_pagelayout('base');
$PAGE->set_title('Todos os Emblemas');
$PAGE->set_heading('Todos os Emblemas');


$badges = $DB->get_records('badge');

$PAGE->navbar->add('Todos os Emblemas');


if (!empty($badges)) {
    // Paginate the badges
    $perpage = 20;
    $page = optional_param('page', 0, PARAM_INT);
    $start = $page * $perpage;

    $totalbadges = count($badges);
    $badges = array_slice($badges, $start, $perpage);

    // Start the table
    $table = new html_table();
    $table->head = array(
        get_string('badgeimage', 'badges'),
        get_string('name'),
        get_string('description'),
    );
    // 03/04/2024 Gera o Json para o Wordpress
    $format = $_GET['format'];

    if($format=='json'){
        $json = json_encode($badges);
        header('Content-Type: application/json');
        print_r($json);
        die();
    }
    //
    echo $OUTPUT->header();

    //
    foreach ($badges as $badge) {
        $row = new html_table_row();

        // Get badge image URL
        $badgeimage = $CFG->wwwroot . '/pluginfile.php/1/' . $badge->courseid . '/badges/badgeimage/' . $badge->id . '/f3';
        $badgeimage = html_writer::tag('img', '', array('src' => $badgeimage, 'alt' => $badge->name, 'style' => 'max-width: 150px;'));
        $row->cells[] = $badgeimage;

        // Add badge name
        $row->cells[] = $badge->name;

        // Add badge description
        $row->cells[] = $badge->description;

        $table->data[] = $row;
    }

    // Print the table
    echo html_writer::table($table);

    // Print pagination
    echo $OUTPUT->paging_bar($totalbadges, $page, $perpage, $PAGE->url);
} else {
    echo $OUTPUT->notification(get_string('nobadges', 'badges'));
}

echo $OUTPUT->footer();

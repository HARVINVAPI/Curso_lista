<?php
/**
 * English language strings for Curso Lista plugin
 * 
 * @package    block_curso_lista
 * @author     Harvinvapi
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.instagram.com/Harvinvapi
 * @link       https://www.linkedin.com/in/harvinvapi
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'My Courses';
$string['curso_lista'] = 'Curso Lista';
$string['curso_lista:addinstance'] = 'Add a new My Courses block';
$string['curso_lista:myaddinstance'] = 'Add a new My Courses block to My Moodle';

// Basic configuration
$string['blocksettings'] = 'Block settings';
$string['config_title'] = 'Custom block title';
$string['config_body'] = 'Custom description';

// Course title configuration
$string['title_color'] = 'Course title color';
$string['title_color_help'] = 'Color to be applied to course titles. Use hexadecimal format (#000000) for best results.';
$string['title_size'] = 'Title size';
$string['title_size_help'] = 'Font size for course titles.';
$string['title_weight'] = 'Title weight';
$string['title_weight_help'] = 'Font weight for course titles.';

// Button configuration
$string['buttoncolor'] = 'Button color';
$string['buttoncolordesc'] = 'Choose the color for "Go to course" buttons. Use hexadecimal format (#FF0000) or CSS color names.';
$string['gradient_start'] = 'Gradient start color';
$string['gradient_start_desc'] = 'Starting color for button gradient (hexadecimal format: #FF0000)';
$string['gradient_end'] = 'Gradient end color';
$string['gradient_end_desc'] = 'Ending color for button gradient (hexadecimal format: #00FF00)';

// Content
$string['nocourses'] = 'You have no available courses at this time.';
$string['loginrequired'] = 'You must log in to view your courses.';
$string['error_loading_courses'] = 'Error loading courses. Please try again.';
$string['courseimage'] = 'Course image';
$string['button_text'] = 'Go to course';
$string['progress'] = 'Progress';
$string['progress_aria'] = 'Course progress: {$a} percent completed';

// Validation
$string['invalid_color'] = 'The entered color is not valid. Default color has been applied.';
$string['invalid_title_color'] = 'The title color is not valid. Default color has been applied.';

// Accessibility
$string['course_link_aria'] = 'Go to course: {$a}';
$string['progress_ring_aria'] = 'Course progress';

// Help configuration
$string['buttoncolor_help'] = 'Customize the color of the block buttons. Examples of valid formats:
<ul>
<li>Hexadecimal: #3498db, #FF5733</li>
<li>Short hexadecimal: #fff, #000</li>
<li>CSS names: red, blue, green, purple, orange</li>
</ul>';

$string['gradient_help'] = 'Configure a custom gradient for the buttons. Colors must be in hexadecimal format (#FF0000).';

// Donation and social media
$string['donation_title'] = '☕ Buy me a coffee';
$string['donation_description'] = 'If this plugin has been useful to you, consider making a small donation to support its development and maintenance.';
$string['donate_paypal'] = '💳 Donate with PayPal';
$string['follow_social'] = 'Follow me on social media:';
$string['website'] = '🌐 Educacion.lat';
$string['instagram'] = '📷 Instagram';
$string['linkedin'] = '💼 LinkedIn';
$string['developed_by'] = 'Developed with ❤️ by <strong>Harvin Vapi</strong>';
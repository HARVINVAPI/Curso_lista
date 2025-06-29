<?php
/**
 * Strings de idioma en español para el plugin Curso Lista
 * 
 * @package    block_curso_lista
 * @author     Harvinvapi
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.instagram.com/Harvinvapi
 * @link       https://www.linkedin.com/in/harvinvapi
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Mis Cursos';
$string['curso_lista'] = 'Curso Lista';
$string['curso_lista:addinstance'] = 'Agregar un nuevo bloque Mis Cursos';
$string['curso_lista:myaddinstance'] = 'Agregar un nuevo bloque Mis Cursos a Mi Moodle';

// Configuración básica
$string['blocksettings'] = 'Configuración del bloque';
$string['config_title'] = 'Título personalizado del bloque';
$string['config_body'] = 'Descripción personalizada';

// Configuración de títulos de cursos
$string['title_color'] = 'Color del título del curso';
$string['title_color_help'] = 'Color que se aplicará a los títulos de los cursos. Use formato hexadecimal (#000000) para mejores resultados.';
$string['title_size'] = 'Tamaño del título';
$string['title_size_help'] = 'Tamaño de la fuente para los títulos de los cursos.';
$string['title_weight'] = 'Peso del título';
$string['title_weight_help'] = 'Grosor de la fuente para los títulos de los cursos.';

// Configuración de botones
$string['buttoncolor'] = 'Color del botón';
$string['buttoncolordesc'] = 'Seleccione el color para los botones "Ir al curso". Use formato hexadecimal (#FF0000) o nombres de colores CSS.';
$string['gradient_start'] = 'Color inicial del gradiente';
$string['gradient_start_desc'] = 'Color inicial para el gradiente del botón (formato hexadecimal: #FF0000)';
$string['gradient_end'] = 'Color final del gradiente';
$string['gradient_end_desc'] = 'Color final para el gradiente del botón (formato hexadecimal: #00FF00)';

// Contenido
$string['nocourses'] = 'No tienes cursos disponibles en este momento.';
$string['loginrequired'] = 'Debes iniciar sesión para ver tus cursos.';
$string['error_loading_courses'] = 'Error al cargar los cursos. Por favor, intenta nuevamente.';
$string['courseimage'] = 'Imagen del curso';
$string['button_text'] = 'Ir al curso';
$string['progress'] = 'Progreso';
$string['progress_aria'] = 'Progreso del curso: {$a} por ciento completado';

// Validación
$string['invalid_color'] = 'El color ingresado no es válido. Se ha aplicado el color por defecto.';
$string['invalid_title_color'] = 'El color del título no es válido. Se ha aplicado el color por defecto.';

// Accesibilidad
$string['course_link_aria'] = 'Ir al curso: {$a}';
$string['progress_ring_aria'] = 'Progreso del curso';

// Configuración de ayuda
$string['buttoncolor_help'] = 'Personaliza el color de los botones del bloque. Ejemplos de formatos válidos:
<ul>
<li>Hexadecimal: #3498db, #FF5733</li>
<li>Hexadecimal corto: #fff, #000</li>
<li>Nombres CSS: red, blue, green, purple, orange</li>
</ul>';

$string['gradient_help'] = 'Configure un gradiente personalizado para los botones. Los colores deben estar en formato hexadecimal (#FF0000).';

// Donación y redes sociales
$string['donation_title'] = '☕ Invítame a un café';
$string['donation_description'] = 'Si este plugin te ha sido útil, considera hacer una pequeña donación para apoyar su desarrollo y mantenimiento.';
$string['donate_paypal'] = '💳 Donar con PayPal';
$string['follow_social'] = 'Sígueme en mis redes sociales:';
$string['website'] = '🌐 Educacion.lat';
$string['instagram'] = '📷 Instagram';
$string['linkedin'] = '💼 LinkedIn';
$string['developed_by'] = 'Desarrollado con ❤️ por <strong>Harvin Vapi</strong>';
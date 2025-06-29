<?php
/**
 * Plugin Curso Lista - Renderer
 * 
 * @package    block_curso_lista
 * @author     Harvinvapi
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.instagram.com/Harvinvapi
 * @link       https://www.linkedin.com/in/harvinvapi
 */

namespace block_curso_lista\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use moodle_url;
use core_course_list_element;
use cache;
use Exception;

class renderer extends plugin_renderer_base {

    /** @var array Cache para imágenes de cursos */
    private $image_cache = [];
    
    /** @var bool Modo debug */
    private $debug = false;

    /**
     * Constructor corregido
     */
    public function __construct(\moodle_page $page, $target) {
        parent::__construct($page, $target);
        $this->debug = debugging();
    }

    /**
     * Renderiza el contenido principal del bloque
     */
    public function render_block_content($courses, $config = null) {
        global $USER, $PAGE;

        // Solo incluir JS una vez por página
        static $js_included = false;
        if (!$js_included) {
            $PAGE->requires->js('/blocks/curso_lista/script.js');
            $PAGE->requires->js('/blocks/curso_lista/debug.js');
            $PAGE->requires->js('/blocks/curso_lista/fix_visual.js');
            $js_included = true;
        }
        
        // Generar CSS dinámico SIEMPRE que hay configuración personalizada
        // NOTA: Removemos el control estático para permitir regeneración tras cambios
        
        $data = [
            'courses' => $this->process_courses($courses),
            'context' => $this->detect_context(),
            'config' => $config
        ];

        // CSS dinámico SIEMPRE que hay configuración personalizada
        if ($config && $this->has_custom_config($config)) {
            error_log('CURSO LISTA RENDERER: Generando CSS dinámico - Config detectada');
            $custom_css = $this->generate_custom_css($config);
            return $custom_css . $this->render_from_template('block_curso_lista/block_content', $data);
        } else {
            error_log('CURSO LISTA RENDERER: Sin configuración personalizada - Usando CSS base');
        }

        return $this->render_from_template('block_curso_lista/block_content', $data);
    }

    /**
     * Procesa los cursos para el template
     */
    private function process_courses($courses) {
        $processed = [];
        
        foreach ($courses as $course) {
            $course_element = new core_course_list_element($course);
            $progress = $this->get_progress($course);
            
            $processed[] = [
                'courseid' => $course->id,
                'fullname' => $this->truncate_text(
                    format_string($course_element->get_formatted_fullname()), 
                    60
                ),
                'courseurl' => $this->get_course_url($course->id),
                'courseimage' => $this->courseimage($course_element),
                'progress' => $progress,
                'progress_circle_html' => $this->render_progress_circle($progress, $course->id),
                'courseimage_alt' => get_string('courseimage', 'block_curso_lista'),
                'progress_label' => get_string('progress_aria', 'block_curso_lista', $progress)
            ];
        }

        return $processed;
    }

    /**
     * Renderiza círculo de progreso - MEJORADO Y FUNCIONAL
     */
    private function render_progress_circle($progress, $courseid = null) {
        // Redondear progreso y asegurar límites
        $progress = max(0, min(100, round($progress)));
        $unique_id = 'course-' . ($courseid ?? uniqid());
        
        // Valores para SVG - Radio 26, circunferencia calculada
        $radius = 26;
        $circumference = 163.36; // 2 * Math.PI * 26 redondeado
        $offset = $circumference - ($progress / 100) * $circumference;

        // HTML optimizado para funcionalidad
        $html = '
        <div class="progress-container-80" 
             role="progressbar" 
             aria-valuenow="' . $progress . '" 
             aria-valuemin="0" 
             aria-valuemax="100"
             aria-label="Progreso del curso: ' . $progress . ' por ciento">
            
            <div class="progress-label-black">PROGRESO</div>
            
            <div class="progress-circle-container">
                <svg viewBox="0 0 64 64" width="64" height="64" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="gradient-' . $unique_id . '" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea; stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2; stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    
                    <!-- Círculo de fondo gris -->
                    <circle 
                        class="progress-bg" 
                        r="26" 
                        cx="32" 
                        cy="32"
                        fill="none"
                        stroke="#e0e0e0"
                        stroke-width="4"
                        opacity="1">
                    </circle>
                    
                    <!-- Círculo de progreso animado - ROTACIÓN CORREGIDA -->
                    <circle 
                        class="progress-fill" 
                        r="26" 
                        cx="32" 
                        cy="32" 
                        fill="none"
                        stroke="#764ba2"
                        stroke-width="4"
                        stroke-linecap="round"
                        transform="rotate(-90 32 32)"
                        data-progress="' . $progress . '"
                        data-circumference="' . $circumference . '"
                        data-offset="' . $offset . '"
                        style="stroke-dasharray: ' . $circumference . '; stroke-dashoffset: ' . $circumference . '; transition: none; transform-origin: center;">
                    </circle>
                </svg>
                
                <div class="progress-percentage">0%</div>
            </div>
        </div>';

        return $html;
    }

    /**
     * Detecta el contexto del bloque (sidebar, main, mobile)
     */
    private function detect_context() {
        global $PAGE;
        
        $body_classes = $PAGE->bodyclasses ?? '';
        $context = [
            'is_sidebar' => false,
            'is_mobile' => false,
            'region' => 'content'
        ];

        // Detectar sidebar
        $sidebar_indicators = ['side-pre', 'side-post', 'drawer'];
        foreach ($sidebar_indicators as $indicator) {
            if (strpos($body_classes, $indicator) !== false) {
                $context['is_sidebar'] = true;
                $context['region'] = $indicator;
                break;
            }
        }

        // Detectar móvil
        if (strpos($body_classes, 'mobile') !== false || 
            (isset($_SERVER['HTTP_USER_AGENT']) && 
             preg_match('/Mobile|Android|iPhone/', $_SERVER['HTTP_USER_AGENT']))) {
            $context['is_mobile'] = true;
        }

        return $context;
    }

    /**
     * Obtiene la imagen del curso con fallback
     */
    public function courseimage($courseelement) {
        global $CFG;

        // Check cache primero
        if (isset($this->image_cache[$courseelement->id])) {
            return $this->image_cache[$courseelement->id];
        }

        $image_url = $this->get_default_image();

        try {
            $context = \context_course::instance($courseelement->id);
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', 0, 'sortorder, id', false);

            foreach ($files as $file) {
                if ($file->is_valid_image() && $file->get_filesize() <= 2097152) {
                    $image_url = \moodle_url::make_pluginfile_url(
                        $context->id, 'course', 'overviewfiles', null, '/', $file->get_filename()
                    )->out(false);
                    break;
                }
            }
        } catch (Exception $e) {
            if ($this->debug) {
                error_log("CusoLista: Error getting image for course {$courseelement->id}: " . $e->getMessage());
            }
        }

        $this->image_cache[$courseelement->id] = $image_url;
        return $image_url;
    }

    /**
     * Obtiene la imagen por defecto
     */
    private function get_default_image() {
        global $CFG;
        
        $custom_image = $CFG->dirroot . '/blocks/curso_lista/pix/defaultcourseimage.png';
        if (file_exists($custom_image)) {
            return (new moodle_url('/blocks/curso_lista/pix/defaultcourseimage.png'))->out(false);
        }
        
        $svg_image = $CFG->dirroot . '/blocks/curso_lista/pix/defaultcourse.svg';
        if (file_exists($svg_image)) {
            return (new moodle_url('/blocks/curso_lista/pix/defaultcourse.svg'))->out(false);
        }
        
        return (new moodle_url('/theme/image.php/boost/core/1/course_defaultimage'))->out(false);
    }

    /**
     * Obtiene el progreso del curso
     */
    private function get_progress($course) {
        global $USER;
        
        $cache_key = "progress_{$course->id}_{$USER->id}";
        
        try {
            if (class_exists('cache')) {
                $cache = cache::make('block_curso_lista', 'progress');
                $cached = $cache->get($cache_key);
                if ($cached !== false) {
                    return intval($cached);
                }
            }

            $completion = new \completion_info($course);
            if (!$completion->is_enabled()) {
                return 0;
            }

            $progress = \core_completion\progress::get_course_progress_percentage($course, $USER->id);
            
            if (is_null($progress)) {
                $progress = $this->calculate_manual_progress($course, $USER->id);
            }

            $result = is_null($progress) ? 0 : max(0, min(100, intval($progress)));

            if (class_exists('cache') && isset($cache)) {
                $cache->set($cache_key, $result, 300);
            }

            return $result;

        } catch (Exception $e) {
            if ($this->debug) {
                error_log("CusoLista: Progress error for course {$course->id}: " . $e->getMessage());
            }
            return 0;
        }
    }

    /**
     * Calcula progreso manual como fallback
     */
    private function calculate_manual_progress($course, $userid) {
        try {
            $completion = new \completion_info($course);
            $activities = $completion->get_activities();
            
            if (empty($activities)) {
                return 0;
            }

            $total = 0;
            $completed = 0;

            foreach ($activities as $activity) {
                $data = $completion->get_data($activity, false, $userid);
                $total++;
                
                if ($data->completionstate == COMPLETION_COMPLETE || 
                    $data->completionstate == COMPLETION_COMPLETE_PASS) {
                    $completed++;
                }
            }

            return $total > 0 ? round(($completed / $total) * 100) : 0;

        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Genera CSS dinámico con especificidad mejorada
     */
    public function generate_custom_css($config) {
        $css = '<style type="text/css">';
        
        // Verificar configuración solo para debugging
        if (debugging()) {
            error_log('CURSO LISTA RENDERER: CSS generado correctamente');
        }
        
        // Extraer configuración priorizando valores SIN prefijo (que son los reales)
        $title_color = $this->sanitize_color($config->title_color ?? $config->config_title_color ?? '#2c3e50');
        $title_size = $config->title_size ?? $config->config_title_size ?? '1rem';
        $title_weight = $config->title_weight ?? $config->config_title_weight ?? '600';
        $color_type = $config->color_type ?? $config->config_color_type ?? 'gradient';
        $button_color = $this->sanitize_color($config->buttoncolor ?? $config->config_buttoncolor ?? '#3498db');
        $button_hover = $this->sanitize_color($config->buttoncolor_hover ?? $config->config_buttoncolor_hover ?? $this->darken_color($button_color, 20));
        $gradient_start = $this->sanitize_color($config->gradient_start ?? $config->config_gradient_start ?? '#667eea');
        $gradient_end = $this->sanitize_color($config->gradient_end ?? $config->config_gradient_end ?? '#764ba2');
        $gradient_start_hover = $this->sanitize_color($config->gradient_start_hover ?? $config->config_gradient_start_hover ?? $this->darken_color($gradient_start, 15));
        $gradient_end_hover = $this->sanitize_color($config->gradient_end_hover ?? $config->config_gradient_end_hover ?? $this->darken_color($gradient_end, 15));
        
        // LOG para debugging
        error_log('RENDERER CSS: title_color=' . $title_color . ', color_type=' . $color_type . ', button_color=' . $button_color . ', gradient_start=' . $gradient_start . ', gradient_end=' . $gradient_end);
        
        // CSS con máxima especificidad para anular completamente styles.css
        $css .= '
        /* === CONFIGURACIÓN DE TÍTULOS - MÁXIMA ESPECIFICIDAD === */
        body .block_curso_lista .block_curso_lista-info-element .course-block .course-name h3,
        body .block_curso_lista .course-block .course-name h3,
        body .block_curso_lista-info-element .course-block .course-name h3,
        .block_curso_lista .block_curso_lista-info-element .course-name h3,
        .block_curso_lista .course-name h3,
        .block_curso_lista-info-element .course-name h3 {
            color: ' . $title_color . ' !important;
            font-size: ' . $title_size . ' !important;
            font-weight: ' . $title_weight . ' !important;
            line-height: 1.2 !important;
            margin: 0 !important;
        }
        
        /* === CONFIGURACIÓN DE BOTONES - MÁXIMA ESPECIFICIDAD === */
        body .block_curso_lista .block_curso_lista-info-element .course-block .btn-course,
        body .block_curso_lista .course-block .btn-course,
        body .block_curso_lista-info-element .course-block .btn-course,
        .block_curso_lista .block_curso_lista-info-element .btn-course,
        .block_curso_lista .btn-course,
        .block_curso_lista-info-element .btn-course {
            background: ' . ($color_type === 'solid' ? $button_color : 'linear-gradient(135deg, ' . $gradient_start . ' 0%, ' . $gradient_end . ' 100%)') . ' !important;
            color: white !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 25px !important;
            text-decoration: none !important;
            display: inline-block !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            min-width: 100px !important;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2) !important;
            white-space: nowrap !important;
        }
        
        /* === HOVER PARA BOTONES - MÁXIMA ESPECIFICIDAD === */
        body .block_curso_lista .block_curso_lista-info-element .course-block .btn-course:hover,
        body .block_curso_lista .course-block .btn-course:hover,
        body .block_curso_lista-info-element .course-block .btn-course:hover,
        .block_curso_lista .block_curso_lista-info-element .btn-course:hover,
        .block_curso_lista .btn-course:hover,
        .block_curso_lista-info-element .btn-course:hover {
            background: ' . ($color_type === 'solid' ? $button_hover : 'linear-gradient(135deg, ' . $gradient_start_hover . ' 0%, ' . $gradient_end_hover . ' 100%)') . ' !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
            text-decoration: none !important;
            color: white !important;
        }
        
        /* === CONFIGURACIÓN DE PROGRESO - MÁXIMA ESPECIFICIDAD === */
        body .block_curso_lista .block_curso_lista-info-element .course-block .progress-fill,
        body .block_curso_lista .course-block .progress-fill,
        body .block_curso_lista-info-element .course-block .progress-fill,
        .block_curso_lista .block_curso_lista-info-element .progress-fill,
        .block_curso_lista .progress-fill,
        .block_curso_lista-info-element .progress-fill {
            stroke-width: 4 !important;';
        
        // Configurar color del progreso según el tipo
        if ($color_type === 'solid') {
            $css .= '
            stroke: ' . $button_color . ' !important;';
        } else {
            // Para gradientes, usar el color final como fallback
            $css .= '
            stroke: ' . $gradient_end . ' !important;';
        }
        
        $css .= '
        }
        
        /* === BACKGROUND Y CONTAINER - MÁXIMA ESPECIFICIDAD === */
        body .block_curso_lista .block_curso_lista-info-element .course-block,
        body .block_curso_lista-info-element .course-block,
        .block_curso_lista .block_curso_lista-info-element,
        .block_curso_lista-info-element {
            background: white !important;
            border-radius: 12px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
            margin-bottom: 15px !important;
            overflow: hidden !important;
        }
        
        /* === ANULAR VARIABLES CSS ESTÁTICAS - MÁXIMA ESPECIFICIDAD === */
        body .block_curso_lista *,
        .block_curso_lista * {
            --curso_lista-title-color: ' . $title_color . ' !important;
            --curso_lista-title-size: ' . $title_size . ' !important;
            --curso_lista-title-weight: ' . $title_weight . ' !important;
        }
        
        /* === FORZAR ANULACIÓN DE CUALQUIER OTRO CSS === */
        body div.block_curso_lista div.block_curso_lista-info-element div.course-block div.course-name h3 {
            color: ' . $title_color . ' !important;
            font-size: ' . $title_size . ' !important;
            font-weight: ' . $title_weight . ' !important;
        }
        
        body div.block_curso_lista div.block_curso_lista-info-element div.course-block a.btn-course {
            background: ' . ($color_type === 'solid' ? $button_color : 'linear-gradient(135deg, ' . $gradient_start . ' 0%, ' . $gradient_end . ' 100%)') . ' !important;
        }
        
        body div.block_curso_lista div.block_curso_lista-info-element div.course-block a.btn-course:hover {
            background: ' . ($color_type === 'solid' ? $button_hover : 'linear-gradient(135deg, ' . $gradient_start_hover . ' 0%, ' . $gradient_end_hover . ' 100%)') . ' !important;
        }';
        
        $css .= '
        </style>';
        
        return $css;
    }

    /**
     * Verifica si hay configuración personalizada - MEJORADO
     */
    private function has_custom_config($config) {
        // SIEMPRE generar CSS dinámico cuando hay configuración
        // Esto asegura que los cambios se reflejen inmediatamente
        if (!$config) {
            return false;
        }
        
        // Verificar si hay alguna configuración de estilo
        $style_configs = [
            'config_color_type', 'color_type',
            'config_buttoncolor', 'buttoncolor', 
            'config_gradient_start', 'gradient_start',
            'config_gradient_end', 'gradient_end',
            'config_title_color', 'title_color',
            'config_title_size', 'title_size',
            'config_title_weight', 'title_weight'
        ];
        
        foreach ($style_configs as $key) {
            if (isset($config->$key) && !empty($config->$key)) {
                return true; // Hay configuración de estilo, generar CSS
            }
        }
        
        // Si no hay configuración específica de estilo, aún generar CSS básico
        return true;
    }

    /**
     * Métodos de utilidad
     */
    private function get_course_url($courseid) {
        return (new moodle_url('/course/view.php', ['id' => $courseid]))->out(false);
    }

    private function truncate_text($text, $length = 50) {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        $truncated = mb_substr($text, 0, $length);
        $last_space = mb_strrpos($truncated, ' ');
        
        if ($last_space !== false && $last_space > $length * 0.7) {
            $truncated = mb_substr($truncated, 0, $last_space);
        }
        
        return $truncated . '...';
    }

    private function sanitize_color($color) {
        $color = strtolower(trim($color));
        
        // Hexadecimal de 6 caracteres
        if (preg_match('/^#[a-f0-9]{6}$/', $color)) {
            return $color;
        }
        
        // Hexadecimal de 3 caracteres - expandir a 6
        if (preg_match('/^#[a-f0-9]{3}$/', $color)) {
            return '#' . $color[1] . $color[1] . $color[2] . $color[2] . $color[3] . $color[3];
        }
        
        // Sin # al inicio - agregar si es válido
        if (preg_match('/^[a-f0-9]{6}$/', $color)) {
            return '#' . $color;
        }
        
        if (preg_match('/^[a-f0-9]{3}$/', $color)) {
            return '#' . $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }
        
        // Colores CSS básicos
        $css_colors = [
            'red' => '#ff0000', 'blue' => '#0000ff', 'green' => '#008000',
            'yellow' => '#ffff00', 'orange' => '#ffa500', 'purple' => '#800080',
            'pink' => '#ffc0cb', 'brown' => '#a52a2a', 'black' => '#000000',
            'white' => '#ffffff', 'gray' => '#808080', 'grey' => '#808080'
        ];
        
        if (isset($css_colors[$color])) {
            return $css_colors[$color];
        }
        
        // Si nada funciona, retornar color por defecto
        return '#2c3e50';
    }

    /**
     * Oscurece un color hexadecimal por el porcentaje especificado
     */
    private function darken_color($hex, $percent) {
        if (!$hex || !preg_match('/^#[a-f0-9]{6}$/i', $hex)) {
            return $hex;
        }
        
        $hex = str_replace('#', '', $hex);
        $num = hexdec($hex);
        $amt = round(2.55 * $percent);
        
        $R = max(0, ($num >> 16) - $amt);
        $B = max(0, (($num >> 8) & 0x00FF) - $amt);
        $G = max(0, ($num & 0x0000FF) - $amt);
        
        return '#' . str_pad(dechex(($R << 16) + ($B << 8) + $G), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Renderiza mensaje de sin cursos
     */
    public function render_no_courses_message() {
        return html_writer::div(
            html_writer::tag('p', get_string('nocourses', 'block_curso_lista')) .
            html_writer::tag('small', 'Los cursos aparecerán aquí cuando te inscribas'),
            'no-courses-message text-center',
            ['role' => 'alert', 'style' => 'padding: 2rem; color: #666;']
        );
    }
}
<?php
/**
 * My Courses Block - Main block class
 * 
 * Displays enrolled courses with customizable styling and progress indicators.
 * Provides advanced caching, configuration synchronization, and accessibility features.
 * 
 * @package    block_curso_lista
 * @author     Harvinvapi
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.instagram.com/Harvinvapi
 * @link       https://www.linkedin.com/in/harvinvapi
 * @since      Moodle 3.9
 */

defined('MOODLE_INTERNAL') || die();

/**
 * My Courses Block class
 * 
 * @package    block_curso_lista
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_curso_lista extends block_base {

    /**
     * Initialize the block
     * 
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_curso_lista');
    }

    /**
     * Specialization method to set custom title
     * 
     * @return void
     */
    public function specialization() {
        if (isset($this->config->title) && !empty($this->config->title)) {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }
    }

    /**
     * Get the block content
     * 
     * @return stdClass|null Block content object
     */
    public function get_content() {
        global $PAGE, $USER;

        // CRÍTICO: Forzar regeneración de contenido tras cambios de configuración
        // Si la configuración fue modificada recientemente, regenerar contenido
        $force_regenerate = false;
        if (isset($this->instance->timemodified)) {
            $time_since_modified = time() - $this->instance->timemodified;
            if ($time_since_modified < 300) { // 5 minutos
                $force_regenerate = true;
                if (debugging()) {
                    error_log('CURSO LISTA: Configuración modificada recientemente, forzando regeneración');
                }
            }
        }
        
        if ($this->content !== null && !$force_regenerate) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        // Verificar si el usuario está logueado
        if (!isloggedin() || isguestuser()) {
            $this->content->text = html_writer::div(
                get_string('loginrequired', 'block_curso_lista'),
                'alert alert-info',
                ['role' => 'alert']
            );
            return $this->content;
        }

        try {
            // ✅ CRÍTICO: Incluir CSS y JS UNA SOLA VEZ
            static $assets_included = false;
            if (!$assets_included) {
                $PAGE->requires->css('/blocks/curso_lista/styles.css');
                $PAGE->requires->js('/blocks/curso_lista/script.js');
                $assets_included = true;
            }

            // Verificar configuración disponible
            if (debugging() && isset($this->config)) {
                error_log('Curso Lista: Configuración cargada correctamente');
            }
            
            // Obtener los cursos del usuario
            $enrolledcourses = $this->get_user_courses();
            
            // Verificar configuración para logs solo si hay problemas
            if (debugging('', DEBUG_DEVELOPER) && isset($this->config)) {
                error_log('CURSO LISTA: Instancia ' . $this->instance->id . ' - Config aplicada correctamente');
            }
            
            if (!empty($enrolledcourses)) {
                // Logs para debugging de configuración
                if (debugging('', DEBUG_DEVELOPER)) {
                    error_log('CURSO LISTA: get_content() - Config actual: ' . json_encode($this->config));
                }
                
                // El renderer generará el CSS dinámico en cada carga
                $renderer = $PAGE->get_renderer('block_curso_lista');
                $this->content->text .= $renderer->render_block_content($enrolledcourses, $this->config);
            } else {
                $renderer = $PAGE->get_renderer('block_curso_lista');
                $this->content->text .= $renderer->render_no_courses_message();
            }

        } catch (Exception $e) {
            // Error handling
            if (debugging()) {
                $error_message = 'Error en Curso Lista: ' . $e->getMessage();
                error_log($error_message);
                $this->content->text = html_writer::div($error_message, 'alert alert-danger');
            } else {
                $this->content->text = html_writer::div(
                    get_string('error_loading_courses', 'block_curso_lista'),
                    'alert alert-warning'
                );
            }
        }

        return $this->content;
    }

    /**
     * Get user enrolled courses with improved caching
     * 
     * Retrieves all visible and active courses for the current user,
     * implementing a 5-minute cache to improve performance.
     * 
     * @return array Array of course objects
     */
    private function get_user_courses() {
        global $USER;
        
        // Usar cache si está disponible
        if (class_exists('cache')) {
            try {
                $cache_key = 'block_curso_lista_courses_' . $USER->id;
                $cache = cache::make('block_curso_lista', 'courses');
                
                $cached_courses = $cache->get($cache_key);
                if ($cached_courses !== false) {
                    return $cached_courses;
                }
            } catch (Exception $e) {
                // Cache no disponible, continuar sin cache
                if (debugging()) {
                    error_log('Curso Lista: Cache not available: ' . $e->getMessage());
                }
            }
        }
        
        // Obtener cursos frescos
        $courses = enrol_get_my_courses('*', 'visible DESC, fullname ASC', 0, [], false);
        
        // Filtrar cursos visibles y activos
        $filtered_courses = array_filter($courses, function($course) {
            return $course->visible == 1 && 
                   $course->startdate <= time() && 
                   ($course->enddate == 0 || $course->enddate > time());
        });
        
        // Guardar en cache si está disponible
        if (class_exists('cache') && isset($cache)) {
            try {
                $cache->set($cache_key, $filtered_courses, 300); // 5 minutos
            } catch (Exception $e) {
                // Error de cache, continuar sin problemas
                if (debugging()) {
                    error_log('CusoLista: Error saving to cache: ' . $e->getMessage());
                }
            }
        }
        
        return $filtered_courses;
    }

    /**
     * Allow configuration per instance
     * 
     * @return bool True to allow instance configuration
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * Has global configuration
     * 
     * @return bool True if the block has global settings
     */
    public function has_config() {
        return true;
    }

    /**
     * Allow multiple instances
     * 
     * @return bool True to allow multiple instances of the block
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Applicable page formats for this block
     * 
     * @return array Array of page formats where block can be added
     */
    public function applicable_formats() {
        return array(
            'all' => true,
            'site' => true,
            'course-view' => true,
            'my' => true,
            'user-profile' => true
        );
    }

    /**
     * ¿Tiene contenido?
     */
    public function is_empty() {
        if (!isloggedin() || isguestuser()) {
            return false;
        }
        
        $courses = $this->get_user_courses();
        return empty($courses);
    }

    /**
     * Configuración por defecto y validación mejorada.
     */
    public function instance_config_save($data, $nolongerused = false) {
        // Validar y sanear datos de entrada
        if (!is_object($data)) {
            return false;
        }
        
        // Establecer valores por defecto si no están configurados - CORREGIDO
        if (empty($data->config_color_type) || !in_array($data->config_color_type, ['solid', 'gradient'])) {
            $data->config_color_type = 'gradient';
        }
        
        if (empty($data->config_gradient_start)) {
            $data->config_gradient_start = '#667eea';
        }
        
        if (empty($data->config_gradient_end)) {
            $data->config_gradient_end = '#764ba2';
        }
        
        // Valores por defecto para configuración de títulos
        if (empty($data->config_title_color)) {
            $data->config_title_color = '#2c3e50';
        }
        
        if (empty($data->config_title_size)) {
            $data->config_title_size = '1rem';
        }
        
        if (empty($data->config_title_weight)) {
            $data->config_title_weight = '600';
        }
        
        // Valores por defecto para diseño
        if (empty($data->block_height)) {
            $data->block_height = 'compact';
        }
        
        if (empty($data->padding)) {
            $data->padding = 'small';
        }
        
        // Validar colores
        if ($data->config_color_type === 'solid' && !empty($data->config_buttoncolor)) {
            if (!$this->is_valid_color($data->config_buttoncolor)) {
                $data->config_buttoncolor = get_config('block_curso_lista', 'buttoncolor') ?: '#3498db';
                
                if (has_capability('moodle/site:config', context_system::instance())) {
                    \core\notification::add(
                        'Color de botón inválido, se aplicó el color por defecto.',
                        \core\notification::WARNING
                    );
                }
            }
        }
        
        // Validar color del título
        if (!empty($data->config_title_color) && !$this->is_valid_color($data->config_title_color)) {
            $data->config_title_color = '#2c3e50';
            
            if (has_capability('moodle/site:config', context_system::instance())) {
                \core\notification::add(
                    'Color de título inválido, se aplicó el color por defecto.',
                    \core\notification::WARNING
                );
            }
        }
        
        // Validar gradientes
        if ($data->config_color_type === 'gradient') {
            if (!empty($data->config_gradient_start) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data->config_gradient_start)) {
                $data->config_gradient_start = '#667eea';
            }
            if (!empty($data->config_gradient_end) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data->config_gradient_end)) {
                $data->config_gradient_end = '#764ba2';
            }
        }
        
        // Validar tamaños de fuente
        $valid_sizes = ['0.8rem', '0.9rem', '1rem', '1.1rem', '1.2rem', '1.3rem'];
        if (!in_array($data->config_title_size, $valid_sizes)) {
            $data->config_title_size = '1rem';
        }
        
        // Validar pesos de fuente
        $valid_weights = ['400', '500', '600', '700', '800'];
        if (!in_array($data->config_title_weight, $valid_weights)) {
            $data->config_title_weight = '600';
        }
        
        // Validar opciones de altura
        $valid_heights = ['compact', 'normal', 'large'];
        if (!in_array($data->block_height, $valid_heights)) {
            $data->block_height = 'compact';
        }
        
        // Validar opciones de padding
        $valid_paddings = ['small', 'normal', 'large'];
        if (!in_array($data->padding, $valid_paddings)) {
            $data->padding = 'small';
        }
        
        // Limpiar contenido para forzar recarga
        $this->content = null;
        
        // Guardar configuración
        $result = parent::instance_config_save($data, $nolongerused);
        
        if (debugging('', DEBUG_DEVELOPER)) {
            error_log('CURSO LISTA: instance_config_save llamado, result=' . ($result ? 'TRUE' : 'FALSE'));
        }
        
        // SINCRONIZACIÓN AUTOMÁTICA MEJORADA después de guardar
        if ($result) {
            if (debugging('', DEBUG_DEVELOPER)) {
                error_log('CURSO LISTA: Iniciando sincronización automática...');
            }
            try {
                // 1. Limpiar TODOS los caches relacionados
                cache_helper::purge_all();
                
                // 2. Limpiar caches específicos del bloque
                if (class_exists('cache')) {
                    $cache = cache::make('block_curso_lista', 'courses');
                    $cache->purge();
                    $progress_cache = cache::make('block_curso_lista', 'progress');
                    $progress_cache->purge();
                }
                
                // 3. Sincronizar otras instancias INMEDIATAMENTE
                $this->sync_all_instances_config($data);
                
                // 4. Forzar regeneración de contenido
                $this->content = null;
                
                // 5. Recargar configuración
                $this->config = (object) $data;
                
                // 6. CRÍTICO: Actualizar timestamp de la instancia actual también
                global $DB;
                $DB->set_field('block_instances', 'timemodified', time(), array('id' => $this->instance->id));
                $this->instance->timemodified = time();
                
                if (debugging('', DEBUG_DEVELOPER)) {
                    error_log('CURSO LISTA: Sincronización automática completada exitosamente');
                }
                
            } catch (Exception $e) {
                if (debugging()) {
                    error_log('CURSO LISTA: Error en sincronización automática: ' . $e->getMessage());
                }
            }
        } else {
            if (debugging('', DEBUG_DEVELOPER)) {
                error_log('CURSO LISTA: No se ejecuta sincronización porque result es FALSE');
            }
        }
        
        return $result;
    }
    
    /**
     * Sincroniza la configuración actual con todas las otras instancias del bloque - MEJORADO
     */
    private function sync_all_instances_config($data) {
        global $DB;
        
        try {
            error_log('CURSO LISTA: Iniciando sync_all_instances_config...');
            
            // Obtener todas las instancias del bloque curso_lista
            $instances = $DB->get_records('block_instances', array('blockname' => 'curso_lista'));
            error_log('CURSO LISTA: Encontradas ' . count($instances) . ' instancias del bloque');
            
            if (!empty($instances)) {
                // Usar los datos recién guardados para la sincronización
                $config_encoded = base64_encode(serialize($data));
                
                $synced_count = 0;
                foreach ($instances as $instance) {
                    // Actualizar TODAS las instancias para asegurar consistencia
                    $DB->set_field('block_instances', 'configdata', $config_encoded, array('id' => $instance->id));
                    $DB->set_field('block_instances', 'timemodified', time(), array('id' => $instance->id));
                    $synced_count++;
                }
                
                error_log('CURSO LISTA: Sincronizadas ' . $synced_count . ' instancias totales');
                
                // CRÍTICO: Limpiar cache de bloques después de la sincronización
                if (function_exists('cache_helper')) {
                    cache_helper::purge_by_definition('core', 'blockinstances');
                    cache_helper::purge_by_definition('core', 'block_instances');
                }
                
            } else {
                error_log('CURSO LISTA: No se encontraron instancias para sincronizar');
            }
            
        } catch (Exception $e) {
            error_log('CURSO LISTA: Error sincronizando instancias: ' . $e->getMessage());
        }
    }

    /**
     * Valida si un color es válido.
     */
    private function is_valid_color($color) {
        // Limpiar el color
        $color = trim(strtolower($color));
        
        // Hexadecimal
        if (preg_match('/^#[a-f0-9]{6}$/', $color) || preg_match('/^#[a-f0-9]{3}$/', $color)) {
            return true;
        }
        
        // Nombres CSS básicos
        $valid_colors = [
            'red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink',
            'brown', 'black', 'white', 'gray', 'grey', 'cyan', 'magenta',
            'lime', 'navy', 'teal', 'silver', 'maroon', 'olive'
        ];
        
        return in_array($color, $valid_colors);
    }

    /**
     * Limpia el cache cuando se guarda la configuración - MEJORADO.
     */
    public function instance_config_commit($nolongerused = false) {
        global $USER, $PAGE;
        
        error_log('CURSO LISTA: instance_config_commit ejecutándose...');
        
        // Limpiar TODOS los caches posibles
        try {
            // 1. Cache global de Moodle
            cache_helper::purge_all();
            
            // 2. Caches específicos del bloque
            if (class_exists('cache')) {
                $cache = cache::make('block_curso_lista', 'courses');
                $cache->purge(); // Purgar todo el cache, no solo el del usuario
                
                $progress_cache = cache::make('block_curso_lista', 'progress');
                $progress_cache->purge();
                
                // 3. Cache de imágenes si existe
                try {
                    $images_cache = cache::make('block_curso_lista', 'images');
                    $images_cache->purge();
                } catch (Exception $e) {
                    // Cache de imágenes puede no existir
                }
            }
            
            // 4. Limpiar cache específico de bloques
            if (function_exists('cache_helper')) {
                cache_helper::purge_by_definition('core', 'blockinstances');
                cache_helper::purge_by_definition('core', 'block_instances');
                cache_helper::purge_by_event('changesincourse');
            }
            
            // 5. Forzar regeneración de contenido
            $this->content = null;
            
            error_log('CURSO LISTA: Todos los caches limpiados en commit');
            
        } catch (Exception $e) {
            error_log('CURSO LISTA: Error clearing cache in commit: ' . $e->getMessage());
        }
        
        return parent::instance_config_commit($nolongerused);
    }
    
    /**
     * Configuración de la instancia para depuración.
     */
    public function get_config_for_external() {
        return (object) [
            'title' => $this->title,
            'config' => $this->config,
            'version' => '2.0.1'
        ];
    }
    
    /**
     * Exportar configuración para debugging
     */
    public function export_for_template(renderer_base $output) {
        $courses = $this->get_user_courses();
        
        return [
            'courses' => array_map(function($course) {
                return [
                    'id' => $course->id,
                    'fullname' => format_string($course->fullname),
                    'shortname' => format_string($course->shortname),
                    'visible' => $course->visible
                ];
            }, $courses),
            'has_courses' => !empty($courses),
            'config' => $this->config,
            'debug_info' => [
                'version' => '2.0.1',
                'image_size' => '80px',
                'progress_size' => '80px',
                'progress_radius' => 26,
                'background' => 'white'
            ]
        ];
    }

    /**
     * Obtiene los requisitos JavaScript para el bloque
     */
    public function get_required_javascript() {
        parent::get_required_javascript();
        
        // Asegurar que el JavaScript se carga
        $this->page->requires->js('/blocks/curso_lista/script.js');
    }

    /**
     * Método para obtener configuración específica para el renderer
     */
    public function get_renderer_config() {
        return [
            'config_color_type' => isset($this->config->config_color_type) ? $this->config->config_color_type : 'gradient',
            'config_buttoncolor' => isset($this->config->config_buttoncolor) ? $this->config->config_buttoncolor : '#3498db',
            'config_gradient_start' => isset($this->config->config_gradient_start) ? $this->config->config_gradient_start : '#667eea',
            'config_gradient_end' => isset($this->config->config_gradient_end) ? $this->config->config_gradient_end : '#764ba2',
            'config_title_color' => isset($this->config->config_title_color) ? $this->config->config_title_color : '#2c3e50',
            'config_title_size' => isset($this->config->config_title_size) ? $this->config->config_title_size : '1rem',
            'config_title_weight' => isset($this->config->config_title_weight) ? $this->config->config_title_weight : '600',
            'block_height' => isset($this->config->block_height) ? $this->config->block_height : 'compact',
            'padding' => isset($this->config->padding) ? $this->config->padding : 'small',
            'animations' => isset($this->config->animations) ? $this->config->animations : true,
            'cache_time' => isset($this->config->cache_time) ? $this->config->cache_time : 300
        ];
    }

    /**
     * Método para forzar regeneración completa (útil para debugging)
     */
    public function force_regenerate() {
        // Limpiar contenido del bloque
        $this->content = null;
        
        // Limpiar caches
        $this->clear_cache();
        
        // Forzar recarga de configuración desde DB
        $this->config = null;
        
        error_log('CURSO LISTA: Regeneración forzada completada');
    }

    /**
     * Método para limpiar cache manualmente (útil para debugging)
     */
    public function clear_cache() {
        global $USER;
        
        if (class_exists('cache')) {
            try {
                // Limpiar cache de cursos
                $courses_cache = cache::make('block_curso_lista', 'courses');
                $courses_cache_key = 'block_curso_lista_courses_' . $USER->id;
                $courses_cache->delete($courses_cache_key);
                
                // Limpiar cache de progreso
                $progress_cache = cache::make('block_curso_lista', 'progress');
                $progress_cache->purge();
                
                // Limpiar cache de imágenes si existe
                $images_cache = cache::make('block_curso_lista', 'images');
                $images_cache->purge();
                
                return true;
                
            } catch (Exception $e) {
                if (debugging()) {
                    error_log('CusoLista: Error clearing cache manually: ' . $e->getMessage());
                }
                return false;
            }
        }
        
        return false;
    }

    /**
     * Información del bloque para administradores
     */
    public function get_aria_role() {
        return 'region';
    }

    /**
     * CSS adicional específico para la instancia
     */
    public function html_attributes() {
        return [
            'class' => 'block_curso_lista',
            'data-version' => '2.0.1',
            'data-background' => 'white'
        ];
    }

    /**
     * Configuración predeterminada para nuevas instancias
     */
    public function get_default_config() {
        return [
            'config_color_type' => 'gradient',
            'config_gradient_start' => '#667eea',
            'config_gradient_end' => '#764ba2',
            'config_title_color' => '#2c3e50',
            'config_title_size' => '1rem',
            'config_title_weight' => '600',
            'block_height' => 'compact',
            'padding' => 'small',
            'animations' => true,
            'cache_time' => 300
        ];
    }

    /**
     * Método para debugging - obtener información del bloque
     */
    public function get_debug_info() {
        global $USER;
        
        $courses = $this->get_user_courses();
        
        return [
            'version' => '2.0.1',
            'user_id' => $USER->id,
            'courses_count' => count($courses),
            'config' => $this->config,
            'title' => $this->title,
            'cache_available' => class_exists('cache'),
            'debugging_enabled' => debugging(),
            'background_color' => 'white',
            'progress_system' => 'svg_circles_80px',
            'timestamp' => time()
        ];
    }
}
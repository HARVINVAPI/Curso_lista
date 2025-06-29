<?php
/**
 * My Courses Block - Configuration form
 * 
 * @package    block_curso_lista
 * @copyright  2024 Harvinvapi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block configuration form class
 */
class block_curso_lista_edit_form extends block_edit_form {

    /**
     * Specific definition for the block configuration form
     * 
     * @param MoodleQuickForm $mform The form being built
     * @return void
     */
    protected function specific_definition($mform) {
        // Validate and get existing configuration safely
        $config = $this->block->config ?? new stdClass();
        
        // Sanitize and validate existing values
        $title_color = $this->validate_color($config->title_color ?? $config->config_title_color ?? '#2c3e50');
        $title_size = $this->validate_size($config->title_size ?? $config->config_title_size ?? '1rem');
        $title_weight = $this->validate_weight($config->title_weight ?? $config->config_title_weight ?? '600');
        $color_type = $this->validate_color_type($config->color_type ?? $config->config_color_type ?? 'gradient');
        $buttoncolor = $this->validate_color($config->buttoncolor ?? $config->config_buttoncolor ?? '#3498db');
        $buttoncolor_hover = $this->validate_color($config->buttoncolor_hover ?? $config->config_buttoncolor_hover ?? '#2980b9');
        $gradient_start = $this->validate_color($config->gradient_start ?? $config->config_gradient_start ?? '#667eea');
        $gradient_end = $this->validate_color($config->gradient_end ?? $config->config_gradient_end ?? '#764ba2');
        $gradient_start_hover = $this->validate_color($config->gradient_start_hover ?? $config->config_gradient_start_hover ?? '#5a6fd8');
        $gradient_end_hover = $this->validate_color($config->gradient_end_hover ?? $config->config_gradient_end_hover ?? '#667eea');
        
        // Sección principal
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Título personalizado del bloque
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_curso_lista'));
        $mform->setType('config_title', PARAM_TEXT);
        
        // Separador visual
        $mform->addElement('html', '<hr style="margin: 20px 0; border: 1px solid #ddd;">');
        
        // ===== SECCIÓN DE TÍTULOS DE CURSOS =====
        $mform->addElement('header', 'titleheader', 'Configuración de Títulos de Cursos');
        $mform->setExpanded('titleheader', true);
        
        // CSS COMPLETAMENTE REDISEÑADO - INTERFAZ PROFESIONAL
        $style_html = '
        <style>
        /* === CONTENEDOR PRINCIPAL PROFESIONAL === */
        .curso-lista-config-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            border-radius: 12px;
            margin: 20px 0;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
        }
        
        .config-section-header {
            background: rgba(255,255,255,0.1);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .config-section-header h4 {
            color: white;
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .config-section-content {
            background: white;
            padding: 25px;
        }
        
        /* === CAMPO DE COLOR PROFESIONAL EN UNA LÍNEA === */
        .form-field-with-color {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            border-radius: 10px;
            margin: 12px 0;
            border: 1px solid #e8eaff;
            transition: all 0.3s ease;
            position: relative;
            min-height: 60px;
        }
        
        .form-field-with-color:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
            border-color: #667eea;
        }
        
        .form-field-with-color label {
            font-weight: 600 !important;
            color: #2c3e50 !important;
            font-size: 0.95rem !important;
            flex: 1 !important;
            margin: 0 !important;
            min-width: auto !important;
            text-align: left !important;
            display: block !important;
        }
        
        .form-field-with-color .color-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .form-field-with-color input[type="text"] {
            width: 100px !important;
            padding: 8px 12px !important;
            border: 2px solid #e0e6ed !important;
            border-radius: 8px !important;
            font-family: "JetBrains Mono", "Fira Code", "Monaco", monospace !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            text-align: center !important;
            background: white !important;
            transition: all 0.3s ease !important;
        }
        
        .form-field-with-color input[type="text"]:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
            outline: none !important;
        }
        
        .form-field-with-color small {
            min-width: auto !important;
            text-align: left !important;
            color: #667eea !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            margin-left: 10px !important;
        }
        
        .color-picker-circle {
            width: 45px !important;
            height: 45px !important;
            border: 3px solid white !important;
            border-radius: 12px !important;
            cursor: pointer !important;
            outline: none !important;
            padding: 0 !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15), inset 0 0 0 1px rgba(0,0,0,0.1) !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        .color-picker-circle:hover {
            transform: scale(1.1) rotate(5deg) !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2), inset 0 0 0 1px rgba(0,0,0,0.1) !important;
        }
        
        .color-picker-circle::after {
            content: "🎨";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18px;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        
        .color-picker-circle:hover::after {
            opacity: 1;
        }
        
        .color-picker-circle::-webkit-color-swatch-wrapper {
            padding: 0 !important;
            border: none !important;
            border-radius: 9px !important;
        }
        .color-picker-circle::-webkit-color-swatch {
            border: none !important;
            border-radius: 9px !important;
        }
        .color-picker-circle::-moz-color-swatch {
            border: none !important;
            border-radius: 9px !important;
        }
        
        /* === SECCIONES MEJORADAS === */
        .solid-color-section, .gradient-color-section {
            background: linear-gradient(135deg, #fff9f0 0%, #fff3e6 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            border: 1px solid #ffe6cc;
            transition: all 0.3s ease;
        }
        
        .solid-color-section:hover, .gradient-color-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 165, 0, 0.1);
        }
        
        .solid-color-section h4, .gradient-color-section h4 {
            color: #ff8c00;
            margin: 0 0 15px 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .solid-color-section h4::before {
            content: "🎯";
            font-size: 16px;
        }
        
        .gradient-color-section h4::before {
            content: "🌈";
            font-size: 16px;
        }
        
        .color-section-hidden {
            display: none !important;
        }
        
        /* === VISTA PREVIA PROFESIONAL === */
        .preview-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            border-radius: 15px;
            margin: 25px 0;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }
        
        .preview-title {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .preview-content {
            background: white;
            padding: 30px;
            text-align: center;
        }
        
        /* === SELECTORES MEJORADOS === */
        .fitem select {
            padding: 10px 15px;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            background: white;
            font-size: 0.9rem;
            font-weight: 500;
            min-width: 120px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .fitem select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        /* === EFECTOS DE ANIMACIÓN === */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-field-with-color {
            animation: slideInUp 0.4s ease-out;
        }
        
        .form-field-with-color:nth-child(odd) { animation-delay: 0.1s; }
        .form-field-with-color:nth-child(even) { animation-delay: 0.2s; }
        
        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .form-field-with-color {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                min-height: auto;
                padding: 20px;
            }
            
            .form-field-with-color .color-controls {
                align-self: stretch;
                justify-content: space-between;
            }
            
            .form-field-with-color input[type="text"] {
                flex: 1;
                max-width: 150px;
            }
        }
        </style>';
        $mform->addElement('html', $style_html);
        
        // === CAMPO DE COLOR DEL TÍTULO PROFESIONAL ===
        $title_field_html = '
        <div class="form-field-with-color">
            <label for="config_title_color">🎨 Color del título del curso</label>
            <div class="color-controls">
                <input type="text" 
                       id="config_title_color" 
                       name="config_title_color" 
                       value="' . $title_color . '" 
                       placeholder="#2c3e50"
                       pattern="^#[0-9A-Fa-f]{6}$">
                <input type="color" 
                       id="config_title_color_picker" 
                       value="' . $title_color . '" 
                       class="color-picker-circle"
                       title="Clic para elegir color">
                <small>Clic en el círculo para elegir</small>
            </div>
        </div>';
        $mform->addElement('html', $title_field_html);
        
        // CAMPO MOODLE REAL OCULTO para color del título
        $mform->addElement('hidden', 'config_title_color', $title_color);
        $mform->setType('config_title_color', PARAM_TEXT);
        
        // === SELECTORES PROFESIONALES ===
        
        // Tamaño del título con diseño mejorado
        $size_options = array(
            '0.8rem' => '📏 Muy Pequeño (0.8rem)',
            '0.9rem' => '📏 Pequeño (0.9rem)',
            '1rem' => '📏 Normal (1rem)',
            '1.1rem' => '📏 Mediano (1.1rem)',
            '1.2rem' => '📏 Grande (1.2rem)',
            '1.3rem' => '📏 Extra Grande (1.3rem)'
        );
        
        
        $size_field_html = '
        <div class="form-field-with-color">
            <label for="config_title_size">📐 Tamaño del título</label>
            <div class="color-controls">
                <select id="config_title_size" name="config_title_size" class="pro-select">';
        
        foreach ($size_options as $value => $label) {
            $selected = ($value == $title_size) ? 'selected' : '';
            $size_field_html .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        
        $size_field_html .= '
                </select>
                <small>Elige el tamaño de fuente</small>
            </div>
        </div>';
        $mform->addElement('html', $size_field_html);
        
        // CAMPO MOODLE REAL OCULTO para tamaño del título
        $mform->addElement('hidden', 'config_title_size', $title_size);
        $mform->setType('config_title_size', PARAM_TEXT);
        
        // Peso de la fuente con diseño mejorado
        $weight_options = array(
            '400' => '📝 Normal (400)',
            '500' => '📝 Medio (500)',
            '600' => '📝 Semi-Bold (600)',
            '700' => '📝 Bold (700)',
            '800' => '📝 Extra Bold (800)'
        );
        
        
        $weight_field_html = '
        <div class="form-field-with-color">
            <label for="config_title_weight">💪 Peso del título</label>
            <div class="color-controls">
                <select id="config_title_weight" name="config_title_weight" class="pro-select">';
        
        foreach ($weight_options as $value => $label) {
            $selected = ($value == $title_weight) ? 'selected' : '';
            $weight_field_html .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        
        $weight_field_html .= '
                </select>
                <small>Elige el grosor de fuente</small>
            </div>
        </div>';
        $mform->addElement('html', $weight_field_html);
        
        // CAMPO MOODLE REAL OCULTO para peso del título
        $mform->addElement('hidden', 'config_title_weight', $title_weight);
        $mform->setType('config_title_weight', PARAM_TEXT);
        
        // Separador visual
        $mform->addElement('html', '<hr style="margin: 20px 0; border: 1px solid #ddd;">');
        
        // ===== SECCIÓN DE COLORES DE BOTONES =====
        $mform->addElement('header', 'colorheader', '🎨 Configuración de Colores de Botones');
        $mform->setExpanded('colorheader', true);
        
        // === SELECTOR DE TIPO DE COLOR PROFESIONAL ===
        $color_options = array(
            'solid' => 'Color sólido',
            'gradient' => 'Gradiente personalizado'
        );
        
        
        $color_type_field_html = '
        <div class="form-field-with-color">
            <label for="config_color_type">🎨 Tipo de diseño del botón</label>
            <div class="color-controls">
                <select id="config_color_type" name="config_color_type" class="pro-select">';
        
        foreach ($color_options as $value => $label) {
            $selected = ($value == $color_type) ? 'selected' : '';
            $color_type_field_html .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        
        $color_type_field_html .= '
                </select>
                <small>Elige el estilo de coloreo</small>
            </div>
        </div>';
        $mform->addElement('html', $color_type_field_html);
        
        // CAMPO MOODLE REAL OCULTO para tipo de color
        $mform->addElement('hidden', 'config_color_type', $color_type);
        $mform->setType('config_color_type', PARAM_TEXT);
        
        // === SECCIÓN COLOR SÓLIDO REDISEÑADA ===
        $solid_section_html = '
        <div class="solid-color-section color-section-hidden">
            <h4>Color Sólido</h4>
            
            <!-- Color principal del botón -->
            <div class="form-field-with-color">
                <label for="config_buttoncolor">🔴 Color principal del botón</label>
                <div class="color-controls">
                    <input type="text" 
                           id="config_buttoncolor" 
                           name="config_buttoncolor" 
                           value="' . $buttoncolor . '" 
                           placeholder="#3498db"
                           pattern="^#[0-9A-Fa-f]{6}$">
                    <input type="color" 
                           id="config_buttoncolor_picker" 
                           value="' . $buttoncolor . '" 
                           class="color-picker-circle"
                           title="Clic para elegir color">
                    <small>Clic en el círculo para elegir</small>
                </div>
            </div>
            
            <!-- Color hover del botón -->
            <div class="form-field-with-color">
                <label for="config_buttoncolor_hover">🟠 Color al pasar el mouse</label>
                <div class="color-controls">
                    <input type="text" 
                           id="config_buttoncolor_hover" 
                           name="config_buttoncolor_hover" 
                           value="' . $buttoncolor_hover . '" 
                           placeholder="#2980b9"
                           pattern="^#[0-9A-Fa-f]{6}$">
                    <input type="color" 
                           id="config_buttoncolor_hover_picker" 
                           value="' . $buttoncolor_hover . '" 
                           class="color-picker-circle"
                           title="Clic para elegir color">
                    <small>Color al hacer hover</small>
                </div>
            </div>
        </div>';
        $mform->addElement('html', $solid_section_html);
        
        // CAMPOS MOODLE REALES OCULTOS para colores sólidos
        $mform->addElement('hidden', 'config_buttoncolor', $buttoncolor);
        $mform->setType('config_buttoncolor', PARAM_TEXT);
        $mform->addElement('hidden', 'config_buttoncolor_hover', $buttoncolor_hover);
        $mform->setType('config_buttoncolor_hover', PARAM_TEXT);
        
        // === SECCIÓN GRADIENTE REDISEÑADA ===
        $gradient_section_html = '
        <div class="gradient-color-section">
            <h4>Gradiente Personalizado</h4>
            
            <!-- Color inicial del gradiente -->
            <div class="form-field-with-color">
                <label for="config_gradient_start">🔵 Color inicial del gradiente</label>
                <div class="color-controls">
                    <input type="text" 
                           id="config_gradient_start" 
                           name="config_gradient_start" 
                           value="' . $gradient_start . '" 
                           placeholder="#667eea"
                           pattern="^#[0-9A-Fa-f]{6}$">
                    <input type="color" 
                           id="config_gradient_start_picker" 
                           value="' . $gradient_start . '" 
                           class="color-picker-circle"
                           title="Clic para elegir color">
                    <small>Clic en el círculo para elegir</small>
                </div>
            </div>
            
            <!-- Color final del gradiente -->
            <div class="form-field-with-color">
                <label for="config_gradient_end">🟣 Color final del gradiente</label>
                <div class="color-controls">
                    <input type="text" 
                           id="config_gradient_end" 
                           name="config_gradient_end" 
                           value="' . $gradient_end . '" 
                           placeholder="#764ba2"
                           pattern="^#[0-9A-Fa-f]{6}$">
                    <input type="color" 
                           id="config_gradient_end_picker" 
                           value="' . $gradient_end . '" 
                           class="color-picker-circle"
                           title="Clic para elegir color">
                    <small>Clic en el círculo para elegir</small>
                </div>
            </div>
            
            <!-- Color inicial hover -->
            <div class="form-field-with-color">
                <label for="config_gradient_start_hover">🔷 Color inicial al pasar el mouse</label>
                <div class="color-controls">
                    <input type="text" 
                           id="config_gradient_start_hover" 
                           name="config_gradient_start_hover" 
                           value="' . $gradient_start_hover . '" 
                           placeholder="#5a6fd8"
                           pattern="^#[0-9A-Fa-f]{6}$">
                    <input type="color" 
                           id="config_gradient_start_hover_picker" 
                           value="' . $gradient_start_hover . '" 
                           class="color-picker-circle"
                           title="Clic para elegir color">
                    <small>Color al hacer hover</small>
                </div>
            </div>
            
            <!-- Color final hover -->
            <div class="form-field-with-color">
                <label for="config_gradient_end_hover">🟪 Color final al pasar el mouse</label>
                <div class="color-controls">
                    <input type="text" 
                           id="config_gradient_end_hover" 
                           name="config_gradient_end_hover" 
                           value="' . $gradient_end_hover . '" 
                           placeholder="#667eea"
                           pattern="^#[0-9A-Fa-f]{6}$">
                    <input type="color" 
                           id="config_gradient_end_hover_picker" 
                           value="' . $gradient_end_hover . '" 
                           class="color-picker-circle"
                           title="Clic para elegir color">
                    <small>Color al hacer hover</small>
                </div>
            </div>
        </div>';
        $mform->addElement('html', $gradient_section_html);
        
        // CAMPOS MOODLE REALES OCULTOS para gradientes
        $mform->addElement('hidden', 'config_gradient_start', $gradient_start);
        $mform->setType('config_gradient_start', PARAM_TEXT);
        $mform->addElement('hidden', 'config_gradient_end', $gradient_end);
        $mform->setType('config_gradient_end', PARAM_TEXT);
        $mform->addElement('hidden', 'config_gradient_start_hover', $gradient_start_hover);
        $mform->setType('config_gradient_start_hover', PARAM_TEXT);
        $mform->addElement('hidden', 'config_gradient_end_hover', $gradient_end_hover);
        $mform->setType('config_gradient_end_hover', PARAM_TEXT);
        
        // Vista previa y presets
        $this->add_preview_section($mform);
        
        // Sección de solución de cache
        $this->add_cache_solution_section($mform);
        
        // Sección de donación
        $this->add_donation_section($mform);
    }
    
    
    private function add_preview_section($mform) {
        // Separador visual
        $mform->addElement('html', '<hr style="margin: 20px 0; border: 1px solid #ddd;">');
        
        // Vista previa del botón
        $preview_html = '
        <div style="margin: 15px 0;">
            <p><strong>Vista previa del botón:</strong></p>
            <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <div id="button-preview" 
                     style="padding: 10px 20px; border-radius: 25px; color: white; text-align: center; font-weight: bold; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease; display: inline-block; min-width: 120px; cursor: pointer; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);"
                     data-original-bg="linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
                     data-hover-bg="linear-gradient(135deg, #5a6fd8 0%, #667eea 100%)"
                     onmouseover="this.style.background = this.dataset.hoverBg"
                     onmouseout="this.style.background = this.dataset.originalBg">
                    Ir al curso
                </div>
                <div style="font-size: 12px; color: #666;">
                    ← Hover para ver efecto
                </div>
            </div>
        </div>';
        
        $mform->addElement('html', $preview_html);
        
        // Vista previa del título
        $title_preview_html = '
        <div style="margin: 15px 0;">
            <p><strong>Vista previa del título:</strong></p>
            <h3 id="title-preview" style="margin: 10px 0; color: #2c3e50; font-size: 1rem; font-weight: 600; transition: all 0.3s ease;">
                Nombre del Curso de Ejemplo
            </h3>
        </div>';
        
        $mform->addElement('html', $title_preview_html);
        
        // PRESETS COMPLETOS
        $presets_html = '
        <div style="margin: 15px 0;">
            <p><strong>Presets de colores para botones:</strong></p>
            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px;">
                <button type="button" onclick="setGradient(\'#667eea\', \'#764ba2\')" 
                        style="padding: 6px 10px; border-radius: 12px; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; cursor: pointer; font-size: 11px;">
                    Azul-Púrpura
                </button>
                <button type="button" onclick="setGradient(\'#ff9a9e\', \'#fecfef\')" 
                        style="padding: 6px 10px; border-radius: 12px; border: none; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; cursor: pointer; font-size: 11px;">
                    Rosa Suave
                </button>
                <button type="button" onclick="setGradient(\'#a8edea\', \'#fed6e3\')" 
                        style="padding: 6px 10px; border-radius: 12px; border: none; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; cursor: pointer; font-size: 11px;">
                    Menta-Rosa
                </button>
                <button type="button" onclick="setGradient(\'#84fab0\', \'#8fd3f4\')" 
                        style="padding: 6px 10px; border-radius: 12px; border: none; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: white; cursor: pointer; font-size: 11px;">
                    Verde-Azul
                </button>
                <button type="button" onclick="setGradient(\'#ffecd2\', \'#fcb69f\')" 
                        style="padding: 6px 10px; border-radius: 12px; border: none; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; cursor: pointer; font-size: 11px;">
                    Melocotón
                </button>
            </div>
            
            <p><strong>Presets de colores sólidos:</strong></p>
            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
                <button type="button" onclick="setSolidColor(\'#3498db\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: none; background: #3498db; color: white; cursor: pointer; font-size: 11px;">
                    Azul Clásico
                </button>
                <button type="button" onclick="setSolidColor(\'#e74c3c\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: none; background: #e74c3c; color: white; cursor: pointer; font-size: 11px;">
                    Rojo
                </button>
                <button type="button" onclick="setSolidColor(\'#2ecc71\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: none; background: #2ecc71; color: white; cursor: pointer; font-size: 11px;">
                    Verde
                </button>
                <button type="button" onclick="setSolidColor(\'#f39c12\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: none; background: #f39c12; color: white; cursor: pointer; font-size: 11px;">
                    Naranja
                </button>
            </div>
            
            <p><strong>Presets de colores para títulos:</strong></p>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <button type="button" onclick="setTitleColor(\'#2c3e50\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: 1px solid #2c3e50; background: #2c3e50; color: white; cursor: pointer; font-size: 11px;">
                    Negro Suave
                </button>
                <button type="button" onclick="setTitleColor(\'#000000\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: 1px solid #000; background: #000; color: white; cursor: pointer; font-size: 11px;">
                    Negro
                </button>
                <button type="button" onclick="setTitleColor(\'#34495e\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: 1px solid #34495e; background: #34495e; color: white; cursor: pointer; font-size: 11px;">
                    Gris Oscuro
                </button>
                <button type="button" onclick="setTitleColor(\'#667eea\')" 
                        style="padding: 6px 10px; border-radius: 8px; border: 1px solid #667eea; background: #667eea; color: white; cursor: pointer; font-size: 11px;">
                    Azul
                </button>
            </div>
        </div>';
        
        $mform->addElement('html', $presets_html);
        
        // JavaScript COMPLETO Y FUNCIONAL
        $css_js_html = '
        <script>
        // Función para manejar visibilidad de campos
        function toggleColorFields() {
            var colorType = document.querySelector(\'select[name="config_color_type"]\');
            var solidSection = document.querySelector(\'.solid-color-section\');
            var gradientSection = document.querySelector(\'.gradient-color-section\');
            
            if (!colorType) return;
            
            function updateVisibility() {
                if (colorType.value === "solid") {
                    if (solidSection) solidSection.classList.remove(\'color-section-hidden\');
                    if (gradientSection) gradientSection.classList.add(\'color-section-hidden\');
                } else {
                    if (solidSection) solidSection.classList.add(\'color-section-hidden\');
                    if (gradientSection) gradientSection.classList.remove(\'color-section-hidden\');
                }
            }
            
            updateVisibility();
            colorType.addEventListener(\'change\', updateVisibility);
        }
        
        // Función para inicializar estado correcto
        function initializeColorFields() {
            var colorType = document.querySelector(\'select[name="config_color_type"]\');
            var solidSection = document.querySelector(\'.solid-color-section\');
            var gradientSection = document.querySelector(\'.gradient-color-section\');
            
            if (colorType && colorType.value === "solid") {
                if (solidSection) solidSection.classList.remove(\'color-section-hidden\');
                if (gradientSection) gradientSection.classList.add(\'color-section-hidden\');
            } else {
                if (solidSection) solidSection.classList.add(\'color-section-hidden\');
                if (gradientSection) gradientSection.classList.remove(\'color-section-hidden\');
            }
        }
        
        function darkenColorHex(hex, percent) {
            if (!hex || hex.indexOf(\'#\') === -1) return hex;
            var num = parseInt(hex.replace(\'#\', \'\'), 16);
            var amt = Math.round(2.55 * percent);
            var R = Math.max(0, (num >> 16) - amt);
            var B = Math.max(0, (num >> 8 & 0x00FF) - amt);
            var G = Math.max(0, (num & 0x0000FF) - amt);
            return \'#\' + (0x1000000 + R * 0x10000 + B * 0x100 + G).toString(16).slice(1);
        }

        function updateColorPickerAndPreview(fieldId, color) {
            var textInput = document.getElementById(fieldId);
            var colorPicker = document.getElementById(fieldId + \'_picker\');
            
            console.log(\'updateColorPickerAndPreview llamada para:\', fieldId, \'con color:\', color);
            console.log(\'textInput encontrado:\', !!textInput, \'colorPicker encontrado:\', !!colorPicker);
            
            if (textInput) {
                textInput.value = color;
                console.log(\'textInput actualizado a:\', textInput.value);
            }
            if (colorPicker) {
                colorPicker.value = color;
                console.log(\'colorPicker actualizado a:\', colorPicker.value);
            }
        }

        function updateMainPreviews() {
            // Actualizar preview del botón
            var colorType = document.querySelector(\'select[name="config_color_type"]\');
            var solidColor = document.querySelector(\'input[name="config_buttoncolor"]\');
            var gradientStart = document.querySelector(\'input[name="config_gradient_start"]\');
            var gradientEnd = document.querySelector(\'input[name="config_gradient_end"]\');
            var gradientStartHover = document.querySelector(\'input[name="config_gradient_start_hover"]\');
            var gradientEndHover = document.querySelector(\'input[name="config_gradient_end_hover"]\');
            var solidHover = document.querySelector(\'input[name="config_buttoncolor_hover"]\');
            var preview = document.getElementById("button-preview");
            
            console.log(\'Actualizando previews...\');
            
            if (preview) {
                if (colorType && colorType.value === "solid" && solidColor && solidColor.value) {
                    var solidBg = solidColor.value;
                    var solidHoverBg = (solidHover && solidHover.value) ? solidHover.value : darkenColorHex(solidBg, 20);
                    
                    preview.style.background = solidBg;
                    preview.setAttribute(\'data-original-bg\', solidBg);
                    preview.setAttribute(\'data-hover-bg\', solidHoverBg);
                    
                    console.log(\'Preview actualizado a color sólido:\', solidBg);
                    
                } else if (gradientStart && gradientEnd && gradientStart.value && gradientEnd.value) {
                    var gradient = "linear-gradient(135deg, " + gradientStart.value + " 0%, " + gradientEnd.value + " 100%)";
                    var gradientHover = "linear-gradient(135deg, " + 
                        ((gradientStartHover && gradientStartHover.value) ? gradientStartHover.value : darkenColorHex(gradientStart.value, 15)) + " 0%, " + 
                        ((gradientEndHover && gradientEndHover.value) ? gradientEndHover.value : darkenColorHex(gradientEnd.value, 15)) + " 100%)";
                    
                    preview.style.background = gradient;
                    preview.setAttribute(\'data-original-bg\', gradient);
                    preview.setAttribute(\'data-hover-bg\', gradientHover);
                    
                    console.log(\'Preview actualizado a gradiente:\', gradient);
                }
            }
            
            // Actualizar preview del título
            var titleColor = document.querySelector(\'input[name="config_title_color"]\');
            var titleSize = document.querySelector(\'select[name="config_title_size"]\');
            var titleWeight = document.querySelector(\'select[name="config_title_weight"]\');
            var titlePreview = document.getElementById("title-preview");
            
            if (titlePreview) {
                if (titleColor && titleColor.value) {
                    titlePreview.style.color = titleColor.value;
                }
                if (titleSize && titleSize.value) {
                    titlePreview.style.fontSize = titleSize.value;
                }
                if (titleWeight && titleWeight.value) {
                    titlePreview.style.fontWeight = titleWeight.value;
                }
            }
        }

        function setGradient(start, end, startHover, endHover) {
            var typeSelect = document.querySelector(\'select[name="config_color_type"]\');
            var startInput = document.querySelector(\'input[name="config_gradient_start"]\');
            var endInput = document.querySelector(\'input[name="config_gradient_end"]\');
            var startHoverInput = document.querySelector(\'input[name="config_gradient_start_hover"]\');
            var endHoverInput = document.querySelector(\'input[name="config_gradient_end_hover"]\');
            
            // Calcular colores hover si no se proporcionan
            var calculatedStartHover = startHover || darkenColorHex(start, 15);
            var calculatedEndHover = endHover || darkenColorHex(end, 15);
            
            if (typeSelect) typeSelect.value = "gradient";
            if (startInput) startInput.value = start;
            if (endInput) endInput.value = end;
            if (startHoverInput) startHoverInput.value = calculatedStartHover;
            if (endHoverInput) endHoverInput.value = calculatedEndHover;
            
            
            // CRÍTICO: Actualizar también los campos ocultos
            var hiddenTypeField = document.querySelector(\'input[name="config_color_type"][type="hidden"]\');
            var hiddenStartField = document.querySelector(\'input[name="config_gradient_start"][type="hidden"]\');
            var hiddenEndField = document.querySelector(\'input[name="config_gradient_end"][type="hidden"]\');
            var hiddenStartHoverField = document.querySelector(\'input[name="config_gradient_start_hover"][type="hidden"]\');
            var hiddenEndHoverField = document.querySelector(\'input[name="config_gradient_end_hover"][type="hidden"]\');
            
            if (hiddenTypeField) hiddenTypeField.value = "gradient";
            if (hiddenStartField) hiddenStartField.value = start;
            if (hiddenEndField) hiddenEndField.value = end;
            if (hiddenStartHoverField) hiddenStartHoverField.value = calculatedStartHover;
            if (hiddenEndHoverField) hiddenEndHoverField.value = calculatedEndHover;
            
            // Actualizar color pickers y previews
            updateColorPickerAndPreview(\'config_gradient_start\', start);
            updateColorPickerAndPreview(\'config_gradient_end\', end);
            updateColorPickerAndPreview(\'config_gradient_start_hover\', calculatedStartHover);
            updateColorPickerAndPreview(\'config_gradient_end_hover\', calculatedEndHover);
            
            // Actualizar visibilidad de secciones
            initializeColorFields();
            updateMainPreviews();
        }

        function setSolidColor(color, hoverColor) {
            var typeSelect = document.querySelector(\'select[name="config_color_type"]\');
            var colorInput = document.querySelector(\'input[name="config_buttoncolor"]\');
            var hoverInput = document.querySelector(\'input[name="config_buttoncolor_hover"]\');
            
            // Calcular color hover si no se proporciona
            var calculatedHoverColor = hoverColor || darkenColorHex(color, 20);
            
            if (typeSelect) typeSelect.value = "solid";
            if (colorInput) colorInput.value = color;
            if (hoverInput) hoverInput.value = calculatedHoverColor;
            
            
            // CRÍTICO: Actualizar también los campos ocultos
            var hiddenTypeField = document.querySelector(\'input[name="config_color_type"][type="hidden"]\');
            var hiddenColorField = document.querySelector(\'input[name="config_buttoncolor"][type="hidden"]\');
            var hiddenHoverField = document.querySelector(\'input[name="config_buttoncolor_hover"][type="hidden"]\');
            
            if (hiddenTypeField) hiddenTypeField.value = "solid";
            if (hiddenColorField) hiddenColorField.value = color;
            if (hiddenHoverField) hiddenHoverField.value = calculatedHoverColor;
            
            // Actualizar color pickers y previews
            updateColorPickerAndPreview(\'config_buttoncolor\', color);
            updateColorPickerAndPreview(\'config_buttoncolor_hover\', calculatedHoverColor);
            
            // Actualizar visibilidad de secciones
            initializeColorFields();
            updateMainPreviews();
        }

        function setTitleColor(color) {
            var titleColorInput = document.querySelector(\'input[name="config_title_color"]\');
            if (titleColorInput) {
                titleColorInput.value = color;
                
                // CRÍTICO: Actualizar también el campo oculto
                var hiddenTitleColorField = document.querySelector(\'input[name="config_title_color"][type="hidden"]\');
                if (hiddenTitleColorField) {
                    hiddenTitleColorField.value = color;
                    console.log(\'Campo oculto título actualizado:\', color);
                }
                
                updateColorPickerAndPreview(\'config_title_color\', color);
                updateMainPreviews();
            }
        }
        
        // === CONECTAR COLOR PICKERS CON CAMPOS DE TEXTO ===
        
        // Función para inicializar color pickers
        function initColorPickerSync() {
            var colorFields = [
                \'config_title_color\',
                \'config_buttoncolor\',
                \'config_buttoncolor_hover\',
                \'config_gradient_start\',
                \'config_gradient_end\',
                \'config_gradient_start_hover\',
                \'config_gradient_end_hover\'
            ];
            
            colorFields.forEach(function(fieldId) {
                // Buscar específicamente el campo de texto VISUAL (no el oculto)
                var textField = document.getElementById(fieldId);
                var colorPicker = document.getElementById(fieldId + \'_picker\');
                
                if (textField && colorPicker) {
                    console.log(\'Sincronizando campo:\', fieldId, \'textField:\', textField, \'colorPicker:\', colorPicker);
                    
                    // Sincronizar valor inicial
                    if (textField.value && /^#[0-9A-Fa-f]{6}$/.test(textField.value)) {
                        colorPicker.value = textField.value;
                    } else if (colorPicker.value) {
                        textField.value = colorPicker.value;
                    }
                    
                    // Cuando cambia el color picker, actualizar el campo de texto Y el campo oculto
                    colorPicker.addEventListener(\'input\', function(e) {
                        console.log(\'Color picker cambio para:\', fieldId, \'nuevo valor:\', e.target.value);
                        console.log(\'Antes - textField.value:\', textField.value);
                        textField.value = e.target.value;
                        console.log(\'Después - textField.value:\', textField.value);
                        
                        // CRÍTICO: Actualizar también el campo oculto de Moodle
                        var hiddenField = document.querySelector(\'input[name="\' + fieldId + \'"][type="hidden"]\');
                        if (hiddenField) {
                            hiddenField.value = e.target.value;
                            console.log(\'Campo oculto actualizado:\', fieldId, \'=\', e.target.value);
                        }
                        
                        // Disparar evento change en el campo de texto para que Moodle lo detecte
                        var event = new Event(\'change\', { bubbles: true });
                        textField.dispatchEvent(event);
                        updateMainPreviews();
                    });
                    
                    // Cuando cambia el campo de texto, actualizar el color picker Y el campo oculto
                    textField.addEventListener(\'input\', function(e) {
                        var color = e.target.value;
                        console.log(\'Campo texto cambio para:\', fieldId, \'nuevo valor:\', color);
                        if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
                            colorPicker.value = color;
                            
                            // CRÍTICO: Actualizar también el campo oculto de Moodle
                            var hiddenField = document.querySelector(\'input[name="\' + fieldId + \'"][type="hidden"]\');
                            if (hiddenField) {
                                hiddenField.value = color;
                                console.log(\'Campo oculto actualizado desde texto:\', fieldId, \'=\', color);
                            }
                            
                            updateMainPreviews();
                        }
                    });
                    
                    // Añadir evento change también
                    textField.addEventListener(\'change\', function(e) {
                        var color = e.target.value;
                        if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
                            colorPicker.value = color;
                            
                            // CRÍTICO: Actualizar también el campo oculto de Moodle
                            var hiddenField = document.querySelector(\'input[name="\' + fieldId + \'"][type="hidden"]\');
                            if (hiddenField) {
                                hiddenField.value = color;
                                console.log(\'Campo oculto actualizado desde change:\', fieldId, \'=\', color);
                            }
                            
                            updateMainPreviews();
                        }
                    });
                    
                    colorPicker.addEventListener(\'change\', function(e) {
                        textField.value = e.target.value;
                        
                        // CRÍTICO: Actualizar también el campo oculto de Moodle
                        var hiddenField = document.querySelector(\'input[name="\' + fieldId + \'"][type="hidden"]\');
                        if (hiddenField) {
                            hiddenField.value = e.target.value;
                            console.log(\'Campo oculto actualizado desde picker change:\', fieldId, \'=\', e.target.value);
                        }
                        
                        var event = new Event(\'change\', { bubbles: true });
                        textField.dispatchEvent(event);
                        updateMainPreviews();
                    });
                } else {
                    console.warn(\'No se encontraron elementos para:\', fieldId);
                    console.warn(\'textField encontrado:\', !!textField, \'colorPicker encontrado:\', !!colorPicker);
                    if (!textField) console.warn(\'No se encontró textField con id:\', fieldId);
                    if (!colorPicker) console.warn(\'No se encontró colorPicker con id:\', fieldId + \'_picker\');
                }
            });
            
            // Sincronizar selects Moodle estándar
            var selects = [\'config_color_type\', \'config_title_size\', \'config_title_weight\'];
            selects.forEach(function(selectId) {
                var selectField = document.querySelector(\'select[name="\' + selectId + \'"]\');
                if (selectField) {
                    selectField.addEventListener(\'change\', function() {
                        console.log(\'Selector Moodle actualizado:\', selectId, \'=\', this.value);
                        
                        // CRÍTICO: Actualizar también el campo oculto de Moodle
                        var hiddenField = document.querySelector(\'input[name="\' + selectId + \'"][type="hidden"]\');
                        if (hiddenField) {
                            hiddenField.value = this.value;
                            console.log(\'Campo oculto selector actualizado:\', selectId, \'=\', this.value);
                        }
                        
                        toggleColorFields();
                        updateMainPreviews();
                    });
                }
            });
            
            // Sincronizar campos de texto de colores
            var colorFields = [\'config_title_color\', \'config_buttoncolor\', \'config_buttoncolor_hover\', 
                              \'config_gradient_start\', \'config_gradient_end\', \'config_gradient_start_hover\', \'config_gradient_end_hover\'];
            colorFields.forEach(function(fieldId) {
                var textField = document.querySelector(\'input[name="\' + fieldId + \'"]\');
                if (textField) {
                    textField.addEventListener(\'input\', function() {
                        console.log(\'Campo de color actualizado:\', fieldId, \'=\', this.value);
                        updateMainPreviews();
                    });
                    textField.addEventListener(\'change\', function() {
                        updateMainPreviews();
                    });
                }
            });
        }
        
        // === SINCRONIZACIÓN AUTOMÁTICA ===
        function setupAutoSync() {
            // Detectar cuando se envía el formulario de configuración
            var form = document.querySelector(\'form\');
            if (form) {
                form.addEventListener(\'submit\', function(e) {
                    // Permitir que el formulario se envíe normalmente
                    console.log(\'Formulario enviado, programando sincronización automática...\');
                    
                    // Esperar un momento después del envío para ejecutar la sincronización
                    setTimeout(function() {
                        console.log(\'Ejecutando sincronización automática...\');
                        executeAutoSync();
                    }, 2000); // Esperar 2 segundos después del envío
                });
                
                console.log(\'Auto-sincronización configurada en el formulario\');
            }
        }
        
        function executeAutoSync() {
            // Obtener la URL base correcta
            var currentUrl = window.location.href;
            var moodleIndex = currentUrl.indexOf("/moodle/");
            var baseUrl;
            
            if (moodleIndex !== -1) {
                baseUrl = currentUrl.substring(0, moodleIndex) + "/moodle/blocks/curso_lista/fix_instances.php?apply=1";
            } else {
                var blocksIndex = currentUrl.indexOf("/blocks/");
                if (blocksIndex !== -1) {
                    baseUrl = currentUrl.substring(0, blocksIndex) + "/blocks/curso_lista/fix_instances.php?apply=1";
                } else {
                    baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, "") + "/fix_instances.php?apply=1";
                }
            }
            
            console.log(\'Ejecutando sincronización automática en:\', baseUrl);
            
            // MEJORADO: Hacer múltiples llamadas para asegurar que funcione
            Promise.all([
                // 1. Primera llamada - Sincronización básica
                fetch(baseUrl, {
                    method: \'GET\',
                    cache: \'no-cache\',
                    headers: {
                        \'Cache-Control\': \'no-cache, no-store, must-revalidate\',
                        \'Pragma\': \'no-cache\',
                        \'Expires\': \'0\'
                    }
                }),
                // 2. Segunda llamada - Asegurar limpieza de cache
                fetch(baseUrl + \'&force_cache_clear=1\', {
                    method: \'GET\',
                    cache: \'no-cache\'
                })
            ]).then(function(responses) {
                var allOk = responses.every(function(response) {
                    return response.ok;
                });
                
                if (allOk) {
                    console.log(\'✅ Sincronización automática completada con éxito\');
                    showAutoSyncNotification(\'✅ Configuración aplicada automáticamente\');
                    
                    // CRÍTICO: Esperar más tiempo y hacer una recarga forzada
                    setTimeout(function() {
                        // Recarga forzada sin cache
                        window.location.href = window.location.href + (window.location.href.includes(\'?\') ? \'&\' : \'?\') + \'nocache=\' + Date.now();
                    }, 2000);
                } else {
                    console.log(\'⚠️ Sincronización parcialmente exitosa\');
                    showAutoSyncNotification(\'⚠️ Configuración aplicada - Recargando página\');
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 1500);
                }
            }).catch(function(error) {
                console.log(\'❌ Error en sincronización automática:\', error);
                showAutoSyncNotification(\'✅ Configuración guardada - Recargando página\');
                // Incluso si hay error, recargar la página porque los cambios pueden haberse guardado
                setTimeout(function() {
                    window.location.reload(true);
                }, 2000);
            });
        }
        
        function showAutoSyncNotification(message) {
            // Crear notificación temporal
            var notification = document.createElement(\'div\');
            notification.style.cssText = \'position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px 20px; border-radius: 8px; z-index: 10000; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.3);\';
            notification.innerHTML = message;
            document.body.appendChild(notification);
            
            // Remover después de 3 segundos
            setTimeout(function() {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }
        
        // Ejecutar cuando el DOM esté listo - CON MÚLTIPLES INTENTOS
        function tryInitialize() {
            try {
                console.log(\'Intentando inicializar formulario...\');
                
                // Verificar que existen los elementos básicos
                var colorType = document.querySelector(\'select[name="config_color_type"]\');
                var titleColorPicker = document.getElementById(\'config_title_color_picker\');
                var titleColorText = document.getElementById(\'config_title_color\');
                
                console.log(\'Verificando elementos:\');
                console.log(\'colorType:\', !!colorType);
                console.log(\'titleColorPicker:\', !!titleColorPicker);
                console.log(\'titleColorText:\', !!titleColorText);
                
                if (colorType && titleColorPicker && titleColorText) {
                    console.log(\'Elementos encontrados, inicializando...\');
                    initColorPickerSync();
                    toggleColorFields();
                    initializeColorFields();
                    updateMainPreviews();
                    setupAutoSync(); // Configurar sincronización automática
                    console.log(\'Inicialización completada (incluyendo auto-sync).\');
                    return true;
                } else {
                    console.log(\'Elementos no encontrados aún, reintentando...\');
                    return false;
                }
            } catch (e) {
                console.error(\'Error en inicialización:\', e);
                return false;
            }
        }
        
        // Múltiples intentos de inicialización
        var attempts = 0;
        var maxAttempts = 30;
        
        function attemptInitialization() {
            attempts++;
            if (tryInitialize() || attempts >= maxAttempts) {
                return;
            }
            setTimeout(attemptInitialization, 100);
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', function() {
                setTimeout(attemptInitialization, 100);
            });
        } else {
            setTimeout(attemptInitialization, 100);
        }
        
        // === MEJORAS VISUALES AUTOMÁTICAS PRO ===
        function enhanceFormInterface() {
            console.log(\'🎨 Mejorando interfaz del formulario...\');
            
            // 1. Mejorar todos los campos de texto existentes de Moodle
            var textInputs = document.querySelectorAll(\'input[name*="color"]\');
            textInputs.forEach(function(input) {
                if (input.type === \'text\') {
                    // Aplicar estilos profesionales
                    input.style.fontFamily = \'JetBrains Mono, Fira Code, Monaco, monospace\';
                    input.style.textAlign = \'center\';
                    input.style.fontWeight = \'500\';
                    input.style.border = \'2px solid #e0e6ed\';
                    input.style.borderRadius = \'8px\';
                    input.style.padding = \'8px 12px\';
                    input.style.transition = \'all 0.3s ease\';
                    
                    // Eventos de focus
                    input.addEventListener(\'focus\', function() {
                        this.style.borderColor = \'#667eea\';
                        this.style.boxShadow = \'0 0 0 3px rgba(102, 126, 234, 0.1)\';
                    });
                    
                    input.addEventListener(\'blur\', function() {
                        this.style.borderColor = \'#e0e6ed\';
                        this.style.boxShadow = \'none\';
                    });
                }
            });
            
            // 2. Mejorar selectores
            var selects = document.querySelectorAll(\'select[name*="config_"]\');
            selects.forEach(function(select) {
                select.style.padding = \'10px 15px\';
                select.style.border = \'2px solid #e0e6ed\';
                select.style.borderRadius = \'8px\';
                select.style.background = \'white\';
                select.style.fontSize = \'0.9rem\';
                select.style.fontWeight = \'500\';
                select.style.cursor = \'pointer\';
                select.style.transition = \'all 0.3s ease\';
                
                select.addEventListener(\'focus\', function() {
                    this.style.borderColor = \'#667eea\';
                    this.style.boxShadow = \'0 0 0 3px rgba(102, 126, 234, 0.1)\';
                });
                
                select.addEventListener(\'blur\', function() {
                    this.style.borderColor = \'#e0e6ed\';
                    this.style.boxShadow = \'none\';
                });
            });
            
            // 3. Agrupar campos visuales automáticamente
            var formItems = document.querySelectorAll(\'.fitem\');
            formItems.forEach(function(item, index) {
                var label = item.querySelector(\'label\');
                var colorInput = item.querySelector(\'input[name*="color"]\');
                
                if (label && colorInput) {
                    // Crear contenedor profesional
                    var container = document.createElement(\'div\');
                    container.className = \'pro-form-field\';
                    container.style.cssText = `
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 15px 20px;
                        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
                        border-radius: 10px;
                        margin: 12px 0;
                        border: 1px solid #e8eaff;
                        transition: all 0.3s ease;
                        min-height: 60px;
                        animation: slideInUp 0.4s ease-out;
                        animation-delay: ${index * 0.1}s;
                    `;
                    
                    // Efecto hover
                    container.addEventListener(\'mouseenter\', function() {
                        this.style.transform = \'translateY(-2px)\';
                        this.style.boxShadow = \'0 8px 25px rgba(102, 126, 234, 0.1)\';
                        this.style.borderColor = \'#667eea\';
                    });
                    
                    container.addEventListener(\'mouseleave\', function() {
                        this.style.transform = \'translateY(0)\';
                        this.style.boxShadow = \'none\';
                        this.style.borderColor = \'#e8eaff\';
                    });
                    
                    // Insertar antes del item original
                    item.parentNode.insertBefore(container, item);
                    
                    // Ocultar el item original
                    item.style.display = \'none\';
                    
                    // Crear estructura profesional
                    var labelPro = document.createElement(\'label\');
                    labelPro.textContent = label.textContent;
                    labelPro.style.cssText = `
                        font-weight: 600;
                        color: #2c3e50;
                        font-size: 0.95rem;
                        flex: 1;
                        margin: 0;
                    `;
                    
                    var controls = document.createElement(\'div\');
                    controls.style.cssText = `
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    `;
                    
                    // Clonar el input para mantener funcionalidad
                    var inputClone = colorInput.cloneNode(true);
                    
                    // Buscar color picker relacionado
                    var pickerId = colorInput.name.replace(\'config_\', \'config_\') + \'_picker\';
                    var colorPicker = document.getElementById(pickerId);
                    
                    if (colorPicker) {
                        var pickerClone = colorPicker.cloneNode(true);
                        pickerClone.style.cssText = `
                            width: 45px;
                            height: 45px;
                            border: 3px solid white;
                            border-radius: 12px;
                            cursor: pointer;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                            transition: all 0.3s ease;
                        `;
                        
                        controls.appendChild(inputClone);
                        controls.appendChild(pickerClone);
                    } else {
                        controls.appendChild(inputClone);
                    }
                    
                    var hint = document.createElement(\'small\');
                    hint.textContent = \'Ajusta el color\';
                    hint.style.cssText = `
                        color: #667eea;
                        font-weight: 500;
                        font-size: 0.85rem;
                    `;
                    controls.appendChild(hint);
                    
                    container.appendChild(labelPro);
                    container.appendChild(controls);
                }
            });
            
            console.log(\'✅ Interfaz mejorada exitosamente\');
        }
        
        // Ejecutar mejoras después de la inicialización
        setTimeout(enhanceFormInterface, 500);
        setTimeout(enhanceFormInterface, 1500); // Reintento por si hay contenido dinámico
        
        </script>';
        
        $mform->addElement('html', $css_js_html);
        
        // Información adicional
        $info_html = '
        <div style="margin: 15px 0; padding: 12px; background: #f8f9fa; border-radius: 8px; font-size: 13px; color: #666; border-left: 4px solid #667eea;">
            <strong>💡 Consejos de configuración:</strong><br>
            • <strong>Títulos:</strong> Use colores oscuros (#000000, #2c3e50) para mejor legibilidad<br>
            • <strong>Botones:</strong> Los gradientes dan un aspecto más moderno<br>
            • <strong>Altura:</strong> Modo compacto es ideal para mostrar más cursos<br>
            • <strong>Colores:</strong> Use formato hexadecimal (#FF0000) para colores exactos
        </div>';
        
        $mform->addElement('html', $info_html);
    }
    
    private function add_cache_solution_section($mform) {
        // Separador visual
        $mform->addElement('html', '<hr style="margin: 20px 0; border: 1px solid #ff9800;">');
        
        // Sección de solución de cache
        $cache_solution_html = '
        <div style="margin: 15px 0; padding: 15px; background: #fff3e0; border: 2px solid #ff9800; border-radius: 8px;">
            <h4 style="color: #e65100; margin-top: 0;">🔧 Solución de Problemas</h4>
            <p style="margin-bottom: 15px; color: #bf360c; font-weight: 500;">
                ¿Los cambios no se reflejan después de guardar? Este botón sincroniza todas las instancias del bloque:
            </p>
            <div style="background: #f9f9f9; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                <strong>💡 Proceso automático:</strong> Al guardar cambios, se sincronizan automáticamente. 
                Si no funciona, usa este botón manual.
            </div>
            
            <button type="button" 
                    onclick="openCacheFixUrl()" 
                    style="background: #ff9800; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; box-shadow: 0 2px 4px rgba(255,152,0,0.3);">
                🚀 Aplicar Cambios Inmediatamente
            </button>
            
            <div id="cache-status" style="margin-top: 10px; font-weight: bold;"></div>
            
            <details style="margin-top: 10px;">
                <summary style="cursor: pointer; color: #e65100; font-weight: bold;">ℹ️ ¿Por qué es necesario?</summary>
                <div style="margin-top: 10px; font-size: 13px; color: #666;">
                    <p>• <strong>Múltiples instancias:</strong> Si tienes el bloque en varias páginas, cada una puede tener configuración diferente</p>
                    <p>• <strong>Cache de Moodle:</strong> Moodle guarda copias temporales para mejorar velocidad</p>
                    <p>• <strong>Este botón:</strong> Sincroniza todas las instancias y limpia el cache automáticamente</p>
                </div>
            </details>
        </div>';
        
        $mform->addElement('html', $cache_solution_html);
        
        // JavaScript para el botón de cache
        $cache_js = '
        <script>
        function openCacheFixUrl() {
            var statusDiv = document.getElementById("cache-status");
            var button = event.target;
            
            // Mostrar estado
            button.innerHTML = "⏳ Abriendo herramienta...";
            statusDiv.innerHTML = "<span style=\\"color: #ff9800;\\">⏳ Abriendo herramienta de sincronización...</span>";
            
            // Obtener la URL base correcta desde la ubicación actual
            var currentUrl = window.location.href;
            var moodleIndex = currentUrl.indexOf("/moodle/");
            var baseUrl;
            
            if (moodleIndex !== -1) {
                // Si está en /moodle/, usar esa estructura
                baseUrl = currentUrl.substring(0, moodleIndex) + "/moodle/blocks/curso_lista/fix_instances.php?apply=1";
            } else {
                // Si no está en /moodle/, buscar solo hasta /blocks/
                var blocksIndex = currentUrl.indexOf("/blocks/");
                if (blocksIndex !== -1) {
                    baseUrl = currentUrl.substring(0, blocksIndex) + "/blocks/curso_lista/fix_instances.php?apply=1";
                } else {
                    // Fallback: usar la URL actual y cambiar la ruta
                    baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, "") + "/fix_instances.php?apply=1";
                }
            }
            
            // Debug: mostrar la URL generada
            console.log("URL generada para cache fix:", baseUrl);
            statusDiv.innerHTML += "<br><small>URL: " + baseUrl + "</small>";
            
            // Abrir en nueva ventana pequeña
            var popup = window.open(baseUrl, "cache_fix", "width=600,height=400,scrollbars=yes,resizable=yes");
            
            // Restaurar botón después de un momento
            setTimeout(function() {
                button.innerHTML = "🚀 Aplicar Cambios Inmediatamente";
                statusDiv.innerHTML = "<span style=\\"color: #4CAF50;\\">✅ Si se abrió la ventana, los cambios se aplicarán automáticamente</span>";
                button.disabled = false;
            }, 2000);
            
            // Cuando se cierre la ventana popup, recargar la página actual
            var checkClosed = setInterval(function() {
                if (popup.closed) {
                    clearInterval(checkClosed);
                    statusDiv.innerHTML = "<span style=\\"color: #4CAF50;\\">🔄 Recargando página para mostrar cambios...</span>";
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            }, 1000);
        }
        </script>';
        
        $mform->addElement('html', $cache_js);
    }
    
    private function add_donation_section($mform) {
        // Header de sección integrada como el resto del menú
        $mform->addElement('header', 'donationheader', '☕ Invítame un café');
        $mform->setExpanded('donationheader', false);
        
        // DISEÑO PROFESIONAL NEGRO Y PLATEADO
        $donation_html = '
        <div style="
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border: 2px solid #c0c0c0;
            box-shadow: 0 8px 32px rgba(192, 192, 192, 0.15);
            position: relative;
            overflow: hidden;
        ">
            <!-- Efecto de brillo plateado -->
            <div style="
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(192, 192, 192, 0.1), transparent);
                animation: shimmer 3s infinite;
            "></div>
            
            <div style="text-align: center; position: relative; z-index: 2;">
                <h3 style="
                    color: #c0c0c0;
                    margin: 0 0 15px 0;
                    font-size: 1.2rem;
                    font-weight: 700;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                ">✨ Invítame un café ✨</h3>
                
                <p style="
                    color: #e8e8e8;
                    margin-bottom: 20px;
                    font-size: 0.95rem;
                    font-weight: 400;
                    line-height: 1.5;
                ">Si este plugin te es útil, considera apoyar su desarrollo</p>
                
                <!-- Botón de donación principal -->
                <div style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 20px;
                    margin-bottom: 20px;
                    flex-wrap: wrap;
                ">
                    <a href="https://www.paypal.com/paypalme/Harvinvapi" target="_blank" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        padding: 12px 24px;
                        background: linear-gradient(135deg, #c0c0c0 0%, #808080 100%);
                        color: #1a1a1a;
                        text-decoration: none;
                        border-radius: 25px;
                        font-weight: 700;
                        font-size: 0.9rem;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 15px rgba(192, 192, 192, 0.3);
                        border: 1px solid #a0a0a0;
                    " onmouseover=\"
                        this.style.transform = \'translateY(-2px) scale(1.05)\';
                        this.style.boxShadow = \'0 8px 25px rgba(192, 192, 192, 0.5)\';
                        this.style.background = \'linear-gradient(135deg, #d0d0d0 0%, #909090 100%)\';
                    \" onmouseout=\"
                        this.style.transform = \'translateY(0) scale(1)\';
                        this.style.boxShadow = \'0 4px 15px rgba(192, 192, 192, 0.3)\';
                        this.style.background = \'linear-gradient(135deg, #c0c0c0 0%, #808080 100%)\';
                    \">
                        <span style=\"font-size: 1.1em;\">☕</span>
                        <strong>Invítame un café</strong>
                    </a>
                </div>
                
                <!-- Redes sociales con texto actualizado -->
                <div style="
                    margin-bottom: 20px;
                ">
                    <p style="
                        color: #c0c0c0;
                        margin-bottom: 12px;
                        font-size: 0.85rem;
                        font-weight: 500;
                    ">Sígueme en mis redes: Instagram y LinkedIn</p>
                    
                    <div style="
                        display: flex;
                        justify-content: center;
                        gap: 15px;
                        flex-wrap: wrap;
                    ">
                        <a href="https://www.instagram.com/Harvinvapi" target="_blank" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            padding: 8px 16px;
                            background: linear-gradient(135deg, #E4405F 0%, #C73650 100%);
                            color: white;
                            text-decoration: none;
                            border-radius: 20px;
                            font-weight: 600;
                            font-size: 0.8rem;
                            transition: all 0.3s ease;
                            box-shadow: 0 3px 10px rgba(228, 64, 95, 0.4);
                        " onmouseover=\"
                            this.style.transform = \'translateY(-1px)\';
                            this.style.boxShadow = \'0 5px 15px rgba(228, 64, 95, 0.6)\';
                        \" onmouseout=\"
                            this.style.transform = \'translateY(0)\';
                            this.style.boxShadow = \'0 3px 10px rgba(228, 64, 95, 0.4)\';
                        \">
                            <span>📷</span>
                            Instagram
                        </a>
                        
                        <a href="https://www.linkedin.com/in/harvinvapi" target="_blank" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            padding: 8px 16px;
                            background: linear-gradient(135deg, #0077B5 0%, #005885 100%);
                            color: white;
                            text-decoration: none;
                            border-radius: 20px;
                            font-weight: 600;
                            font-size: 0.8rem;
                            transition: all 0.3s ease;
                            box-shadow: 0 3px 10px rgba(0, 119, 181, 0.4);
                        " onmouseover=\"
                            this.style.transform = \'translateY(-1px)\';
                            this.style.boxShadow = \'0 5px 15px rgba(0, 119, 181, 0.6)\';
                        \" onmouseout=\"
                            this.style.transform = \'translateY(0)\';
                            this.style.boxShadow = \'0 3px 10px rgba(0, 119, 181, 0.4)\';
                        \">
                            <span>💼</span>
                            LinkedIn
                        </a>
                    </div>
                </div>
                
                <!-- Footer plateado -->
                <div style="
                    border-top: 1px solid #606060;
                    margin-top: 20px;
                    padding-top: 15px;
                ">
                    <p style="
                        color: #c0c0c0;
                        margin: 0 0 5px 0;
                        font-size: 0.85rem;
                        font-weight: 700;
                        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
                    ">⚡ Desarrollado por Harvinvapi</p>
                    <p style="
                        color: #a0a0a0;
                        margin: 0;
                        font-size: 0.75rem;
                        font-weight: 400;
                    ">Plugin Curso Lista v2.0.1 - Interfaz profesional</p>
                </div>
            </div>
        </div>
        
        <style>
        @keyframes shimmer {
            0% { 
                left: -100%; 
                opacity: 0; 
            }
            50% { 
                opacity: 1; 
            }
            100% { 
                left: 100%; 
                opacity: 0; 
            }
        }
        
        /* Responsive para móviles */
        @media (max-width: 600px) {
            .donation-section div[style*="display: flex"] {
                flex-direction: column !important;
                gap: 12px !important;
            }
            
            .donation-section a {
                width: 100% !important;
                max-width: 250px !important;
                margin: 0 auto !important;
            }
        }
        </style>';
        
        $mform->addElement('html', $donation_html);
    }
    
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        
        // Validar color del título
        if (!empty($data['config_title_color']) && !$this->is_valid_color($data['config_title_color'])) {
            $errors['config_title_color'] = 'Ingrese un color válido (ej: #000000 o black)';
        }
        
        // Validar color sólido
        if (isset($data['config_color_type']) && $data['config_color_type'] === 'solid') {
            if (!empty($data['config_buttoncolor']) && !$this->is_valid_color($data['config_buttoncolor'])) {
                $errors['config_buttoncolor'] = 'Ingrese un color válido (ej: #FF0000 o red)';
            }
        }
        
        // Validar gradientes
        if (isset($data['config_color_type']) && $data['config_color_type'] === 'gradient') {
            if (!empty($data['config_gradient_start']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data['config_gradient_start'])) {
                $errors['config_gradient_start'] = 'Use formato hexadecimal (#FF0000)';
            }
            if (!empty($data['config_gradient_end']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data['config_gradient_end'])) {
                $errors['config_gradient_end'] = 'Use formato hexadecimal (#FF0000)';
            }
        }
        
        return $errors;
    }
    
    /**
     * Validate color input
     * 
     * @param string $color Color value to validate
     * @return bool True if valid, false otherwise
     */
    private function is_valid_color($color) {
        // Clean input
        $color = trim($color);
        if (empty($color)) {
            return false;
        }
        
        // Hexadecimal validation
        if (preg_match('/^#[a-fA-F0-9]{6}$/', $color) || preg_match('/^#[a-fA-F0-9]{3}$/', $color)) {
            return true;
        }
        
        // CSS color names validation
        $valid_colors = ['red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink', 'brown', 'black', 'white', 'gray', 'grey'];
        return in_array(strtolower($color), $valid_colors);
    }
    
    /**
     * Validate color type selection
     * 
     * @param string $type Color type to validate
     * @return string Valid color type
     */
    private function validate_color_type($type) {
        $valid_types = ['solid', 'gradient'];
        return in_array($type, $valid_types) ? $type : 'gradient';
    }
    
    /**
     * Validate font size
     * 
     * @param string $size Font size to validate
     * @return string Valid font size
     */
    private function validate_size($size) {
        $valid_sizes = ['0.8rem', '0.9rem', '1rem', '1.1rem', '1.2rem', '1.3rem'];
        return in_array($size, $valid_sizes) ? $size : '1rem';
    }
    
    /**
     * Validate font weight
     * 
     * @param string $weight Font weight to validate
     * @return string Valid font weight
     */
    private function validate_weight($weight) {
        $valid_weights = ['400', '500', '600', '700', '800'];
        return in_array($weight, $valid_weights) ? $weight : '600';
    }
    
    /**
     * Validate and sanitize color value
     * 
     * @param string $color Color value to validate
     * @return string Sanitized color value
     */
    private function validate_color($color) {
        if ($this->is_valid_color($color)) {
            return clean_param($color, PARAM_TEXT);
        }
        return '#000000'; // Default fallback
    }
}
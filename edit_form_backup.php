<?php
defined('MOODLE_INTERNAL') || die();

class block_curso_lista_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        // Obtener configuración existente
        $config = $this->block->config ?? new stdClass();
        
        // Valores por defecto
        $title_color = $config->config_title_color ?? '#2c3e50';
        $title_size = $config->config_title_size ?? '1rem';
        $title_weight = $config->config_title_weight ?? '600';
        $color_type = $config->config_color_type ?? 'gradient';
        $buttoncolor = $config->config_buttoncolor ?? '#3498db';
        $buttoncolor_hover = $config->config_buttoncolor_hover ?? '#2980b9';
        $gradient_start = $config->config_gradient_start ?? '#667eea';
        $gradient_end = $config->config_gradient_end ?? '#764ba2';
        $gradient_start_hover = $config->config_gradient_start_hover ?? '#5a6fd8';
        $gradient_end_hover = $config->config_gradient_end_hover ?? '#667eea';
        
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
        
        // CSS para los selectores circulares ORIGINAL
        $style_html = '
        <style>
        .form-field-with-color {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .form-field-with-color label {
            min-width: 200px;
            font-weight: 600;
            text-align: left;
            display: inline-block;
        }
        .form-field-with-color input[type="text"] {
            width: 100px;
            text-align: center;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
        }
        .form-field-with-color small {
            min-width: 160px;
            text-align: left;
        }
        .color-picker-circle {
            width: 30px;
            height: 30px;
            border: none !important;
            border-radius: 50% !important;
            cursor: pointer;
            outline: none;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }
        .color-picker-circle:hover {
            transform: scale(1.1);
        }
        .color-picker-circle::-webkit-color-swatch-wrapper {
            padding: 0;
            border: none;
            border-radius: 50%;
        }
        .color-picker-circle::-webkit-color-swatch {
            border: none;
            border-radius: 50%;
        }
        .color-picker-circle::-moz-color-swatch {
            border: none;
            border-radius: 50%;
        }
        .solid-color-section, .gradient-color-section {
            transition: all 0.3s ease;
        }
        .color-section-hidden {
            display: none !important;
        }
        </style>';
        $mform->addElement('html', $style_html);
        
        // Color del título - ELEMENTO MOODLE REAL + VISUAL
        $mform->addElement('text', 'config_title_color', 'Color del título del curso', array('size' => 10, 'placeholder' => '#2c3e50'));
        $mform->setType('config_title_color', PARAM_TEXT);
        $mform->setDefault('config_title_color', $title_color);
        
        // HTML visual para el color picker
        $title_field_html = '
        <div class="form-field-with-color" style="margin-top: -20px; margin-bottom: 10px;">
            <input type="color" 
                   id="config_title_color_picker" 
                   value="' . $title_color . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px; margin-left: 10px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $title_field_html);
        
        // Tamaño del título
        $size_options = array(
            '0.9rem' => 'Pequeño (0.9rem)',
            '1rem' => 'Normal (1rem)',
            '1.1rem' => 'Mediano (1.1rem)',
            '1.2rem' => 'Grande (1.2rem)',
            '1.3rem' => 'Extra Grande (1.3rem)'
        );
        $mform->addElement('select', 'config_title_size', 'Tamaño del título', $size_options);
        $mform->setDefault('config_title_size', $title_size);
        
        // Peso de la fuente
        $weight_options = array(
            '400' => 'Normal',
            '500' => 'Medio',
            '600' => 'Semi-Bold',
            '700' => 'Bold',
            '800' => 'Extra Bold'
        );
        $mform->addElement('select', 'config_title_weight', 'Peso del título', $weight_options);
        $mform->setDefault('config_title_weight', $title_weight);
        
        // Separador visual
        $mform->addElement('html', '<hr style="margin: 20px 0; border: 1px solid #ddd;">');
        
        // ===== SECCIÓN DE COLORES DE BOTONES =====
        $mform->addElement('header', 'colorheader', '🎨 Configuración de Colores de Botones');
        $mform->setExpanded('colorheader', true);
        
        // Tipo de color: sólido o gradiente
        $color_options = array(
            'solid' => 'Color sólido',
            'gradient' => 'Gradiente personalizado'
        );
        $mform->addElement('select', 'config_color_type', 'Tipo de color', $color_options);
        $mform->setDefault('config_color_type', $color_type);
        
        // === COLOR SÓLIDO ===
        $mform->addElement('html', '<div class="solid-color-section color-section-hidden" style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin: 10px 0;"><h4>Color Sólido</h4>');
        
        // Color sólido principal - ELEMENTO MOODLE REAL
        $mform->addElement('text', 'config_buttoncolor', 'Color del botón', array('size' => 10, 'placeholder' => '#3498db'));
        $mform->setType('config_buttoncolor', PARAM_TEXT);
        $mform->setDefault('config_buttoncolor', $buttoncolor);
        
        // HTML visual para el color picker
        $solid_color_html = '
        <div class="form-field-with-color" style="margin-top: -20px; margin-bottom: 10px;">
            <input type="color" 
                   id="config_buttoncolor_picker" 
                   value="' . $buttoncolor . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px; margin-left: 10px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $solid_color_html);
        
        // Color hover sólido
        $solid_hover_html = '
        <div class="form-field-with-color">
            <label for="config_buttoncolor_hover">Color hover:</label>
            <input type="text" 
                   name="config_buttoncolor_hover" 
                   id="config_buttoncolor_hover" 
                   value="' . $buttoncolor_hover . '" 
                   placeholder="#2980b9" 
                   size="10">
            <input type="color" 
                   id="config_buttoncolor_hover_picker" 
                   value="' . $buttoncolor_hover . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $solid_hover_html);
        
        $mform->addElement('html', '</div>');
        
        // === GRADIENTE ===
        $mform->addElement('html', '<div class="gradient-color-section" style="padding: 15px; background: #f0f8ff; border-radius: 8px; margin: 10px 0;"><h4>Gradiente Personalizado</h4>');
        
        // Color inicial del gradiente - ELEMENTO MOODLE REAL
        $mform->addElement('text', 'config_gradient_start', 'Color inicial del gradiente', array('size' => 10, 'placeholder' => '#667eea'));
        $mform->setType('config_gradient_start', PARAM_TEXT);
        $mform->setDefault('config_gradient_start', $gradient_start);
        
        // HTML visual para el color picker
        $gradient_start_html = '
        <div class="form-field-with-color" style="margin-top: -20px; margin-bottom: 10px;">
            <input type="color" 
                   id="config_gradient_start_picker" 
                   value="' . $gradient_start . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px; margin-left: 10px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $gradient_start_html);
        
        // Color final del gradiente - ELEMENTO MOODLE REAL
        $mform->addElement('text', 'config_gradient_end', 'Color final del gradiente', array('size' => 10, 'placeholder' => '#764ba2'));
        $mform->setType('config_gradient_end', PARAM_TEXT);
        $mform->setDefault('config_gradient_end', $gradient_end);
        
        // HTML visual para el color picker
        $gradient_end_html = '
        <div class="form-field-with-color" style="margin-top: -20px; margin-bottom: 10px;">
            <input type="color" 
                   id="config_gradient_end_picker" 
                   value="' . $gradient_end . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px; margin-left: 10px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $gradient_end_html);
        
        // Color inicial hover
        $gradient_start_hover_html = '
        <div class="form-field-with-color">
            <label for="config_gradient_start_hover">Color inicial hover:</label>
            <input type="text" 
                   name="config_gradient_start_hover" 
                   id="config_gradient_start_hover" 
                   value="' . $gradient_start_hover . '" 
                   placeholder="#5a6fd8" 
                   size="10">
            <input type="color" 
                   id="config_gradient_start_hover_picker" 
                   value="' . $gradient_start_hover . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $gradient_start_hover_html);
        
        // Color final hover
        $gradient_end_hover_html = '
        <div class="form-field-with-color">
            <label for="config_gradient_end_hover">Color final hover:</label>
            <input type="text" 
                   name="config_gradient_end_hover" 
                   id="config_gradient_end_hover" 
                   value="' . $gradient_end_hover . '" 
                   placeholder="#667eea" 
                   size="10">
            <input type="color" 
                   id="config_gradient_end_hover_picker" 
                   value="' . $gradient_end_hover . '" 
                   class="color-picker-circle"
                   title="Clic aquí para cambiar el color">
            <small style="color: #666; font-size: 11px;">Clic aquí para cambiar el color</small>
        </div>';
        $mform->addElement('html', $gradient_end_hover_html);
        
        $mform->addElement('html', '</div>');
        
        // Vista previa y presets
        $this->add_preview_section($mform);
        
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
            var colorType = document.querySelector(\'[name="config_color_type"]\');
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
            var colorType = document.querySelector(\'[name="config_color_type"]\');
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
            
            if (textInput) textInput.value = color;
            if (colorPicker) colorPicker.value = color;
        }

        function updateMainPreviews() {
            // Actualizar preview del botón
            var colorType = document.querySelector(\'[name="config_color_type"]\');
            var solidColor = document.querySelector(\'[name="config_buttoncolor"]\');
            var gradientStart = document.querySelector(\'[name="config_gradient_start"]\');
            var gradientEnd = document.querySelector(\'[name="config_gradient_end"]\');
            var preview = document.getElementById("button-preview");
            
            if (preview) {
                if (colorType && colorType.value === "solid" && solidColor && solidColor.value) {
                    preview.style.background = solidColor.value;
                } else if (gradientStart && gradientEnd && gradientStart.value && gradientEnd.value) {
                    var gradient = "linear-gradient(135deg, " + gradientStart.value + " 0%, " + gradientEnd.value + " 100%)";
                    preview.style.background = gradient;
                }
            }
            
            // Actualizar preview del título
            var titleColor = document.querySelector(\'[name="config_title_color"]\');
            var titleSize = document.querySelector(\'[name="config_title_size"]\');
            var titleWeight = document.querySelector(\'[name="config_title_weight"]\');
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
            var typeSelect = document.querySelector(\'[name="config_color_type"]\');
            var startInput = document.querySelector(\'[name="config_gradient_start"]\');
            var endInput = document.querySelector(\'[name="config_gradient_end"]\');
            var startHoverInput = document.querySelector(\'[name="config_gradient_start_hover"]\');
            var endHoverInput = document.querySelector(\'[name="config_gradient_end_hover"]\');
            
            // Calcular colores hover si no se proporcionan
            var calculatedStartHover = startHover || darkenColorHex(start, 15);
            var calculatedEndHover = endHover || darkenColorHex(end, 15);
            
            if (typeSelect) typeSelect.value = "gradient";
            if (startInput) startInput.value = start;
            if (endInput) endInput.value = end;
            if (startHoverInput) startHoverInput.value = calculatedStartHover;
            if (endHoverInput) endHoverInput.value = calculatedEndHover;
            
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
            var typeSelect = document.querySelector(\'[name="config_color_type"]\');
            var colorInput = document.querySelector(\'[name="config_buttoncolor"]\');
            var hoverInput = document.querySelector(\'[name="config_buttoncolor_hover"]\');
            
            // Calcular color hover si no se proporciona
            var calculatedHoverColor = hoverColor || darkenColorHex(color, 20);
            
            if (typeSelect) typeSelect.value = "solid";
            if (colorInput) colorInput.value = color;
            if (hoverInput) hoverInput.value = calculatedHoverColor;
            
            // Actualizar color pickers y previews
            updateColorPickerAndPreview(\'config_buttoncolor\', color);
            updateColorPickerAndPreview(\'config_buttoncolor_hover\', calculatedHoverColor);
            
            // Actualizar visibilidad de secciones
            initializeColorFields();
            updateMainPreviews();
        }

        function setTitleColor(color) {
            var titleColorInput = document.querySelector(\'[name="config_title_color"]\');
            if (titleColorInput) {
                titleColorInput.value = color;
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
                var textField = document.getElementById(fieldId);
                var colorPicker = document.getElementById(fieldId + \'_picker\');
                
                if (textField && colorPicker) {
                    // Cuando cambia el color picker, actualizar el campo de texto
                    colorPicker.addEventListener(\'input\', function() {
                        textField.value = this.value;
                        updateMainPreviews();
                    });
                    
                    // Cuando cambia el campo de texto, actualizar el color picker
                    textField.addEventListener(\'input\', function() {
                        var color = this.value;
                        if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
                            colorPicker.value = color;
                            updateMainPreviews();
                        }
                    });
                    
                    // Sincronizar valores iniciales
                    if (textField.value && /^#[0-9A-Fa-f]{6}$/.test(textField.value)) {
                        colorPicker.value = textField.value;
                    }
                }
            });
        }
        
        // Ejecutar cuando el DOM esté listo
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', function() {
                setTimeout(function() {
                    initColorPickerSync();
                    toggleColorFields();
                    initializeColorFields();
                }, 200);
            });
        } else {
            setTimeout(function() {
                initColorPickerSync();
                toggleColorFields();
                initializeColorFields();
            }, 200);
        }
        
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
    
    private function add_donation_section($mform) {
        // Separador visual
        $mform->addElement('html', '<hr style="margin: 30px 0; border: 2px solid #667eea;">');
        
        // Header de donación
        $mform->addElement('header', 'donationheader', get_string('donation_title', 'block_curso_lista'));
        $mform->setExpanded('donationheader', false);
        
        // Contenido de donación simplificado
        $donation_html = '
        <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; margin: 15px 0;">
            <h3 style="color: white; margin-bottom: 10px;">☕ ' . get_string('donation_title', 'block_curso_lista') . '</h3>
            <p style="margin-bottom: 15px; font-size: 14px;">' . get_string('donation_description', 'block_curso_lista') . '</p>
            
            <div style="margin: 15px 0;">
                <a href="https://www.paypal.com/paypalme/Harvinvapi" target="_blank"
                   style="display: inline-block; padding: 10px 25px; background: #0070ba; color: white; text-decoration: none; border-radius: 20px; font-weight: bold;">
                    💳 ' . get_string('donate_paypal', 'block_curso_lista') . '
                </a>
            </div>
            
            <div style="margin-top: 20px; font-size: 12px; opacity: 0.9;">
                <p>' . get_string('developed_by', 'block_curso_lista') . '</p>
                <p>Plugin Curso Lista v1.5.2 - Moodle Block</p>
            </div>
        </div>';
        
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
    
    private function is_valid_color($color) {
        // Hexadecimal
        if (preg_match('/^#[a-fA-F0-9]{6}$/', $color) || preg_match('/^#[a-fA-F0-9]{3}$/', $color)) {
            return true;
        }
        
        // Nombres CSS básicos
        $valid_colors = ['red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink', 'brown', 'black', 'white', 'gray', 'grey'];
        return in_array(strtolower($color), $valid_colors);
    }
}
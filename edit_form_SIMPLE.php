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
        $gradient_start = $config->config_gradient_start ?? '#667eea';
        $gradient_end = $config->config_gradient_end ?? '#764ba2';
        
        // Sección principal
        $mform->addElement('header', 'configheader', 'Configuración del bloque');
        
        // CAMPO 1: Color del título - SIMPLE
        $mform->addElement('text', 'config_title_color', 'Color del título del curso (ej: #0080ff)', array('size' => 15));
        $mform->setType('config_title_color', PARAM_TEXT);
        $mform->setDefault('config_title_color', $title_color);
        $mform->addHelpButton('config_title_color', 'config_title_color', 'block_curso_lista');
        
        // CAMPO 2: Tamaño del título
        $size_options = array(
            '0.9rem' => 'Pequeño (0.9rem)',
            '1rem' => 'Normal (1rem)',
            '1.1rem' => 'Mediano (1.1rem)',
            '1.2rem' => 'Grande (1.2rem)',
            '1.3rem' => 'Extra Grande (1.3rem)'
        );
        $mform->addElement('select', 'config_title_size', 'Tamaño del título', $size_options);
        $mform->setDefault('config_title_size', $title_size);
        
        // CAMPO 3: Peso de la fuente
        $weight_options = array(
            '400' => 'Normal',
            '500' => 'Medio',
            '600' => 'Semi-Bold',
            '700' => 'Bold',
            '800' => 'Extra Bold'
        );
        $mform->addElement('select', 'config_title_weight', 'Peso del título', $weight_options);
        $mform->setDefault('config_title_weight', $title_weight);
        
        // SEPARADOR
        $mform->addElement('html', '<hr style="margin: 20px 0; border: 1px solid #ddd;">');
        
        // CAMPO 4: Tipo de color
        $color_options = array(
            'solid' => 'Color sólido',
            'gradient' => 'Gradiente personalizado'
        );
        $mform->addElement('select', 'config_color_type', 'Tipo de color', $color_options);
        $mform->setDefault('config_color_type', $color_type);
        
        // CAMPO 5: Color sólido del botón
        $mform->addElement('text', 'config_buttoncolor', 'Color del botón (ej: #3498db)', array('size' => 15));
        $mform->setType('config_buttoncolor', PARAM_TEXT);
        $mform->setDefault('config_buttoncolor', $buttoncolor);
        
        // CAMPO 6: Color inicial del gradiente
        $mform->addElement('text', 'config_gradient_start', 'Color inicial del gradiente (ej: #667eea)', array('size' => 15));
        $mform->setType('config_gradient_start', PARAM_TEXT);
        $mform->setDefault('config_gradient_start', $gradient_start);
        
        // CAMPO 7: Color final del gradiente
        $mform->addElement('text', 'config_gradient_end', 'Color final del gradiente (ej: #764ba2)', array('size' => 15));
        $mform->setType('config_gradient_end', PARAM_TEXT);
        $mform->setDefault('config_gradient_end', $gradient_end);
        
        // Información
        $info_html = '
        <div style="margin: 15px 0; padding: 12px; background: #e7f3ff; border-radius: 8px; font-size: 13px; color: #333; border-left: 4px solid #667eea;">
            <strong>🔧 MODO DEBUG:</strong><br>
            • Este es un formulario simplificado para probar la configuración<br>
            • Usa colores en formato hexadecimal: #FF0000 (rojo), #00FF00 (verde), #0000FF (azul)<br>
            • Los cambios deberían verse inmediatamente al guardar
        </div>';
        
        $mform->addElement('html', $info_html);
    }
    
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        
        // Validación básica de colores hexadecimales
        $color_fields = ['config_title_color', 'config_buttoncolor', 'config_gradient_start', 'config_gradient_end'];
        
        foreach ($color_fields as $field) {
            if (!empty($data[$field]) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $data[$field])) {
                $errors[$field] = 'Use formato hexadecimal de 6 caracteres (ej: #FF0000)';
            }
        }
        
        return $errors;
    }
}
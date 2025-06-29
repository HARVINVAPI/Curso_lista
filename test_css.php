<?php
/**
 * Script para probar que el CSS dinámico funciona correctamente
 */

require_once(__DIR__ . '/../../config.php');
require_login();

// Solo para administradores
if (!is_siteadmin()) {
    die('Solo administradores');
}

echo "<!DOCTYPE html><html><head><title>Test CSS - Curso Lista</title>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; }</style>";
echo "</head><body>";

echo "<h2>🎨 Test CSS Dinámico - Bloque Curso Lista</h2>";

// Simular configuración de prueba
$test_config = (object) [
    'config_color_type' => 'gradient',
    'config_title_color' => '#ff6b6b',
    'config_title_size' => '1.2rem',
    'config_title_weight' => '700',
    'config_buttoncolor' => '#4ecdc4',
    'config_gradient_start' => '#ff6b6b',
    'config_gradient_end' => '#4ecdc4',
    'config_buttoncolor_hover' => '#45b7b8',
    'config_gradient_start_hover' => '#ff5252',
    'config_gradient_end_hover' => '#45b7b8'
];

echo "<h3>📋 Configuración de Prueba:</h3>";
echo "<ul>";
echo "<li><strong>Tipo:</strong> " . $test_config->config_color_type . "</li>";
echo "<li><strong>Color título:</strong> " . $test_config->config_title_color . "</li>";
echo "<li><strong>Tamaño título:</strong> " . $test_config->config_title_size . "</li>";
echo "<li><strong>Peso título:</strong> " . $test_config->config_title_weight . "</li>";
echo "<li><strong>Gradiente inicio:</strong> " . $test_config->config_gradient_start . "</li>";
echo "<li><strong>Gradiente fin:</strong> " . $test_config->config_gradient_end . "</li>";
echo "</ul>";

// Generar CSS dinámico usando el renderer
$PAGE->requires->css('/blocks/curso_lista/styles.css');

try {
    $renderer = $PAGE->get_renderer('block_curso_lista');
    $dynamic_css = $renderer->generate_custom_css($test_config);
    
    echo "<h3>✅ CSS Dinámico Generado:</h3>";
    echo "<details><summary>Ver CSS generado</summary>";
    echo "<pre style='background: #f5f5f5; padding: 15px; overflow-x: auto; font-size: 12px;'>";
    echo htmlspecialchars($dynamic_css);
    echo "</pre></details>";
    
    // Aplicar el CSS
    echo $dynamic_css;
    
    // Crear elementos de prueba
    echo "<h3>🎯 Elementos de Prueba:</h3>";
    echo "<div class='block_curso_lista'>";
    echo "<div class='block_curso_lista-info-element'>";
    echo "<div class='course-block'>";
    
    // Imagen
    echo "<div class='course-image'>";
    echo "<img src='/blocks/curso_lista/pix/defaultcourse.svg' alt='Curso' style='width: 80px; height: 80px;'>";
    echo "</div>";
    
    // Título
    echo "<div class='course-name'>";
    echo "<h3>Este es un título de prueba que debería cambiar de color</h3>";
    echo "</div>";
    
    // Progreso
    echo "<div class='progress-container-80'>";
    echo "<div class='progress-label-black'>PROGRESO</div>";
    echo "<div class='progress-circle-container'>";
    echo "<svg viewBox='0 0 64 64' width='64' height='64'>";
    echo "<circle class='progress-bg' r='26' cx='32' cy='32' fill='none' stroke='#e0e0e0' stroke-width='4'></circle>";
    echo "<circle class='progress-fill' r='26' cx='32' cy='32' fill='none' stroke='#764ba2' stroke-width='4' stroke-linecap='round' transform='rotate(-90 32 32)'></circle>";
    echo "</svg>";
    echo "<div class='progress-percentage'>75%</div>";
    echo "</div>";
    echo "</div>";
    
    // Botón
    echo "<div class='course-action'>";
    echo "<a href='#' class='btn-course'>ENTRAR</a>";
    echo "</div>";
    
    echo "</div>"; // course-block
    echo "</div>"; // block_curso_lista-info-element
    echo "</div>"; // block_curso_lista
    
    echo "<h3>📝 Instrucciones:</h3>";
    echo "<ol>";
    echo "<li>El título debería ser color <strong style='color: " . $test_config->config_title_color . ";'>" . $test_config->config_title_color . "</strong></li>";
    echo "<li>El título debería tener tamaño <strong>" . $test_config->config_title_size . "</strong></li>";
    echo "<li>El botón debería tener gradiente de <strong>" . $test_config->config_gradient_start . "</strong> a <strong>" . $test_config->config_gradient_end . "</strong></li>";
    echo "<li>El progreso debería tener stroke-width de <strong>4</strong></li>";
    echo "<li>Inspeccionar elementos para verificar que los estilos se aplican correctamente</li>";
    echo "</ol>";
    
    echo "<h3>🔧 Herramientas:</h3>";
    echo "<p><a href='fix_instances.php'>🔧 Ir a Fix Instances</a></p>";
    echo "<p><a href='{$CFG->wwwroot}/my/'>← Volver a Mi Panel</a></p>";
    
} catch (Exception $e) {
    echo "<div style='background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px;'>";
    echo "<h3>❌ Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</body></html>";
?>
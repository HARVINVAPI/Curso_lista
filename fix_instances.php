<?php
/**
 * Script para limpiar y unificar instancias del bloque curso_lista
 */

require_once(__DIR__ . '/../../config.php');
require_login();

// Solo para administradores
if (!is_siteadmin()) {
    die('Solo administradores');
}

echo "<h2>🔧 Diagnóstico y Reparación de Instancias del Bloque</h2>";

// 1. Limpiar todos los caches
cache_helper::purge_all();
echo "<p>✅ Todos los caches limpiados</p>";

// 1.5. Limpiar cache específico del bloque curso_lista
try {
    if (class_exists('cache')) {
        $cache = cache::make('block_curso_lista', 'courses');
        $cache->purge();
        $progress_cache = cache::make('block_curso_lista', 'progress');
        $progress_cache->purge();
        echo "<p>✅ Caches específicos del bloque limpiados</p>";
    }
} catch (Exception $e) {
    echo "<p>⚠️ Error limpiando caches específicos: " . $e->getMessage() . "</p>";
}

// 2. Buscar todas las instancias del bloque curso_lista
$instances = $DB->get_records('block_instances', array('blockname' => 'curso_lista'));
echo "<h3>📊 Instancias encontradas: " . count($instances) . "</h3>";

$latest_config = null;
$latest_time = 0;

foreach ($instances as $instance) {
    echo "<div style='border: 1px solid #ddd; margin: 10px; padding: 10px;'>";
    echo "<h4>Instancia ID: {$instance->id}</h4>";
    echo "<p><strong>Región:</strong> {$instance->defaultregion}</p>";
    echo "<p><strong>Peso:</strong> {$instance->defaultweight}</p>";
    echo "<p><strong>Tiempo modificación:</strong> " . date('Y-m-d H:i:s', $instance->timemodified) . "</p>";
    
    if ($instance->configdata) {
        $config = unserialize(base64_decode($instance->configdata));
        echo "<p><strong>Configuración:</strong></p>";
        echo "<pre style='font-size: 11px; background: #f5f5f5; padding: 10px;'>";
        print_r($config);
        echo "</pre>";
        
        // Encontrar la configuración más reciente
        if ($instance->timemodified > $latest_time) {
            $latest_time = $instance->timemodified;
            $latest_config = $config;
        }
    } else {
        echo "<p><em>Sin configuración</em></p>";
    }
    echo "</div>";
}

if ($latest_config) {
    echo "<h3>🎯 Configuración más reciente encontrada:</h3>";
    echo "<pre style='font-size: 12px; background: #e8f5e8; padding: 15px; border: 2px solid #4CAF50;'>";
    print_r($latest_config);
    echo "</pre>";
    
    // Opción para aplicar esta configuración a todas las instancias
    if (isset($_GET['apply']) && $_GET['apply'] == '1') {
        $config_encoded = base64_encode(serialize($latest_config));
        
        foreach ($instances as $instance) {
            $DB->set_field('block_instances', 'configdata', $config_encoded, array('id' => $instance->id));
            $DB->set_field('block_instances', 'timemodified', time(), array('id' => $instance->id));
        }
        
        echo "<div style='background: #4CAF50; color: white; padding: 15px; margin: 10px 0;'>";
        echo "<h3>✅ Configuración aplicada a todas las instancias</h3>";
        echo "<p>Todas las instancias ahora usan la misma configuración.</p>";
        echo "</div>";
        
        // Limpiar cache nuevamente
        cache_helper::purge_all();
        echo "<p>✅ Caches limpiados nuevamente</p>";
        
    } else {
        echo "<p><a href='?apply=1' style='background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔧 Aplicar esta configuración a TODAS las instancias</a></p>";
    }
}

echo "<hr>";
echo "<p><a href='{$CFG->wwwroot}/my/'>← Volver a Mi Panel</a></p>";
?>
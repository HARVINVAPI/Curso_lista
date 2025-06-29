<?php
/**
 * Script temporal para forzar actualización del plugin
 */

require_once(__DIR__ . '/../../config.php');
require_login();

// Solo para administradores
if (!is_siteadmin()) {
    die('Solo administradores');
}

// Limpiar cache de Moodle
cache_helper::purge_by_event('changesincourse');
cache_helper::purge_by_event('changesincoursecat');

// Purgar todos los cache del bloque
try {
    $cache = cache::make('block_curso_lista', 'courses');
    $cache->purge();
} catch (Exception $e) {
    echo "Cache courses no encontrado\n";
}

try {
    $cache = cache::make('block_curso_lista', 'progress');
    $cache->purge();
} catch (Exception $e) {
    echo "Cache progress no encontrado\n";
}

try {
    $cache = cache::make('block_curso_lista', 'images');
    $cache->purge();
} catch (Exception $e) {
    echo "Cache images no encontrado\n";
}

// Forzar recarga del plugin en la DB
$DB->set_field('config_plugins', 'value', time(), array('plugin' => 'block_curso_lista', 'name' => 'version'));

// Invalidar cache de configuraciones de bloques
cache_helper::invalidate_by_definition('core', 'config', array(), 'block_curso_lista');

echo "<h2>✅ Cache limpiado y plugin refrescado</h2>";
echo "<p>Ahora ve al bloque y prueba la configuración.</p>";
echo "<p><a href='" . $CFG->wwwroot . "/my/'>Ir a Mi Panel</a></p>";
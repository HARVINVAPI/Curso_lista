<?php
defined('MOODLE_INTERNAL') || die();

function render_progress_circle($progress) {
    // Redondear progreso para evitar decimales largos
    $progress = round($progress);
    $unique_id = uniqid(); // Generar un identificador único para el gradiente

    // HTML para la barra de progreso circular
    $html = '
    <div class="circular-progress-container">
        <svg class="progress-ring" width="100" height="100">
            <defs>
                <linearGradient id="gradient-uniqueId" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#667eea; stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#764ba2; stop-opacity:1" />
                </linearGradient>
            </defs>
            <circle class="progress-ring-background" r="45" cx="50" cy="50"></circle>
            <circle class="progress-fill" 
                    r="45" 
                    cx="50" 
                    cy="50" 
                    fill="none"
                    stroke="#764ba2"
                    stroke-width="3"
                    stroke-linecap="round"
                    transform="rotate(-90 50 50)"
                    data-progress="' . $progress . '"
                    data-circumference="' . $circumference . '"
                    data-offset="' . $offset . '"
                    style="stroke-dasharray: ' . $circumference . '; stroke-dashoffset: ' . $circumference . ';">
            </circle>
        </svg>
        <div class="progress-value">' . $progress . '%</div>
    </div>';

    return $html;
}
?>

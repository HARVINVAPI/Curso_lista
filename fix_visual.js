/**
 * Script de corrección visual para círculos SVG desfasados
 * Ejecutar en la consola para corregir inmediatamente
 */

function fixVisualMisalignment() {
    console.log('🎯 CORRECCIÓN VISUAL: Iniciando diagnóstico y reparación...');
    
    const containers = document.querySelectorAll('.progress-container-80');
    
    containers.forEach((container, index) => {
        const bgCircle = container.querySelector('.progress-bg');
        const progressCircle = container.querySelector('.progress-fill');
        const svg = container.querySelector('svg');
        const percentage = container.querySelector('.progress-percentage');
        const circleContainer = container.querySelector('.progress-circle-container');
        
        if (!progressCircle || !bgCircle || !svg) {
            console.warn(`❌ Contenedor ${index + 1}: Elementos SVG faltantes`);
            return;
        }
        
        const progress = parseFloat(progressCircle.getAttribute('data-progress')) || 0;
        
        console.log(`🔧 Reparando contenedor ${index + 1}: ${progress}%`);
        
        // 1. Sincronizar ambos círculos (fondo y progreso)
        const centerX = 32;
        const centerY = 32;
        const radius = 26;
        
        // Asegurar que ambos círculos tengan la misma posición
        bgCircle.setAttribute('cx', centerX);
        bgCircle.setAttribute('cy', centerY);
        bgCircle.setAttribute('r', radius);
        bgCircle.setAttribute('stroke', '#e0e0e0');
        bgCircle.setAttribute('stroke-width', '3');
        bgCircle.setAttribute('fill', 'none');
        
        progressCircle.setAttribute('cx', centerX);
        progressCircle.setAttribute('cy', centerY);
        progressCircle.setAttribute('r', radius);
        progressCircle.setAttribute('stroke', '#764ba2');
        progressCircle.setAttribute('stroke-width', '3');
        progressCircle.setAttribute('fill', 'none');
        progressCircle.setAttribute('stroke-linecap', 'round');
        
        // 2. Configurar rotación correcta para que empiece arriba
        progressCircle.setAttribute('transform', `rotate(-90 ${centerX} ${centerY})`);
        
        // 3. Configurar animación del progreso
        const circumference = 2 * Math.PI * radius; // 163.36
        const offset = circumference - (progress / 100) * circumference;
        
        progressCircle.style.strokeDasharray = circumference;
        progressCircle.style.strokeDashoffset = offset;
        progressCircle.style.transition = 'none';
        
        // 4. Asegurar posicionamiento correcto del contenedor
        if (circleContainer) {
            circleContainer.style.cssText = `
                position: relative !important;
                width: 64px !important;
                height: 64px !important;
                margin-top: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            `;
        }
        
        // 5. Posicionar SVG correctamente
        svg.style.cssText = `
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: block !important;
            overflow: visible !important;
        `;
        
        // 6. Centrar porcentaje perfectamente
        if (percentage) {
            percentage.textContent = progress + '%';
            percentage.style.cssText = `
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                z-index: 15 !important;
                margin: 0 !important;
                padding: 0 !important;
                font-size: 0.9rem !important;
                font-weight: 700 !important;
                color: #2c3e50 !important;
                line-height: 1 !important;
                pointer-events: none !important;
                display: block !important;
            `;
        }
        
        console.log(`✅ Contenedor ${index + 1} reparado:`, {
            progress: `${progress}%`,
            circumference: circumference,
            offset: offset,
            bgPosition: `${bgCircle.getAttribute('cx')},${bgCircle.getAttribute('cy')}`,
            progressPosition: `${progressCircle.getAttribute('cx')},${progressCircle.getAttribute('cy')}`,
            synchronized: bgCircle.getAttribute('cx') === progressCircle.getAttribute('cx')
        });
    });
    
    console.log('🎯 CORRECCIÓN VISUAL COMPLETADA');
}

// Función para verificar sincronización
function checkSynchronization() {
    console.group('🔍 Verificación de Sincronización');
    
    const containers = document.querySelectorAll('.progress-container-80');
    
    containers.forEach((container, index) => {
        const bgCircle = container.querySelector('.progress-bg');
        const progressCircle = container.querySelector('.progress-fill');
        
        if (bgCircle && progressCircle) {
            const bgCenter = `${bgCircle.getAttribute('cx')},${bgCircle.getAttribute('cy')}`;
            const progressCenter = `${progressCircle.getAttribute('cx')},${progressCircle.getAttribute('cy')}`;
            const isSynchronized = bgCenter === progressCenter;
            
            console.log(`Contenedor ${index + 1}:`, {
                bgCenter: bgCenter,
                progressCenter: progressCenter,
                synchronized: isSynchronized ? '✅' : '❌',
                progress: progressCircle.getAttribute('data-progress') + '%'
            });
        }
    });
    
    console.groupEnd();
}

// Ejecutar automáticamente si hay círculos desfasados
setTimeout(() => {
    const circles = document.querySelectorAll('.progress-fill');
    if (circles.length > 0) {
        console.log('🚨 Detectados círculos SVG - Ejecutando corrección automática...');
        fixVisualMisalignment();
    }
}, 1000);

// Hacer funciones disponibles globalmente
window.fixVisualMisalignment = fixVisualMisalignment;
window.checkSynchronization = checkSynchronization;

console.log('🛠️ Funciones de corrección visual disponibles:', {
    fixVisualMisalignment: 'Corregir desfase visual inmediatamente',
    checkSynchronization: 'Verificar sincronización de círculos'
});
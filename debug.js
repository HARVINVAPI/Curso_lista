/**
 * Script de debug para Curso Lista
 * Usar en la consola del navegador para diagnosticar problemas
 */

// Función para debug completo
function debugCursoLista() {
    console.group('🔍 Debug Curso Lista');
    
    // 1. Verificar si el script principal está cargado
    console.log('Script principal cargado:', typeof window.CursoLista !== 'undefined');
    
    // 2. Verificar elementos del DOM
    const blocks = document.querySelectorAll('.block_curso_lista-info-element');
    const courseBlocks = document.querySelectorAll('.course-block');
    const progressContainers = document.querySelectorAll('.progress-container-80');
    const progressCircles = document.querySelectorAll('.progress-fill');
    
    console.log('Elementos encontrados:', {
        blocks: blocks.length,
        courseBlocks: courseBlocks.length,
        progressContainers: progressContainers.length,
        progressCircles: progressCircles.length
    });
    
    // 3. Verificar cada círculo de progreso
    progressCircles.forEach((circle, index) => {
        const progress = circle.getAttribute('data-progress');
        const strokeDasharray = circle.style.strokeDasharray;
        const strokeDashoffset = circle.style.strokeDashoffset;
        const stroke = circle.getAttribute('stroke');
        const transform = circle.getAttribute('transform');
        
        console.log(`Círculo ${index + 1}:`, {
            progress: progress + '%',
            stroke: stroke,
            strokeDasharray: strokeDasharray,
            strokeDashoffset: strokeDashoffset,
            transform: transform,
            isAnimated: circle.classList.contains('animated')
        });
    });
    
    // 4. Verificar CSS cargado
    const stylesheets = Array.from(document.styleSheets).filter(sheet => 
        sheet.href && sheet.href.includes('curso_lista')
    );
    console.log('CSS de CursoLista cargado:', stylesheets.length > 0);
    
    console.groupEnd();
}

// Función para forzar animación mejorada
function forceAnimation() {
    console.log('🚀 Forzando animación de círculos...');
    
    // Primero limpiar todas las animaciones existentes
    document.querySelectorAll('.progress-fill').forEach(circle => {
        circle.classList.remove('curso_lista-animated', 'animated');
        circle.style.transition = 'none';
        circle.style.strokeDashoffset = '163.36';
    });
    
    // Esperar un momento y luego animar
    setTimeout(() => {
        document.querySelectorAll('.progress-fill').forEach((circle, index) => {
            const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
            const circumference = 163.36;
            const targetOffset = circumference - (progress / 100) * circumference;
            
            // Configurar SVG
            circle.setAttribute('stroke', '#764ba2');
            circle.setAttribute('stroke-width', '3');
            circle.setAttribute('stroke-linecap', 'round');
            circle.setAttribute('fill', 'none');
            
            // Configurar animación
            circle.style.strokeDasharray = circumference;
            circle.style.strokeDashoffset = circumference;
            
            setTimeout(() => {
                circle.style.transition = 'stroke-dashoffset 2s ease-out';
                circle.style.strokeDashoffset = targetOffset;
                
                // Animar texto
                const container = circle.closest('.progress-container-80');
                if (container) {
                    const percentage = container.querySelector('.progress-percentage');
                    if (percentage) {
                        percentage.textContent = '0%';
                        let current = 0;
                        const increment = progress / 40;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= progress) {
                                current = progress;
                                clearInterval(timer);
                            }
                            percentage.textContent = Math.round(current) + '%';
                        }, 50);
                    }
                }
                
                circle.classList.add('curso_lista-animated');
            }, 200 + index * 150);
        });
    }, 100);
}

// Función para reinicializar todo
function reinitCursoLista() {
    console.log('🔄 Reinicializando CursoLista...');
    
    if (window.CursoLista) {
        window.CursoLista.reset();
    } else {
        // Reinicialización manual
        document.querySelectorAll('.progress-fill').forEach(circle => {
            circle.classList.remove('animated');
            circle.style.strokeDashoffset = '163.36';
            circle.style.transition = 'none';
        });
        
        setTimeout(() => {
            forceAnimation();
        }, 500);
    }
}

// Función para verificar responsive
function testResponsive() {
    console.group('📱 Test Responsive');
    
    const courseBlocks = document.querySelectorAll('.course-block');
    
    courseBlocks.forEach((block, index) => {
        const computedStyle = window.getComputedStyle(block);
        const parent = block.closest('.block-region-side-pre, .block-region-side-post, .drawercontent');
        
        console.log(`Bloque ${index + 1}:`, {
            isInSidebar: !!parent,
            sidebarType: parent ? parent.className : 'none',
            display: computedStyle.display,
            gridTemplateColumns: computedStyle.gridTemplateColumns,
            flexDirection: computedStyle.flexDirection
        });
    });
    
    console.groupEnd();
}

// Función para verificar alineación
function checkAlignment() {
    console.group('📐 Verificación de Alineación');
    
    const containers = document.querySelectorAll('.progress-container-80');
    
    containers.forEach((container, index) => {
        const circleContainer = container.querySelector('.progress-circle-container');
        const svg = container.querySelector('svg');
        const percentage = container.querySelector('.progress-percentage');
        
        if (circleContainer && svg && percentage) {
            const containerRect = circleContainer.getBoundingClientRect();
            const svgRect = svg.getBoundingClientRect();
            const percentageRect = percentage.getBoundingClientRect();
            
            console.log(`Contenedor ${index + 1}:`, {
                containerSize: `${containerRect.width}x${containerRect.height}`,
                svgSize: `${svgRect.width}x${svgRect.height}`,
                svgPosition: {
                    top: svgRect.top - containerRect.top,
                    left: svgRect.left - containerRect.left
                },
                percentagePosition: {
                    top: percentageRect.top - containerRect.top,
                    left: percentageRect.left - containerRect.left
                },
                isAligned: Math.abs((svgRect.top + svgRect.height/2) - (percentageRect.top + percentageRect.height/2)) < 2
            });
        }
    });
    
    console.groupEnd();
}

// Función para forzar alineación correcta LIMPIA
function forceAlignment() {
    console.log('🎯 Forzando alineación correcta después de limpiar CSS...');
    
    document.querySelectorAll('.progress-container-80').forEach((container, index) => {
        const circleContainer = container.querySelector('.progress-circle-container');
        const svg = container.querySelector('svg');
        const percentage = container.querySelector('.progress-percentage');
        
        if (circleContainer && svg && percentage) {
            // Limpiar estilos conflictivos primero
            percentage.style.cssText = '';
            svg.style.cssText = '';
            circleContainer.style.cssText = '';
            
            // Aplicar estilos limpos y específicos
            circleContainer.style.cssText = `
                position: relative !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 64px !important;
                height: 64px !important;
                margin-top: 10px !important;
            `;
            
            svg.style.cssText = `
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                display: block !important;
                overflow: visible !important;
            `;
            
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
                width: auto !important;
                height: auto !important;
            `;
            
            console.log(`✅ Contenedor ${index + 1} alineado correctamente`);
        }
    });
    
    console.log('🎯 Alineación forzada completada');
}

// Hacer funciones globales disponibles
window.debugSVG = debugSVG;
window.fixSVGNow = fixSVGNow;
window.debugCursoLista = debugCursoLista;
window.forceAlignment = forceAlignment;
window.checkAlignment = checkAlignment;

// Activar debug automático si está habilitado
if (window.CursoLista && window.CursoLista.config.debug) {
    setTimeout(debugCursoLista, 2000);
}

// Función específica para diagnosticar SVG
function debugSVG() {
    console.group('🔍 Debug SVG Específico');
    
    const circles = document.querySelectorAll('.progress-fill');
    
    circles.forEach((circle, index) => {
        const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
        const circumference = 163.36;
        const expectedOffset = circumference - (progress / 100) * circumference;
        const currentOffset = parseFloat(circle.style.strokeDashoffset) || 0;
        
        console.log(`🎯 Círculo ${index + 1}:`, {
            progress: `${progress}%`,
            expectedOffset: expectedOffset,
            currentOffset: currentOffset,
            isCorrect: Math.abs(expectedOffset - currentOffset) < 1,
            strokeDasharray: circle.style.strokeDasharray,
            stroke: circle.getAttribute('stroke'),
            strokeWidth: circle.getAttribute('stroke-width'),
            opacity: circle.getAttribute('opacity'),
            transform: circle.getAttribute('transform'),
            calculation: `163.36 - (${progress}/100 * 163.36) = ${expectedOffset}`
        });
        
        // Verificar círculo de fondo
        const container = circle.closest('.progress-container-80');
        if (container) {
            const bgCircle = container.querySelector('.progress-bg');
            if (bgCircle) {
                console.log(`   🔘 Círculo fondo:`, {
                    stroke: bgCircle.getAttribute('stroke'),
                    strokeWidth: bgCircle.getAttribute('stroke-width'),
                    samePosition: bgCircle.getAttribute('cx') === circle.getAttribute('cx')
                });
            }
        }
    });
    
    console.groupEnd();
}

// Función para forzar corrección específica del SVG
function fixSVGNow() {
    console.log('🔧 Forzando corrección inmediata de SVG...');
    
    document.querySelectorAll('.progress-fill').forEach((circle, index) => {
        const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
        const circumference = 163.36;
        const targetOffset = circumference - (progress / 100) * circumference;
        
        // Resetear completamente
        circle.style.transition = 'none';
        circle.style.strokeDasharray = circumference;
        circle.style.strokeDashoffset = circumference;
        
        // Aplicar inmediatamente
        setTimeout(() => {
            circle.style.transition = 'stroke-dashoffset 1s ease-out';
            circle.style.strokeDashoffset = targetOffset;
            console.log(`✅ SVG ${index + 1}: ${progress}% → ${targetOffset}`);
        }, 10);
    });
}

console.log('🛠️ Funciones de debug disponibles:', {
    debugCursoLista: 'Información completa del estado',
    forceAnimation: 'Forzar animación de progreso',
    reinitCursoLista: 'Reinicializar todo el sistema',
    testResponsive: 'Verificar diseño responsive',
    checkAlignment: 'Verificar alineación de círculos',
    forceAlignment: 'Forzar alineación correcta',
    debugSVG: 'Diagnosticar problemas específicos del SVG',
    fixSVGNow: 'Corregir SVG inmediatamente'
});
/**
 * Curso Lista Block - Script Completo y Funcional
 * Version: 4.0.1 - Con correcciones para barras de progreso
 */

(function() {
    'use strict';
    
    // Configuración
    const CONFIG = {
        radius: 26,
        circumference: 163.36, // 2 * Math.PI * 26
        animationDuration: 1800,
        debug: true // Activar debug temporalmente
    };
    
    // Estado
    let initialized = false;
    
    /**
     * Log de debug
     */
    function log(message, data) {
        if (CONFIG.debug) {
            console.log(`🎯 Curso Lista: ${message}`, data || '');
        }
    }
    
    /**
     * Inicializa todos los círculos de progreso
     */
    function initializeProgressCircles() {
        log('=== Iniciando círculos de progreso ===');
        
        // Esperar a que el DOM esté completamente cargado
        if (!document.querySelector('.block_curso_lista-info-element')) {
            log('Esperando a que el bloque CusoLista se cargue...');
            setTimeout(initializeProgressCircles, 100);
            return;
        }
        
        // Buscar todos los círculos que NO han sido animados
        const circles = document.querySelectorAll('.progress-fill:not(.curso_lista-animated)');
        log(`Encontrados ${circles.length} círculos nuevos de progreso`);
        
        if (circles.length === 0) {
            log('No se encontraron círculos nuevos');
            return;
        }
        
        circles.forEach((circle, index) => {
            // Crear ID único basado en posición y contenedor
            const courseBlock = circle.closest('.course-block');
            const blockContainer = circle.closest('.block_curso_lista-info-element');
            const courseId = courseBlock ? courseBlock.getAttribute('data-course-id') : null;
            const blockId = blockContainer ? blockContainer.closest('[class*="block"]').id : 'unknown';
            const uniqueId = `${blockId}_${courseId}_${index}`;
            
            // Obtener el progreso del atributo data-progress
            const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
            log(`Procesando círculo ${index + 1} (ID: ${uniqueId})`, { progress });
            
            // Marcar inmediatamente para evitar doble procesamiento
            circle.classList.add('curso_lista-animated');
            
            // Configurar el círculo SVG
            setupCircle(circle, progress, index);
        });
        
        initialized = true;
        log('=== Inicialización completada ===');
    }
    
    /**
     * Configura y anima un círculo individual - CORREGIDO
     */
    function setupCircle(circle, progress, index) {
        const { circumference } = CONFIG;
        
        // IMPORTANTE: Para un círculo que va de 0% a 100%, necesitamos:
        // - stroke-dasharray: longitud total del círculo
        // - stroke-dashoffset inicial: longitud total (vacío)
        // - stroke-dashoffset final: longitud total - (progreso * longitud total)
        
        const targetOffset = circumference - (progress / 100) * circumference;
        
        log(`Configurando círculo ${index + 1}:`, {
            progress: `${progress}%`,
            circumference: circumference,
            targetOffset: targetOffset,
            calculation: `${circumference} - (${progress}/100 * ${circumference}) = ${targetOffset}`
        });
        
        // Configurar atributos SVG básicos
        circle.setAttribute('stroke', '#764ba2');
        circle.setAttribute('stroke-width', '3');
        circle.setAttribute('stroke-linecap', 'round');
        circle.setAttribute('fill', 'none');
        circle.setAttribute('opacity', '1');
        
        // SOLUCIÓN APLICADA: Corregir desfase vertical
        circle.style.transform = 'translateY(0px)';
        circle.style.transformOrigin = 'center';
        
        // CRÍTICO: Configurar correctamente dasharray y dashoffset
        circle.style.strokeDasharray = `${circumference}`;
        circle.style.strokeDashoffset = `${circumference}`; // Empezar completamente vacío
        circle.style.transition = 'none';
        
        // Verificar que el círculo sea visible
        log(`Verificando círculo ${index + 1}:`, {
            stroke: circle.getAttribute('stroke'),
            strokeWidth: circle.getAttribute('stroke-width'),
            dashArray: circle.style.strokeDasharray,
            dashOffset: circle.style.strokeDashoffset,
            visible: circle.style.opacity !== '0'
        });
        
        // Forzar un repaint
        circle.getBoundingClientRect();
        
        // Animar después de un delay
        setTimeout(() => {
            // Activar transición suave
            circle.style.transition = 'stroke-dashoffset 1.5s ease-in-out';
            circle.style.strokeDashoffset = `${targetOffset}`;
            
            log(`Animando círculo ${index + 1}: ${circumference} → ${targetOffset}`);
            
            // Animar el texto del porcentaje
            const container = circle.closest('.progress-container-80');
            if (container) {
                const percentageElement = container.querySelector('.progress-percentage');
                if (percentageElement) {
                    percentageElement.textContent = '0%';
                    animateValue(percentageElement, 0, progress, 1500);
                }
            }
            
        }, 300 + (index * 200));
    }
    
    /**
     * Anima el valor numérico del progreso
     */
    function animateValue(element, start, end, duration) {
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Función de easing
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(start + (end - start) * easeOut);
            
            element.textContent = current + '%';
            
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }
    
    /**
     * Función de debug para verificar el estado
     */
    function debugCircles() {
        const circles = document.querySelectorAll('.progress-fill');
        
        console.group('🔍 Debug de Círculos de Progreso');
        console.log('Total de círculos encontrados:', circles.length);
        
        circles.forEach((circle, index) => {
            const progress = circle.getAttribute('data-progress');
            const dasharray = circle.style.strokeDasharray;
            const dashoffset = circle.style.strokeDashoffset;
            const transform = circle.getAttribute('transform');
            const computed = window.getComputedStyle(circle);
            const container = circle.closest('.progress-container-80');
            
            console.log(`Círculo ${index + 1}:`, {
                progress: `${progress}%`,
                dasharray: dasharray,
                dashoffset: dashoffset,
                transform: transform,
                computedStroke: computed.stroke,
                computedStrokeWidth: computed.strokeWidth,
                hasGradient: computed.stroke.includes('gradient'),
                containerFound: !!container,
                animated: circle.classList.contains('animated')
            });
        });
        
        console.groupEnd();
    }
    
    /**
     * Reinicializa todos los círculos
     */
    function resetAndInit() {
        log('Reinicializando todos los círculos');
        
        // Limpiar estado
        initialized = false;
        
        // Resetear todos los círculos
        const circles = document.querySelectorAll('.progress-fill');
        circles.forEach(circle => {
            circle.classList.remove('curso_lista-animated', 'animated');
            circle.style.transition = 'none';
            circle.style.strokeDashoffset = CONFIG.circumference;
        });
        
        // Reinicializar después de un breve delay
        setTimeout(initializeProgressCircles, 100);
    }
    
    /**
     * Configurar observador para contenido dinámico
     */
    function setupMutationObserver() {
        if (!window.MutationObserver) {
            log('MutationObserver no soportado');
            return;
        }
        
        const observer = new MutationObserver((mutations) => {
            const hasNewContent = mutations.some(mutation => {
                return Array.from(mutation.addedNodes).some(node => {
                    return node.nodeType === 1 && (
                        node.classList?.contains('course-block') ||
                        node.querySelector?.('.course-block') ||
                        node.classList?.contains('block_curso_lista-info-element')
                    );
                });
            });
            
            if (hasNewContent) {
                log('Nuevo contenido detectado, verificando círculos nuevos');
                setTimeout(initializeProgressCircles, 200);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        log('MutationObserver configurado');
    }
    
    /**
     * Verificar soporte de SVG
     */
    function checkSVGSupport() {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        return !!(svg && svg.createSVGRect);
    }
    
    /**
     * Inicialización principal
     */
    function init() {
        log('🚀 CusoLista - Iniciando v4.0.1');
        
        // Verificar soporte SVG
        if (!checkSVGSupport()) {
            console.error('Curso Lista: El navegador no soporta SVG');
            return;
        }
        
        // Intentar inicializar inmediatamente
        initializeProgressCircles();
        
        // Configurar observador
        setupMutationObserver();
        
        // Reintentos adicionales con delays incrementales
        const retryDelays = [500, 1000, 2000];
        retryDelays.forEach((delay, index) => {
            setTimeout(() => {
                const unanimated = document.querySelectorAll('.progress-fill:not(.animated)');
                if (unanimated.length > 0) {
                    log(`Reintento ${index + 1}: ${unanimated.length} círculos sin animar`);
                    unanimated.forEach(circle => {
                        circle.classList.remove('curso_lista-animated');
                    });
                    initializeProgressCircles();
                }
            }, delay);
        });
    }
    
    // Múltiples puntos de entrada para asegurar inicialización
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Inicialización adicional cuando todo esté cargado
    window.addEventListener('load', () => {
        log('Window loaded, verificación final');
        setTimeout(() => {
            const unanimated = document.querySelectorAll('.progress-fill:not(.animated)');
            if (unanimated.length > 0) {
                log(`Load event: ${unanimated.length} círculos sin animar`);
                initializeProgressCircles();
            }
        }, 300);
    });
    
    // Compatibilidad con Moodle
    if (typeof M !== 'undefined') {
        log('Entorno Moodle detectado');
        
        if (typeof M.util !== 'undefined' && typeof M.util.js_complete !== 'undefined') {
            M.util.js_complete('block_curso_lista');
        }
    }
    
    // Exponer API global para debugging
    window.CusoLista = {
        init: initializeProgressCircles,
        reset: resetAndInit,
        debug: debugCircles,
        config: CONFIG,
        setDebug: (enabled) => { 
            CONFIG.debug = enabled;
            console.log(`CusoLista debug ${enabled ? 'activado' : 'desactivado'}`);
        },
        version: '4.0.2-CLEAN',
        forceAnimate: function() {
            const circles = document.querySelectorAll('.progress-fill');
            circles.forEach((circle, index) => {
                const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
                setupCircle(circle, progress, index);
            });
        },
        forceFixAlignment: forceFixAlignment, // Nueva función expuesta
        getStatus: function() {
            const total = document.querySelectorAll('.progress-fill').length;
            const animated = document.querySelectorAll('.progress-fill.animated').length;
            return {
                total: total,
                animated: animated,
                pending: total - animated,
                initialized: initialized
            };
        }
    };
    
    // Compatibilidad con versiones anteriores
    window.CusoLista80px = window.CusoLista;
    window.CusoListaProgress = window.CusoLista;
    
    log('Script cargado completamente');
    
    // Función de emergencia CORREGIDA para forzar la visualización de SVG
    function emergencyFixSVG() {
        const circles = document.querySelectorAll('.progress-fill');
        log(`🚨 EMERGENCIA: Ejecutando fix en ${circles.length} círculos`);
        
        circles.forEach((circle, index) => {
            // Aplicar configuración básica SIEMPRE
            circle.setAttribute('stroke', '#764ba2');
            circle.setAttribute('stroke-width', '3');
            circle.setAttribute('stroke-linecap', 'round');
            circle.setAttribute('fill', 'none');
            circle.setAttribute('opacity', '1');
            
            // Obtener progreso
            const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
            const circumference = 163.36;
            const targetOffset = circumference - (progress / 100) * circumference;
            
            log(`🔧 Fix círculo ${index + 1}: ${progress}% → offset: ${targetOffset}`);
            
            // FORZAR configuración correcta
            circle.style.strokeDasharray = `${circumference}`;
            circle.style.strokeDashoffset = `${circumference}`; // Empezar vacío
            circle.style.transition = 'none';
            
            // Forzar repaint
            void circle.offsetHeight;
            
            // Animar inmediatamente
            setTimeout(() => {
                circle.style.transition = 'stroke-dashoffset 1.5s ease-in-out';
                circle.style.strokeDashoffset = `${targetOffset}`;
                
                log(`✅ Círculo ${index + 1} animado: ${circumference} → ${targetOffset}`);
                
                // Actualizar porcentaje
                const container = circle.closest('.progress-container-80');
                if (container) {
                    const percentage = container.querySelector('.progress-percentage');
                    if (percentage) {
                        percentage.textContent = progress + '%';
                    }
                }
            }, 50 + index * 50);
        });
    }
    
    // Función para corregir inmediatamente cualquier desfase - MEJORADA
    function forceFixAlignment() {
        const circles = document.querySelectorAll('.progress-fill');
        log(`🔧 CORRECCIÓN FORZADA: ${circles.length} círculos`);
        
        circles.forEach((circle, index) => {
            const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
            const circumference = 163.36;
            const targetOffset = circumference - (progress / 100) * circumference;
            
            // Configuración directa sin transiciones
            circle.setAttribute('stroke', '#764ba2');
            circle.setAttribute('stroke-width', '3');
            circle.setAttribute('fill', 'none');
            circle.setAttribute('opacity', '1');
            
            // CRÍTICO: Asegurar que la rotación sea correcta
            circle.setAttribute('transform', 'rotate(-90 32 32)');
            circle.style.transformOrigin = 'center';
            circle.style.transform = 'translateY(0px)'; // Corrección del desfase vertical
            
            // Configurar dasharray y offset correctos
            circle.style.strokeDasharray = `${circumference}`;
            circle.style.strokeDashoffset = `${targetOffset}`;
            circle.style.transition = 'none';
            
            // Verificar posicionamiento del contenedor
            const container = circle.closest('.progress-container-80');
            if (container) {
                const circleContainer = container.querySelector('.progress-circle-container');
                if (circleContainer) {
                    // Asegurar posicionamiento correcto del contenedor
                    circleContainer.style.position = 'relative';
                    circleContainer.style.display = 'flex';
                    circleContainer.style.alignItems = 'center';
                    circleContainer.style.justifyContent = 'center';
                }
                
                // Actualizar porcentaje
                const percentage = container.querySelector('.progress-percentage');
                if (percentage) {
                    percentage.textContent = progress + '%';
                    percentage.style.position = 'absolute';
                    percentage.style.top = '50%';
                    percentage.style.left = '50%';
                    percentage.style.transform = 'translate(-50%, -50%)';
                    percentage.style.zIndex = '15';
                }
            }
            
            log(`✅ Círculo ${index + 1} corregido: ${progress}% → ${targetOffset}`);
        });
    }
    
    // Ejecutar fix de emergencia en diferentes momentos
    setTimeout(emergencyFixSVG, 100);
    setTimeout(emergencyFixSVG, 1000);
    setTimeout(forceFixAlignment, 2000); // Corrección final
    
})();
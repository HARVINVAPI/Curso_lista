/**
 * Form Preview JavaScript para CusoLista Block - VERSION CORREGIDA
 */

document.addEventListener("DOMContentLoaded", function() {
    
    // Función para sincronizar color pickers con campos de texto
    function initColorPickers() {
        var colorFields = [
            'config_title_color',
            'config_buttoncolor', 
            'config_buttoncolor_hover',
            'config_gradient_start',
            'config_gradient_end', 
            'config_gradient_start_hover',
            'config_gradient_end_hover'
        ];
        
        colorFields.forEach(function(fieldId) {
            var textInput = document.getElementById(fieldId);
            var colorPicker = document.getElementById(fieldId + '_picker');
            var preview = document.getElementById(fieldId + '_preview');
            
            if (textInput && colorPicker && preview) {
                // Sincronizar picker cuando cambia el texto
                textInput.addEventListener('input', function() {
                    var color = this.value;
                    if (isValidHexColor(color)) {
                        colorPicker.value = color;
                        preview.style.backgroundColor = color;
                        // Actualizar previews principales
                        updateMainPreviews();
                    }
                });
                
                // Sincronizar texto cuando cambia el picker
                colorPicker.addEventListener('input', function() {
                    var color = this.value;
                    textInput.value = color;
                    preview.style.backgroundColor = color;
                    
                    // Actualizar previews principales
                    updateMainPreviews();
                });
                
                // Inicializar con valor actual
                var currentColor = textInput.value || textInput.getAttribute('value');
                if (currentColor && isValidHexColor(currentColor)) {
                    colorPicker.value = currentColor;
                    preview.style.backgroundColor = currentColor;
                }
            }
        });
    }
    
    // Validar color hexadecimal
    function isValidHexColor(color) {
        return /^#[0-9A-Fa-f]{6}$/.test(color);
    }
    
    // Función para actualizar los previews principales (botón y título)
    function updateMainPreviews() {
        updateButtonPreview();
        updateTitlePreview();
    }
    
    function updateButtonPreview() {
        var colorType = document.querySelector('[name="config_color_type"]');
        var solidColor = document.querySelector('[name="config_buttoncolor"]');
        var solidColorHover = document.querySelector('[name="config_buttoncolor_hover"]');
        var gradientStart = document.querySelector('[name="config_gradient_start"]');
        var gradientEnd = document.querySelector('[name="config_gradient_end"]');
        var gradientStartHover = document.querySelector('[name="config_gradient_start_hover"]');
        var gradientEndHover = document.querySelector('[name="config_gradient_end_hover"]');
        var preview = document.getElementById("button-preview");
        
        if (!preview) return;
        
        function darkenColor(hex, percent) {
            if (!hex || hex.indexOf('#') === -1) return hex;
            var num = parseInt(hex.replace('#', ''), 16);
            var amt = Math.round(2.55 * percent);
            var R = Math.max(0, (num >> 16) - amt);
            var B = Math.max(0, (num >> 8 & 0x00FF) - amt);
            var G = Math.max(0, (num & 0x0000FF) - amt);
            return '#' + (0x1000000 + R * 0x10000 + B * 0x100 + G).toString(16).slice(1);
        }
        
        if (colorType && colorType.value === "solid" && solidColor && solidColor.value) {
            preview.style.background = solidColor.value;
            preview.dataset.originalBg = solidColor.value;
            var hoverColor = solidColorHover && solidColorHover.value ? solidColorHover.value : darkenColor(solidColor.value, 20);
            preview.dataset.hoverBg = hoverColor;
        } else if (gradientStart && gradientEnd && gradientStart.value && gradientEnd.value) {
            var normalGradient = "linear-gradient(135deg, " + gradientStart.value + " 0%, " + gradientEnd.value + " 100%)";
            preview.style.background = normalGradient;
            preview.dataset.originalBg = normalGradient;
            var hoverStartColor = gradientStartHover && gradientStartHover.value ? gradientStartHover.value : darkenColor(gradientStart.value, 15);
            var hoverEndColor = gradientEndHover && gradientEndHover.value ? gradientEndHover.value : darkenColor(gradientEnd.value, 15);
            var hoverGradient = "linear-gradient(135deg, " + hoverStartColor + " 0%, " + hoverEndColor + " 100%)";
            preview.dataset.hoverBg = hoverGradient;
        }
    }
    
    function updateTitlePreview() {
        var titleColor = document.querySelector('[name="config_title_color"]');
        var titleSize = document.querySelector('[name="config_title_size"]');
        var titleWeight = document.querySelector('[name="config_title_weight"]');
        var preview = document.getElementById("title-preview");
        
        if (!preview) return;
        
        if (titleColor && titleColor.value) {
            preview.style.color = titleColor.value;
        }
        if (titleSize && titleSize.value) {
            preview.style.fontSize = titleSize.value;
        }
        if (titleWeight && titleWeight.value) {
            preview.style.fontWeight = titleWeight.value;
        }
    }
    
    // Inicializar todo
    setTimeout(function() {
        initColorPickers();
        updateMainPreviews();
        
        // Agregar listeners para cambios de tipo de color
        var colorType = document.querySelector('[name="config_color_type"]');
        if (colorType) {
            colorType.addEventListener('change', updateMainPreviews);
        }
        
        // Agregar listeners para cambios de tamaño y peso de título
        var titleSize = document.querySelector('[name="config_title_size"]');
        var titleWeight = document.querySelector('[name="config_title_weight"]');
        if (titleSize) titleSize.addEventListener('change', updateTitlePreview);
        if (titleWeight) titleWeight.addEventListener('change', updateTitlePreview);
    }, 200);
});

function darkenColorHex(hex, percent) {
    if (!hex || hex.indexOf('#') === -1) return hex;
    var num = parseInt(hex.replace('#', ''), 16);
    var amt = Math.round(2.55 * percent);
    var R = Math.max(0, (num >> 16) - amt);
    var B = Math.max(0, (num >> 8 & 0x00FF) - amt);
    var G = Math.max(0, (num & 0x0000FF) - amt);
    return '#' + (0x1000000 + R * 0x10000 + B * 0x100 + G).toString(16).slice(1);
}

function setGradient(start, end, startHover, endHover) {
    var typeSelect = document.querySelector('[name="config_color_type"]');
    var startInput = document.querySelector('[name="config_gradient_start"]');
    var endInput = document.querySelector('[name="config_gradient_end"]');
    var startHoverInput = document.querySelector('[name="config_gradient_start_hover"]');
    var endHoverInput = document.querySelector('[name="config_gradient_end_hover"]');
    
    // Calcular colores hover si no se proporcionan
    var calculatedStartHover = startHover || darkenColorHex(start, 15);
    var calculatedEndHover = endHover || darkenColorHex(end, 15);
    
    if (typeSelect) typeSelect.value = "gradient";
    if (startInput) startInput.value = start;
    if (endInput) endInput.value = end;
    if (startHoverInput) startHoverInput.value = calculatedStartHover;
    if (endHoverInput) endHoverInput.value = calculatedEndHover;
    
    // Actualizar color pickers y previews
    updateColorPickerAndPreview('config_gradient_start', start);
    updateColorPickerAndPreview('config_gradient_end', end);
    updateColorPickerAndPreview('config_gradient_start_hover', calculatedStartHover);
    updateColorPickerAndPreview('config_gradient_end_hover', calculatedEndHover);
    
    // Disparar eventos para actualizar previews
    if (typeSelect) typeSelect.dispatchEvent(new Event("change"));
    if (startInput) startInput.dispatchEvent(new Event("input"));
    if (endInput) endInput.dispatchEvent(new Event("input"));
    if (startHoverInput) startHoverInput.dispatchEvent(new Event("input"));
    if (endHoverInput) endHoverInput.dispatchEvent(new Event("input"));
    
    // Forzar actualización de previews
    setTimeout(function() {
        if (window.updateMainPreviews) {
            window.updateMainPreviews();
        }
        // Actualizar visibilidad de secciones
        if (window.initializeColorFields) {
            window.initializeColorFields();
        }
    }, 100);
}

function setSolidColor(color, hoverColor) {
    var typeSelect = document.querySelector('[name="config_color_type"]');
    var colorInput = document.querySelector('[name="config_buttoncolor"]');
    var hoverInput = document.querySelector('[name="config_buttoncolor_hover"]');
    
    // Calcular color hover si no se proporciona
    var calculatedHoverColor = hoverColor || darkenColorHex(color, 20);
    
    if (typeSelect) typeSelect.value = "solid";
    if (colorInput) colorInput.value = color;
    if (hoverInput) hoverInput.value = calculatedHoverColor;
    
    // Actualizar color pickers y previews
    updateColorPickerAndPreview('config_buttoncolor', color);
    updateColorPickerAndPreview('config_buttoncolor_hover', calculatedHoverColor);
    
    // Disparar eventos para actualizar previews
    if (typeSelect) typeSelect.dispatchEvent(new Event("change"));
    if (colorInput) colorInput.dispatchEvent(new Event("input"));
    if (hoverInput) hoverInput.dispatchEvent(new Event("input"));
    
    // Forzar actualización de previews
    setTimeout(function() {
        if (window.updateMainPreviews) {
            window.updateMainPreviews();
        }
        // Actualizar visibilidad de secciones
        if (window.initializeColorFields) {
            window.initializeColorFields();
        }
    }, 100);
}

function setTitleColor(color) {
    var titleColorInput = document.querySelector('[name="config_title_color"]');
    if (titleColorInput) {
        titleColorInput.value = color;
        updateColorPickerAndPreview('config_title_color', color);
        titleColorInput.dispatchEvent(new Event("input"));
        
        // Forzar actualización del preview del título
        setTimeout(function() {
            var preview = document.getElementById("title-preview");
            if (preview) {
                preview.style.color = color;
            }
        }, 50);
    }
}

// Función auxiliar para actualizar color picker y preview
function updateColorPickerAndPreview(fieldId, color) {
    var colorPicker = document.getElementById(fieldId + '_picker');
    var preview = document.getElementById(fieldId + '_preview');
    
    if (colorPicker) colorPicker.value = color;
    if (preview) preview.style.backgroundColor = color;
}

// Exponer funciones globalmente para que puedan ser llamadas desde los presets
window.updateMainPreviews = function() {
    var updateButtonPreview = function() {
        var colorType = document.querySelector('[name="config_color_type"]');
        var solidColor = document.querySelector('[name="config_buttoncolor"]');
        var solidColorHover = document.querySelector('[name="config_buttoncolor_hover"]');
        var gradientStart = document.querySelector('[name="config_gradient_start"]');
        var gradientEnd = document.querySelector('[name="config_gradient_end"]');
        var gradientStartHover = document.querySelector('[name="config_gradient_start_hover"]');
        var gradientEndHover = document.querySelector('[name="config_gradient_end_hover"]');
        var preview = document.getElementById("button-preview");
        
        if (!preview) return;
        
        function darkenColor(hex, percent) {
            if (!hex || hex.indexOf('#') === -1) return hex;
            var num = parseInt(hex.replace('#', ''), 16);
            var amt = Math.round(2.55 * percent);
            var R = Math.max(0, (num >> 16) - amt);
            var B = Math.max(0, (num >> 8 & 0x00FF) - amt);
            var G = Math.max(0, (num & 0x0000FF) - amt);
            return '#' + (0x1000000 + R * 0x10000 + B * 0x100 + G).toString(16).slice(1);
        }
        
        if (colorType && colorType.value === "solid" && solidColor && solidColor.value) {
            preview.style.background = solidColor.value;
            preview.dataset.originalBg = solidColor.value;
            var hoverColor = solidColorHover && solidColorHover.value ? solidColorHover.value : darkenColor(solidColor.value, 20);
            preview.dataset.hoverBg = hoverColor;
        } else if (gradientStart && gradientEnd && gradientStart.value && gradientEnd.value) {
            var normalGradient = "linear-gradient(135deg, " + gradientStart.value + " 0%, " + gradientEnd.value + " 100%)";
            preview.style.background = normalGradient;
            preview.dataset.originalBg = normalGradient;
            var hoverStartColor = gradientStartHover && gradientStartHover.value ? gradientStartHover.value : darkenColor(gradientStart.value, 15);
            var hoverEndColor = gradientEndHover && gradientEndHover.value ? gradientEndHover.value : darkenColor(gradientEnd.value, 15);
            var hoverGradient = "linear-gradient(135deg, " + hoverStartColor + " 0%, " + hoverEndColor + " 100%)";
            preview.dataset.hoverBg = hoverGradient;
        }
    };
    
    var updateTitlePreview = function() {
        var titleColor = document.querySelector('[name="config_title_color"]');
        var titleSize = document.querySelector('[name="config_title_size"]');
        var titleWeight = document.querySelector('[name="config_title_weight"]');
        var preview = document.getElementById("title-preview");
        
        if (!preview) return;
        
        if (titleColor && titleColor.value) {
            preview.style.color = titleColor.value;
        }
        if (titleSize && titleSize.value) {
            preview.style.fontSize = titleSize.value;
        }
        if (titleWeight && titleWeight.value) {
            preview.style.fontWeight = titleWeight.value;
        }
    };
    
    updateButtonPreview();
    updateTitlePreview();
};
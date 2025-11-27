$(document).ready(function() {
    
    
    // 1. Inicialización de Select2
    
    function initializeSelect2(selector) {
        $(selector).select2({
            placeholder: "Buscar y seleccionar encargado(s)",
            allowClear: true,
            width: '100%',
            // Opciones de estilo opcionales
            containerCssClass: 'select2-custom-container', 
            dropdownCssClass: 'select2-custom-dropdown'
        });
    }

    // Inicializar Select2 en ambos formularios
    initializeSelect2('#select-encargados-crear');
    initializeSelect2('#select-encargados-editar');



    // Confirmación de Eliminación

    const deleteLinks = document.querySelectorAll('a[href*="accion=eliminar"]');

    deleteLinks.forEach(link => {
        link.addEventListener('click', handleConfirmation);
    });

    function handleConfirmation(event) {
        event.preventDefault(); 
        
        
        const nombreBodega = event.target.closest('tr').querySelector('td:nth-child(2)').textContent;
        const url = event.currentTarget.href;

        if (confirm(`⚠️ ¿Desea confirmar la ELIMINACIÓN de la bodega: "${nombreBodega}"? Esta acción es irreversible.`)) {
            window.location.href = url;
        }
    }
    
    

    // Validación de Formulario

    const formCreacion = document.querySelector('#form-creacion');
    const formEdicion = document.querySelector('#form-edicion');
    
    // Validar en la creación
    if (formCreacion) {
        formCreacion.addEventListener('submit', (e) => validateForm(e, formCreacion));
    }

    // Validar en la edición
    if (formEdicion) {
        formEdicion.addEventListener('submit', (e) => validateForm(e, formEdicion));
    }

    function validateForm(event, form) {
        let isValid = true;
        const codigoInput = form.querySelector('input[name="codigo_identificador"]');
        const dotacionInput = form.querySelector('input[name="dotacion"]');
        
        // Validación del Código Identificador
        if (codigoInput.value.length > 5) {
            alert('❌ Error: El Código Identificador no debe exceder los 5 caracteres.');
            codigoInput.focus();
            isValid = false;
        }

        //Validación de la Dotación
        const dotacionValue = parseInt(dotacionInput.value);
        if (isNaN(dotacionValue) || dotacionValue <= 0) {
            alert('❌ Error: La Dotación Máxima debe ser un número entero positivo.');
            dotacionInput.focus();
            isValid = false;
        }
        
        if (!isValid) {
            event.preventDefault();
        }
    }

    const modal = document.getElementById("create-bodega-modal");
    const btn = document.getElementById("open-modal-btn");
    const span = document.getElementsByClassName("close-button")[0];

    if (btn) {
        btn.onclick = function() {
            modal.style.display = "block";
            $('#select-encargados-crear').select2({
                placeholder: "Buscar y seleccionar encargado(s)",
                allowClear: true,
                width: '100%'
            });
        }
    }

    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
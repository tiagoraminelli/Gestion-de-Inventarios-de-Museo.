
    // Limpiar campos del formulario
    document.getElementById('btnLimpiar').addEventListener('click', function() {
        const form = document.getElementById('formCrearPieza');
        form.reset(); // limpia inputs, selects y textarea

        // Limpiar selects con opción "Selecciona un donante" si es necesario
        const donanteSelect = form.querySelector('select[name="Donante_idDonante"]');
        if (donanteSelect) donanteSelect.selectedIndex = 0;

        // Limpiar preview de imagen si existe
        const fileInput = form.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = '';

        // Limpiar cualquier error mostrado
        const erroresDiv = form.querySelector('#erroresCrear');
        if (erroresDiv) erroresDiv.innerHTML = '';
    });


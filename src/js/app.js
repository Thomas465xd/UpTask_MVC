( function() {

    // Revisa si la clase .btn-eliminar existe
    // Si la clase existe, significa que hay por lo menos 1 proyecto.

    if(document.querySelector('.btn-eliminar')) {
        const btnEliminar = document.querySelectorAll('.btn-eliminar');
        
        // Itero sobre todos los resultados
        btnEliminar.forEach(btn => {
            btn.addEventListener('click', function(evento) {
                evento.preventDefault();
                // Selecciono el formulario del boton
                const formulario = btn.closest('.formulario-eliminar');

                // Lanzo mi alerta sweetalert
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Sí, eliminar!', 
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    // Valido el resultado, si es true, hago el submit al formulario.
                    if (result.isConfirmed) {
                        formulario.submit();
                    }
                })
            } );
        } );
    }
})();
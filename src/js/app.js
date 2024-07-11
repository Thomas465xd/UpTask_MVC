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

const mobileMenuBtn = document.querySelector("#mobile-menu");
const cerrarMenu = document.querySelector("#cerrar-menu");
const sidebar = document.querySelector(".sidebar");

if(mobileMenuBtn) {
    mobileMenuBtn.addEventListener("click", function() {
        sidebar.classList.toggle("mostrar");
    })
}

if(cerrarMenu) {
    cerrarMenu.addEventListener("click", function() {
        sidebar.classList.add("ocultar");

        setTimeout(() => {
            sidebar.classList.remove("mostrar");
            sidebar.classList.remove("ocultar")
        }, 300)
    })
}

// Elimina la clase de mostrar en un tamaño de tablet y mayores
const anchoPantalla = document.body.clientWidth;

window.addEventListener("resize", function() {
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768) {
        sidebar.classList.remove("mostrar");
    }
})
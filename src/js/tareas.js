// IIFE (Immediately Invoked Function Expression) - Permite que las variables solo funcionen en este archivo
( function() {

    // Obtener Tareas de cada proyecto
    obtenerTareas();
    let tareas = [];

    // Boton para mostrar el modal de agregar tarea
    const nuevaTarea = document.querySelector("#agregar-tarea");

    // Evento para mostrar el modal de agregar tarea
    nuevaTarea.addEventListener('click', mostrarFormulario);

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas();

            //console.log(tareas);

        } catch (error) {
            console.log("Error: ", error);
        }
    }

    function mostrarTareas() {

        limpiarTareas();

        if(tareas.length == 0) {
            const contenedorTareas = document.querySelector("#listado-tareas");

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = "No Hay Tareas";
            textoNoTareas.classList.add("no-tareas");

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        // "Diccionario" de estados
        const estados = {
            0: "Pendiente", 
            1: "Completa"
        }

        tareas.forEach(tarea => {
            //console.log(tarea);

            const contenedorTarea = document.createElement("LI")
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add("tarea");

            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = tarea.nombre

            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");

            //Botones 
            const btnEstadoTarea = document.createElement("BUTTON")
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            const btnEliminarTarea = document.createElement("BUTTON");
            btnEliminarTarea.classList.add("eliminar-tarea");
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = "Eliminar Tarea";

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector("#listado-tareas");
            listadoTareas.appendChild(contenedorTarea);
        })
    }

    // Funcion para mostrar el modal de agregar tarea
    function mostrarFormulario() {
        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Agrega una nueva tarea</legend>
                <div class="campo">
                    <label>Nombre de la tarea</label>
                    <input 
                        type="text" 
                        id="tarea"
                        placeholder="Añadir Tarea al Proyecto Actual" 
                        class="nombre-tarea"
                    >
                </div>
                <div class="opcionesTarea">
                    <input 
                        type="submit" 
                        class="submit-nueva-tarea" 
                        value="Agregar Tarea"
                    >
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector(".formulario");
            formulario.classList.add("animar");
        }, 100);

        // Evento para cerrar el modal de agregar tarea
        modal.addEventListener("click", function(evento) {
            evento.preventDefault();

            if(evento.target.classList.contains("cerrar-modal")) {
                const formulario = document.querySelector(".formulario");
                formulario.classList.add("cerrar");
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if(evento.target.classList.contains("submit-nueva-tarea")) {
                submitFormularioNuevaTarea();
            }
        })

        // Evento para agregar una nueva tarea


        // Se agrega el modal al body
        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea()  {
        const tarea = document.querySelector("#tarea").value.trim();

        //console.log(tarea);

        if(tarea === "") {
            // Mostrar una Alerta de Error
            mostarAlerta("El Nombre de la Tarea es Obligatorio", "error", document.querySelector(".formulario legend"));
            return;
        } 

        agregarTarea(tarea);
    }

    // Muestra un Mensaje en la interfaz
    function mostarAlerta(mensaje, tipo, referencia) {

        // Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector(".alerta");

        if(alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('div');
        alerta.classList.add("alerta", tipo);
        alerta.textContent = mensaje;

        //referencia.appendChild(alerta);

        //referencia.insertBefore(alerta, referencia.firstChild);

        // Insertar antes de la referencia
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        // Eliminar la alerta despues de 5 segundos
        setTimeout(() => {
            alerta.remove();
        }, 2000);
    }

    // Consultar el servidor para añadir una nueva tarea al poryecto actual
    async function agregarTarea(tarea) {

        // Construir la petición
        const datos = new FormData();
        datos.append("nombre", tarea);
        datos.append("proyectoId", obtenerProyecto());

        try {

            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            //console.log(resultado);

            // Mostrar una Alerta de Error
            mostarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".formulario legend"));

            if (resultado.tipo === "exito") {
                // Evitar doble click
                const opcionesTarea = document.querySelector(".opcionesTarea");
                opcionesTarea.remove();

                // Limpiar el formulario
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    modal.remove();

                    // Cargar Tareas mediante recargar la página
                    //window.location.reload();
                }, 2000)

                // Agregar el objeto de tarea al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea, 
                    estado: "0", 
                    proyectoId: resultado.proyectoId, 
                }

                tareas = [...tareas, tareaObj];
                mostrarTareas();

                //console.log(tareaObj);
            }

        } catch (error) {
            console.log("Error: ", error);
        }
    }

    function obtenerProyecto() {
        const ProyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(ProyectoParams.entries());
        return proyecto.id;
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector("#listado-tareas");
        
        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }
})();
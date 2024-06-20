<?php 

namespace Controllers;

use MVC\Router;
use Model\Tarea;
use Model\Usuario;
use Model\Proyecto;

class TareaController {
    public static function index(Router $router) {

        $proyectoId = $_GET['id'];

        if(!$proyectoId) {
            header("Location: /dashboard");
        }

        $proyecto = Proyecto::where('url', $proyectoId);
        //debug($proyecto);

        session_start();

        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
            header("Location: /404"); 
        }

        $tareas = Tarea::belongsTo("proyectoId", $proyecto->id);
        //debug($tareas);

        echo json_encode(['tareas' => $tareas]);

        // $router->render("tareas/index", [
        //     "proyecto" => $proyecto,
        //     "tareas" => $tareas
        // ]);
    }

    public static function crear() {
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {

            session_start();

            $proyectoId = $_POST["proyectoId"];

            $proyecto = Proyecto::where("url", $proyectoId);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION["id"]) {
                
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un error al agregar la tarea"
                ];

                echo json_encode($respuesta);
                return;
            }

            // Instanciar y crear la Tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente', 
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar(Router $router) {


        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }

    public static function eliminar(Router $router) {
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
        }
    }
}
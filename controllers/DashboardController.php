<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;

class DashboardController {
    public static function index(Router $router) {
        session_start();

        isAuth();

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos', 

        ]);
    }

    public static function crear(Router $router) {
        session_start();

        isAuth();

        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            // Instanciar un nuevo Proyecto
            $proyecto = new Proyecto($_POST);

            // Validar el Proyecto
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                //Generar una URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION["id"];

                // Guardar el proyecto
                $proyecto->guardar();

                // Redireccionar
                header("Location: /proyecto?id=" . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto', 
            'alertas' => $alertas, 
        ]);
    }

    public static function proyecto(Router $router) {
        
        session_start();

        isAuth();

        $token = $_GET["id"];

        if(!$token) {
            header("Location: /dashboard");
        }

        // Revisar que la persona que visita el proyecto, es el propietario
        $proyecto = Proyecto::where("url", $token);
        
        if($proyecto->propietarioId !== $_SESSION["id"]) {
            header("Location: /dashboard");
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
        ]);
    }

    public static function perfil(Router $router) {
        session_start();    

        isAuth();

        $router->render('dashboard/perfil', [
            'titulo' => 'Mi Perfil', 
        ]);
    }
}
<?php

namespace Controllers;

use MVC\Router;
use Model\Tarea;
use Model\Usuario;
use Model\Proyecto;

class DashboardController {
    public static function index(Router $router) {
        session_start();

        isAuth();

        // Consultar los Proyectos del usuario actual
        $id = $_SESSION["id"];

        $proyectos = Proyecto::belongsTo("propietarioId", $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos', 
            'proyectos' => $proyectos
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

    public static function eliminar_proyecto(Router $router) {

        session_start();

        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {

                $proyecto = Proyecto::find($id);

                if ($proyecto->propietarioId === $_SESSION['id']) {

                    $id = $_POST['id'];
                    $proyecto = Proyecto::find($id);

                    // Eliminar Tareas del Proyecto (no olvides importar la clase Tarea)
                    $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

                    foreach ($tareas as $tarea) {
                        $tarea->eliminar();
                    }

                    $proyecto->eliminar();

                    // Redireccionar
                    header('Location: /dashboard');
                }
            }
        }
    }

    public static function perfil(Router $router) {
        session_start();    
        isAuth();

        $alertas = [];

        $usuario = Usuario::find($_SESSION["id"]);
        //debug($usuario);

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            $usuario->sincronizar($_POST);
            //debug($usuario);

            $alertas = $usuario->validarPerfil();
            //debug($alertas);

            if(empty($alertas)) {

                $existeUsuario = Usuario::where("email", $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje de Error
                    Usuario::setAlerta("error", "Email no válido, ya pertenece a otro usuario");
                    $alertas = Usuario::getAlertas();
                } else {
                    // Guardar el usuario
                    $usuario->guardar();
    
                    Usuario::setAlerta("exito", "Guardado Correctamente");
                    $alertas = Usuario::getAlertas();
    
                    // Asignar el nombre nuevo a la barra
                    $_SESSION["nombre"] = $usuario->nombre;
                    $_SESSION["email"] = $usuario->email;
    
                    //debug($_SESSION);
                }
                
            }
        }


        $router->render('dashboard/perfil', [
            'titulo' => 'Mi Perfil', 
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) {

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            $usuario = Usuario::find($_SESSION["id"]);
            
            // Sincronizar con los datos del usuario 
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();
                //debug($resultado);

                if($resultado) {

                    // Asignar el nuevo password
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar propiedades innecesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    
                    // Hashear el password
                    $usuario->hashPassword();

                    // Guardar el nuevo password en la BD
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta("exito", "Password Guardado Correctamente");
                        $alertas = Usuario::getAlertas();
                    }
                } else {
                    Usuario::setAlerta("error", "Password Incorrecto");
                    $alertas = Usuario::getAlertas();
                }
            }

            //debug($usuario);
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password', 
            'alertas' => $alertas, 
        ]);
    }
}
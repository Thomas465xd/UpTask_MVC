<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {

        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
            // Crear una instancia de Usuario
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                // Verificar que el usuario exista
                $usuario = Usuario::where("email", $usuario->email);
                
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta("error", "El Usuario No Existe o No esta Confirmado");
                } else {
                    
                    // Si el Usuario existe Comprobar Password
                    if (password_verify($_POST["password"], $usuario->password)) {

                        // Iniciar la Sesión
                        session_start();

                        // Llenar el arreglo de la Sesión
                        $_SESSION = [];

                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["apellido"] = $usuario->apellido;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        // Redireccionar
                        header("Location: /dashboard");

                    } else {
                        Usuario::setAlerta("error", "Password Incorrecto");
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Incluimos la vista de login
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas,
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];

        header("Location: /");
    }

    public static function crear(Router $router) {

        $alertas = [];

        $usuario = new Usuario;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $usuario->sincronizar($_POST);
            //debug($usuario);
            $alertas = $usuario->validarNuevaCuenta();
            //debug($alertas);
            
            if (empty($alertas)) {

                $existeUsuario = Usuario::where("email", $usuario->email);
                //debug($existeUsuario);
    
                if ($existeUsuario) {
                    Usuario::setAlerta("error", "El Usuario ya esta Registrado");
                    $alertas = $usuario->getAlertas();
                } else {

                    // Hashear Password
                    $usuario->hashPassword();
                    
                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar Token
                    $usuario->crearToken();

                    // Crear Cuenta
                    $resultado = $usuario->guardar();

                    // Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    
                    if($resultado) {
                        header("Location: /mensaje");
                    }

                    //debug($usuario);

                    // Crear un Nuevo Usuario
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta en UpTask',
            'usuario' => $usuario, 
            'alertas' => $alertas,
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                // Buscar el usuario mediant el email
                $usuario = Usuario::where("email", $usuario->email);

                if($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo Token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    
                    // Actualizar el Usuario
                    $usuario->guardar();

                    // Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir la alerta
                    Usuario::setAlerta("exito", "Revisa tu Email");

                } else {
                    Usuario::setAlerta("error", "El Usuario No Existe o No está Confirmado");
                }

                // Get alerts from the static method
                $alertas = Usuario::getAlertas();
            }
        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {
        $alertas = [];

        $token = s($_GET["token"]);
        $mostrar = true;

        // Redirect to homepage if token is not provided
        if (!$token) {
            header("Location: /");
            exit;
        }

        // Identificar el usuario con este token
        $usuario = Usuario::where ("token", $token);

        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta("error", "Token No Válido");
            $mostrar = false;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Añadir el Nuevo Password
            $usuario->sincronizar($_POST);

            // Validar el Password
            $alertas = $usuario->validarPassowrd();

            if (empty($alertas)) {

                // Hashear el nuevo Password
                $usuario->hashPassword();
                unset($usuario->password2);

                // Eliminar el Token
                $usuario->token = null;

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                // Redireccionar
                if ($resultado) {
                    header("Location: /");                 
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente',
        ]);
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET["token"]);

        // Redirect to homepage if token is not provided
        if (!$token) {
            header("Location: /");
            exit;
        }
        // Encontrar al usuario con este token
        $usuario = Usuario::where("token", $token);
        
        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta("error", "Token No Válido");
            $alertas = Usuario::getAlertas(); // Get alertas from the static method
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            unset($usuario->password2);
            
            // Guardar en la base de datos
            $usuario->guardar();
            
            // Set success message
            Usuario::setAlerta("exito", "Cuenta Comprobada correctamente");
            $alertas = $usuario->getAlertas();

            // Redirect to homepage after confirmation
            header("Location: /");
            exit;
        }
        
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }
}
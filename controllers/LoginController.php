<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

        }

        // Incluimos la vista de login
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
        ]);
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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

        }

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
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

    public function logout() {

    }
}
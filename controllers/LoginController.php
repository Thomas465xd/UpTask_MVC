<?php

namespace Controllers;

use MVC\Router;

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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta en UpTask',
        ]);
    }

    public static function olvide(Router $router) {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

        }

        $router->render('auth/olvide', [
            'titulo' => 'Reestablece tu Password',
        ]);
    }

    public static function reestablecer(Router $router) {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

        }

        $router->render('auth/crear');
    }

    public static function mensaje(Router $router) {

        $router->render('auth/crear');
    }

    public static function confirmar(Router $router) {

        $router->render('auth/crear');
    }

    public function logout() {

    }
}
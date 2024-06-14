<?php

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $token;
    public $confirmado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validación para Cuentas Nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        if(strlen($this->password) < 6) {	
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }

        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los Passwords no son iguales';
        }

        return self::$alertas;	
    }

    // Validar Login
    public function validarLogin() {
        $alertas = [];

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }

    // Validar Email
    public function validarEmail() {

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        return self::$alertas;
    }

    // Validar Password
    public function validarPassowrd() {
        $alertas = [];
        
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        if(strlen($this->password) < 6) {	
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Hashear Password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Crear Token
    public function crearToken() {
        $this->token = md5( uniqid() );
    }
}
<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cc74adbfff75b1';
        $mail->Password = 'cc886167295234';

        // Configurar el email
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu Cuenta';

        // Set HTML 
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Cuerpo del email
        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $this->nombre . '</strong> Has Creado una Cuenta en UpTask, solo debes Confirmarla en el siguiente enlace</p>';
        $contenido .= '<p>Presiona aqui: <a href="http://localhost:3000/confirmar-cuenta?token=' . $this->token . '">Confirmar Cuenta</a></p>';
        $contenido .= '<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>';
        $contenido .= '</html>';
        $mail->Body = $contenido;
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cc74adbfff75b1';
        $mail->Password = 'cc886167295234';

        // Configurar el Email
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu Password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Cuerpo del Email
        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $this->nombre . '</strong> Has solicitado reestablecer tu Password, sigue el siguiente enlace para crear uno nuevo.</p>';
        $contenido .= '<p>Presiona aqui: <a href="http://localhost:3000/recuperar?token=' . $this->token . '">Reestablecer Password</a></p>';
        $contenido .= '<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>';
        $contenido .= '</html>';
        $mail->Body = $contenido;
    }
}
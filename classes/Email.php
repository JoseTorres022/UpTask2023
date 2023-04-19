<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre ,$token){
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;
    }

    //Estructura para el envio de confirmacion del correo
    public function enviarConfirmacion(){
        $mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Port = 2525;
$mail->Username = 'cdcc9e439d43bd';
$mail->Password = '19d31c2f5b999a';

$mail->setFrom('cuentas@uptask.com');
$mail->addAddress('cuentas@uptask.com', 'uptask.com');
$mail->Subjet='Confirma tu cuenta';

$mail->isHTML(TRUE);
$mail->CharSet='UTF-8';

$contenido='<html>';
$contenido .="<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en Uptask, 
solo debes confirmala en el siguiente enlace:</p>";
$contenido .="<p> Presiona aqui: <a href='http://localhost:3000/confirmar?token=" . 
$this->token . "'> Confirma tu Cuenta</a></p>";
$contenido .="<p> Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
$contenido .='</html>';

$mail->Body=$contenido;

//Enviar el email
$mail->send();
    }
}
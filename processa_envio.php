<?php
// print_r($_POST);

require "./lib/PHPMailer/Exception.php";
require "./lib/PHPMailer/OAuth.php";
require "./lib/PHPMailer/PHPMailer.php";
#POP3 recebimento de email
require "./lib/PHPMailer/POP3.php";
#SMTP envio de email
require "./lib/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;



class Mensagem
{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;



    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function mensagemValida()
    {
        if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            return false;
        } else {
            return true;
        }
    }
}

$mensagem = new Mensagem();

$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);



if (!$mensagem->mensagemValida()) {
    echo 'Mensagem não é válida';
    header('Location: index.php');
}


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
     //Server settings
     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
     $mail->isSMTP();                                            //Send using SMTP
     $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
     $mail->Username   = 'user@example.com';                     //SMTP username
     $mail->Password   = 'secret';                               //SMTP password
     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
     $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    

    //Recipients
    $mail->setFrom('user@example.com', 'Remetente');
    $mail->addAddress($mensagem->__get('para'));     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessario utilizar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem';

    $mail->send();
    echo 'E-mail enviado com sucesso';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

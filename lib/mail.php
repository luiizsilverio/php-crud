<?php

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

function enviar_email ($destinatario, $assunto, $mensagemHtml) {
  $email  = 'fatnino@yahoo.com.br';
  $senha  = 'poosoqdbbjybnzxp'; // não é a senha do Yahoo Mail, mas uma senha gerada na área de segurança do Yahoo, opção Gerar e gerenciar senhas de aplicativo.
  $host   = 'smtp.mail.yahoo.com';
  $port   = 587;
  $username = 'fatnino'; // no caso do Yahoo, é o e-mail antes do @

  $mail = new PHPMailer;

  $mail->IsSMTP();
  $mail->IsHTML(true);
  $mail->SMTPDebug = 0; // 0=Debug_OFF; 1=Debug_Client; 2=Debug_Server
  $mail->SMTPAuth = true;
  $mail->Mailer = "smtp";
  $mail->CharSet = 'UTF-8';
  $mail->SMTPSecure = false; 

  $mail->Host = $host;
  $mail->Port = $port;
  $mail->Username = $username;
  $mail->Password = $senha;

  $mail->setFrom($email, "LuizDev"); 
  $mail->addAddress($destinatario);

  $mail->Subject = $assunto;
  $mail->Body = $mensagemHtml;
  $mail->AltBody = filter_var($mensagemHtml, FILTER_SANITIZE_STRING);

  if ($mail->send()) {
    // echo "E-mail enviado com sucesso!";
    return true;
  }
  else {
    // echo "Falha ao enviar e-mail!";
    return false;
  }
}

?>
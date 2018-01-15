<?php

$mail = new PHPMailer();                              // Passing `true` enables exceptions

// Instância do objeto PHPMailer
//$mail = new PHPMailer();

// Configura para envio de e-mails usando SMTP
$mail->SMTPDebug = 0;
    //Set PHPMailer to use SMTP.
$mail->isSMTP();
 
$mail->CharSet = 'UTF-8';
 
$mail->SMTPOptions = array('ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ));
// Servidor SMTP
$mail->Host = 'smtp.gmail.com' ;

// Usar autenticação SMTP
$mail->SMTPAuth = true;

// Usuário da conta
$mail->Username = 'anacamposteste@gmail.com';

// Senha da conta
$mail->Password = 'xique123';

// Tipo de encriptação que será usado na conexão SMTP
$mail->SMTPSecure = 'tls';

// Porta do servidor SMTP
$mail->Port = 587;

// Informa se vamos enviar mensagens usando HTML
$mail->IsHTML(true);

// Email do Remetente
$mail->From = 'anacamposteste@gmail.com';
// Nome do Remetente
$mail->FromName = 'Ana';

// Endereço do e-mail do destinatário
$mail->addAddress($email, $nomeusuario);

// Assunto do e-mail
$mail->Subject = 'Livros em atraso';

// Mensagem que vai no corpo do e-mail
$mail->Body = '<h1>Consta em nossos registros livros em atrasos</h1><br /><p>Prezado(a)'.$nomeusuario.'
, consta em nosso sistema que você possui um ou mais livros em atraso.
<br /><br />Estamos enviando em anexo a lista dos atrasos.</p>';

//$mail->addAttachment('C:\xampp\htdocs\biblioteca\livro.pdf', 'relação de livros atrazados');
$mail->addAttachment($nomearquivo.".pdf", 'anexo');
// Envia o e-mail e captura o sucesso ou erro
if($mail->Send()):
    echo '';
else:
    echo 'Erro ao enviar Email:' . $mail->ErrorInfo;
endif;

echo '';

//header("location:listarEmprestimos.php");

?>
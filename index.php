<?php
require 'PHPMailerAutoload.php';
//require_once('class.phpmailer.php');

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=mail;charset=utf8', 'root', '');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

if(isset($_GET['id'])){
	$compteur = $_GET['id'];
}else{
	$compteur=0;
}

$req = $bdd->query('SELECT COUNT(*) FROM mail');
$nombreMailer = $req->fetch();
$req->closeCursor();

var_dump($nombreMailer);

$reponse = $bdd->query('SELECT * FROM mail LIMIT 20 OFFSET '.$compteur);

if($compteur==$nombreMailer[0]){
	exit();
}


while ($mailer = $reponse->fetch())
{
	sleep(1);
	$mail = new PHPMailer;
	$mail->isSMTP();                                  // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'YOUR_MAIL';                 // SMTP usernametyjgy
	$mail->Password = 'YOUR_PASSWORD';                           // SMTP password
	$mail->SMTPSecure = 'ssl';                          // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                    // TCP port to connect to

	$mail->setFrom('YOUR_MAIL', 'Admin');
	$mail->addAddress($mailer['email'], '');     // Add a recipient
	$mail->addReplyTo('YOUR_MAIL', 'Information');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	$mail->isHTML(true);                                  // Set email format to HTML (TRUE)

	$mail->Subject = 'Bonjour '.$mailer['prenom'].' '.$mailer['nom'];
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Le message a été envoyé';
	}
	$compteur++;
}

$reponse->closeCursor();
//exit();


header('Location: http://localhost/mail/index.php?id='.$compteur);



//$mail->SMTPDebug = 3;                               // Enable verbose debug output



<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./vendor/autoload.php";

if(isset($_SESSION["pending_user"]["email"])){
    $email = $_SESSION["pending_user"]["email"];
    $subject = "Email Verification";
    $code_session_key = "verification_code";
}
else {
    header("Location: login.php");
    exit;
}

// Yeni doğrulama kodu oluştur
$code = random_int(100000, 999999);
$_SESSION[$code_session_key] = $code;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "256project256@gmail.com"; 
    $mail->Password   = "vtuk sokb qxrw lwsb";       
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom("256project256@gmail.com", "Sender");
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = "Your 6-digit code is: <b>$code</b>";
    $mail->send();

} catch (Exception $e) {
    $_SESSION["mail_error"] = "Email gönderilemedi: {$mail->ErrorInfo}";
}

header("Location: verify.php");
exit;
?>

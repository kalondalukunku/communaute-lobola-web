<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once BASE_PATH . 'vendor/PHPMailer/src/PHPMailer.php';
require_once BASE_PATH . 'vendor/PHPMailer/src/SMTP.php';
require_once BASE_PATH . 'vendor/PHPMailer/src/Exception.php';

class SendMail {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
    }

    public function sendEmail($to, $subject, $message, $image = null)
    {
        $this->mail->SMTPDebug = 0;
        $this->mail->isSMTP();
        // $this->mail->Host = 'smtp.hostinger.com';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true; 
        $this->mail->Username = ADMIN_EMAIL2;
        // $this->mail->Password = 'support@LOBOLA01'; // Mettre le mot de passe réel ici
        $this->mail->Password = 'hdgcbceqjrijqeba'; // Mettre le mot de passe réel ici
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';

        $this->mail->setFrom(ADMIN_EMAIL2, SITE_NAME);
        $this->mail->addAddress($to);
        $this->mail->addReplyTo(ADMIN_EMAIL2, SITE_NAME);
        if($image != null) {
            $this->mail->addAttachment($image, 'ANKHING BUKUS logo');
        }

        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->setLanguage('fr','/optional/path/to/language/directory');

        if($this->mail->send()) {
            return true;
        }else {
            return false;
        }
    }
}
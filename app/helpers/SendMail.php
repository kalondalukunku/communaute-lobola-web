<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once BASE_PATH . 'vendor/phpmailer/src/PHPMailer.php';
require_once BASE_PATH . 'vendor/phpmailer/src/SMTP.php';
require_once BASE_PATH . 'vendor/phpmailer/src/Exception.php';

class SendMail {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        try {
            // Configuration SMTP depuis config
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = MAIL_USER;
            $this->mail->Password = MAIL_PASS;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = 465;
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';

            $this->mail->CharSet = 'UTF-8';
            $this->mail->isHTML(true);
            $this->mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);

        } catch (Exception $e) {
            $this->logError("Erreur configuration mailer : " . $e->getMessage());
            die("Erreur configuration mailer : " . $e->getMessage());
        }
    }

    public function addTo($email, $name = '') {
        $this->mail->addAddress($email, $name);
    }

    public function addCC($email, $name = '') {
        $this->mail->addCC($email, $name);
    }

    public function addBCC($email, $name = '') {
        $this->mail->addBCC($email, $name);
    }

    public function setSubject($subject) {
        $this->mail->Subject = $subject;
    }

    public function setBody($body) {
        $this->mail->Body = $body;
    }

    public function setAltBody($altBody) {
        $this->mail->AltBody = $altBody;
    }

    public function addAttachment($path, $name = '') {
        $this->mail->addAttachment($path, $name);
    }

    public function send() {
        try {
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            $this->logError("Erreur envoi mail : " . $this->mail->ErrorInfo);
            return "Erreur lors de l'envoi : " . $this->mail->ErrorInfo;
        }
    }

    private function logError($message) {
        $logFile = BASE_PATH . 'storage/logs/mail.log';
        $date = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$date] $message" . PHP_EOL, FILE_APPEND);
    }
}
<?php
namespace App\Core;

class Mailer
{
    public static function send(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool
    {
        // Try PHPMailer if available
        $phpMailerPath = BASE_PATH . '/lib/PHPMailer/src/PHPMailer.php';
        if (file_exists($phpMailerPath)) {
            require_once BASE_PATH . '/lib/PHPMailer/src/Exception.php';
            require_once BASE_PATH . '/lib/PHPMailer/src/PHPMailer.php';
            require_once BASE_PATH . '/lib/PHPMailer/src/SMTP.php';
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                if (MAIL_DRIVER === 'smtp') {
                    $mail->isSMTP();
                    $mail->Host = MAIL_HOST;
                    $mail->Port = MAIL_PORT;
                    $mail->SMTPAuth = true;
                    $mail->Username = MAIL_USERNAME;
                    $mail->Password = MAIL_PASSWORD;
                    if (MAIL_ENCRYPTION) {
                        $mail->SMTPSecure = MAIL_ENCRYPTION;
                    }
                }
                $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;
                $mail->AltBody = $textBody ?? strip_tags($htmlBody);
                return $mail->send();
            } catch (\Throwable $e) {
                return false;
            }
        }

        // Fallback to PHP mail()
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';
        return mail($to, $subject, $htmlBody, implode("\r\n", $headers));
    }
}
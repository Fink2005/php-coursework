<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailModel {
    public function sendConfirmationEmail($to, $token) {
        $mail = new PHPMailer(true);
        
        // Ensure $token is a 6-digit string; split into individual digits
        $token = str_pad((string)$token, 6, '0', STR_PAD_LEFT); // Pad to 6 digits if needed
        $num1 = $token[0];
        $num2 = $token[1];
        $num3 = $token[2];
        $num4 = $token[3];
        $num5 = $token[4];
        $num6 = $token[5];
        
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'phans3806@gmail.com';
            $mail->Password = 'pkyviubrnddetrti';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom('phans3806@gmail.com', 'PHP Course Work');
            $mail->addAddress($to);

            // Email subject
            $mail->Subject = 'Confirm Your Registration';

            // Enable HTML
            $mail->isHTML(true);

            // HTML email body with inlined Tailwind-inspired styles
            $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Email Confirmation</title>
                </head>
                <body style="background-color: #f3f4f6;">
                    <div style="max-width: 32rem; margin-left: auto; margin-right: auto; margin-top: 2rem; margin-bottom: 2rem; padding: 1.5rem; background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                        <h2 style="font-size: 1.5rem; font-weight: bold; text-align: center; color: #2563eb; margin-bottom: 1rem;">Welcome to PHP Course Work</h2>
                        <div style="color: #4b5563;">
                            <h1 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">Your Verification Code</h1>
                            <p style="margin-bottom: 1rem;">Thank you for registering! To verify your account, enter this code on the verification page:</p>
                            <p style="text-align: center; font-size: 2rem; font-weight: bold; color: #2563eb; margin: 1.5rem 0; letter-spacing: 0.5rem;">'
                                . htmlspecialchars($num1) . ' ' 
                                . htmlspecialchars($num2) . ' ' 
                                . htmlspecialchars($num3) . ' ' 
                                . htmlspecialchars($num4) . ' ' 
                                . htmlspecialchars($num5) . ' ' 
                                . htmlspecialchars($num6) . '
                            </p>
                            <p style="margin-bottom: 1rem;">If you didn’t register, please ignore this email.</p>
                        </div>
                        <div style="margin-top: 1.5rem; text-align: center; color: #6b7280; font-size: 0.75rem;">
                            <p>© ' . date('Y') . ' PHP Course Work. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
            ';

            // Plain text alternative
            $mail->AltBody = "Thank you for registering! Your verification code is: $num1 $num2 $num3 $num4 $num5 $num6\nEnter this code on the verification page. If you didn’t register, ignore this email.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Failed to send email. Error: {$mail->ErrorInfo}";
        }
    }
}
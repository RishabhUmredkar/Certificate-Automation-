<?php
include('smtp/PHPMailerAutoload.php');

function smtp_mailer($to, $subject, $msg, $headers) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'mail.asterisc.in';
    $mail->Port = 465;
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = 'admin@asterisc.in';
    $mail->Password = 'Asterisc@22c';
    $mail->setFrom('admin@asterisc.in');
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->addAddress($to);
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );

    // Attach the file
    $attachmentPath = '../Certificates/rishabh.pdf'; // Replace with the actual file path
    $attachmentName = 'rishabh.pdf'; // Replace with the desired attachment name
    $mail->addAttachment($attachmentPath, $attachmentName);

    if (!$mail->send()) {
        echo $mail->ErrorInfo;
    } else {
        echo 'Email sent successfully.';
    }
}

// Usage example
$to = 'aishwaryapatle22@gmail.com';
$subject = 'Email with Attachment';
$msg = 'Hello, this email contains an attachment.';
$headers = '';

smtp_mailer($to, $subject, $msg, $headers);
?>

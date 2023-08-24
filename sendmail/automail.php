<?php
require 'smtp/PHPMailerAutoload.php';

$pdfLink = ''; // Initialize the variable to store the PDF link

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pdfPath'])) {
    $uemail = $_POST['email'];
    $name = $_POST['name'];
    $pdfPath = $_POST['pdfPath'];

    // Send email with link to PDF
    if (filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
        $subject = "Your Course Certificate";
        $pdfLink = generatePdfLink($pdfPath); // Generate the PDF link
        $body = generateEmailBody($name, $pdfLink);

        sendEmail($uemail, $subject, $body);
    } else {
        die("Invalid email address provided.");
    }
} else {
    die("Name, Email, or PDF path not provided.");
}

function generatePdfLink($pdfPath) {
    // Replace the base URL with your desired directory path
    $baseUrl = "http://localhost/Internship/Deploy In/Certificates/";
    // Get the filename from the provided $pdfPath
    $filename = basename($pdfPath);
    
    // Concatenate the base URL and the filename to create the modified PDF link
    return $baseUrl . $filename;
}

function generateEmailBody($name, $pdfLink) {
    $emailBody = "
        <!DOCTYPE html>
        <html>
        <body>

        <h2>Dear $name,</h2>
        <p>
        We are pleased to inform you that your course certificate is ready to download. Please click the link below to download your certificate:
        </p>
        <p><a href='$pdfLink'>Download Certificate</a></p>
        <p>
        We thank you for signing up for the course at our institution and placing your faith in us.
        We hope that this course helps you achieve your objectives and wish you all the best in your future endeavors.
        </p>
        <p>
        Best regards,<br>
        Chandrakant D. Bobade<br>
        Director<br>
        Asterisc Computer Institute<br>
        Call on: 8669804213 / 7743822228
        </p>
        </body>
        </html>
    ";

    return $emailBody;
}

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer();

    // Set up SMTP configurations
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "mail.asterisc.in";
    $mail->Port = "465";
    $mail->Username = "admin@asterisc.in";
    $mail->Password = 'Asterisc@22c';

    // Set email details
    $mail->setFrom("admin@asterisc.in", "Chandrakant D. Bobade");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Body = $body;

    // Send the email
    if (!$mail->send()) {
        echo 'The email could not be sent.';
        echo $mail->ErrorInfo;
    } else {
        echo 'The email was sent successfully.';
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Email and PDF Link</title>
</head>

<body>
    <?php
    if (!empty($pdfLink)) {
        echo "<p><a href='$pdfLink'>Download Certificate</a></p>";
    }
    ?>
    <!-- Your HTML content for the page goes here -->
</body>

</html>

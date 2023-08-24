<?php
// Your database connection and other configurations here
require '../config.php';

// Fetch all email addresses from the database
$query = "SELECT * FROM event_certificate";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    require 'smtp/PHPMailerAutoload.php'; // Include PHPMailer library

    while ($row = mysqli_fetch_assoc($result)) {
        $name = strtolower($row["sname"]);
        $email = $row["uemail"];
        $pdfPath = "Certificates/" . $name . ".pdf";

        // Send email with link to PDF
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $subject = "Your Course Certificate";
            $pdfLink = generatePdfLink($pdfPath); // Generate the PDF link
            $body = generateEmailBody($name, $pdfLink);

            sendEmail($email, $subject, $body);
        } else {
            echo "Invalid email address provided for: " . $name . "<br>";
        }
    }
} else {
    echo "No recipients found in the database.";
}

function generatePdfLink($pdfPath) {
    // Replace the base URL with your desired directory path
    $baseUrl = "http://localhost/Internship/Deploy%20In/Certificates/";

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
        echo 'The email could not be sent for: ' . $to . '<br>';
        echo $mail->ErrorInfo;
    } else {
        echo 'Email sent successfully to: ' . $to . '<br>';
    }
}
?>

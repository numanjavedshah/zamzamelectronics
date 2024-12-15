<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Database connection
$host = 'localhost'; // Change if necessary
$username = 'root';  // Change if necessary
$password = '';      // Change if necessary
$dbname = 'contact_form'; // The name of your database

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

// Insert into the database
$sql = "INSERT INTO submissions (name, email, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    // Data successfully inserted; proceed to send the email
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'numanjavedshah@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'abc';   // Replace with your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('numanjavedshah@gmail.com', 'Contact Form'); // Replace with your email
        $mail->addAddress('numanjavedshah@gmail.com'); // Email where data should be sent

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong> $message</p>
        ";

        // Send the email
        $mail->send();
        echo 'Thank you for showing your interest! Zam Zam greatly values your message and will get back to you shortly.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            
    }
} else {
    echo 'Failed to save data to the database.';
}

// Close the database connection
$stmt->close();
$conn->close();
?>

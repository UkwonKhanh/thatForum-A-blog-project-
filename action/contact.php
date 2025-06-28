<?php
ob_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

require_once "../config/config_session.php";
if (!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit;
}
try {
    require_once "../config/database.php";
    require_once "../config/function.php";

    if ($_SERVER["REQUEST_METHOD"] == 'POST'){
        // announce array
        $success = [];
        $errors = [];

        // retrieve user's id and details
        $user_id = $_SESSION['user_id'];
        $user = get_user_details($user_id);
        $email_user = $user['email_address'];
        $username = $user['username'];
        $message = $_POST['message'];
        $message_title = $_POST['messageTitle'];

        // admin email address
        $admin_email = "hocongkhanh310520049a4@gmail.com"; // modify this to add new admin

        if (empty_title($message_title)){
            $errors['empty title'] = "Message title is required";
        }
        if (empty_content($message)){
            $errors['empty content'] = "Message content is required";
        }
        if ($errors){
            $_SESSION['errors'] = $errors;
            header("location: ../templates/contact.html.php");
            exit;
        }else{
            try {
                // Insert into database
                $sql = "INSERT INTO messages (title, `message`, user_id) 
                        VALUES (:title, :message_content, :user_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':title', $message_title);
                $stmt->bindParam(':message_content', $message);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();

                // Send email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();                                            //Send using SMTP  
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->Username   = 'khanhhcgcs220658@fpt.edu.vn';                     //SMTP username modify this to admin email 
                    $mail->Password   = 'bosvwvwihblmvsqn';                               //SMTP password

                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //ENCRYPTION_SMTPS 465 - Enable implicit TLS encryption
                    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom($admin_email);
                    $mail->addAddress($admin_email, "Mr admin");                //Add a recipient              


                    //Content
                    $mail->isHTML(true);                                        //Set email format to HTML
                    $mail->Subject = $message_title;
                    // $mail->AltBody = $message;
                    $mail->Body = "<h3>You've got a message from thatForum</h3>
                    <h4> From username: " . $username . "</h4>
                    <h4> Email: " . $email_user . "</h4>
                    " . $message ;

                    $mail->send();

                    $success['success'] = 'Message sent successfully!';
                    $_SESSION['success'] = $success;
                    header("location: ../templates/userquestion.html.php");
                    exit;
                }catch (Exception $e) {
                    $errors['fail'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    $_SESSION['errors'] = $errors;
                    header("location: ../templates/contact.html.php");
                    exit;    
                }
            }catch (PDOException $e) {
                $errors['exception'] = "Failed: " . $e ->getMessage();
                $_SESSION['errors'] = $errors;
                header("location: ../templates/contact.html.php");
                die();
            }
        }
    }
}catch (PDOException $e) {
    $errors['exception'] = "Failed: " . $e ->getMessage();
    $_SESSION['errors'] = $errors;
    header("location: ../templates/contact.html.php");
    die();
}
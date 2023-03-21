<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$error = '';
$success_message = '';


if (isset($_POST['register'])) {

   
    @session_start();

    if (isset($_SESSION['user'])) {
        @header('location:chatRooms.php');
    }

    require_once('databases/ChatUser.php');

    $user = new ChatUser;
    $user->setName($_POST['name']);
    $user->setEmail($_POST['email']);
    $user->setPassword(md5($_POST['password']));    
    $user->setProfile($user->makeAvatar(strtoupper($_POST['name'][0])));
    $user->setStatus('Disabled');
    $user->setCreatedOn(date('Y-m-d H:i:s'));
    $user->setVerificationCode(md5(uniqid()));
 
    $user_data = $user->getUserDataByEmail();

 

    if (is_array($user_data) && count($user_data) > 0) {
        $error = 'This Email Already Register';
    } else {
    
        if ($user->save()) {


            $mail = new PHPMailer(true);

            $mail->isSMTP();// Set mailer to use SMTP
            $mail->CharSet = "utf-8";// set charset to utf8
            $mail->SMTPAuth = true;// Enable SMTP authentication
            $mail->SMTPSecure = 'tls';// Enable TLS encryption, `ssl` also accepted

            $mail->Host = 'sandbox.smtp.mailtrap.io';// Specify main and backup SMTP servers
            $mail->Port = 2525;// TCP port to connect to
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->isHTML(true);// Set email format to HTML

            $mail->Username = '7c2ef40bab28ca';// SMTP username
            $mail->Password = '3f2834e458672f';// SMTP password

            $mail->setFrom('coffeelegendary0323@gmail.com', 'mailtrap');//Your application NAME and EMAIL
    
            $mail->addAddress($user->getEmail(), $user->getName());// Target email
            $mail->isHTML(true);
            $mail->Subject = "Registration Verification for chat Application Demo";
       
            $mail->Body = '
            <p>Thank you for registering for Chat Application Demo.</p>
                <p>This is a verification email, please click the link to verify your email address.</p>
                <p><a href="http://localhost:8000/verify.php?code='.$user->getVerificationcode().'">Click to Verify</a></p>
                <p>Thank you...</p>
            ';

            $mail->send();


            $success_message = 'Verification Email sent to ' . $user->getEmail() . ', so before login first verify your email';

        
        } else {
            $error = "Something Went Worng Try Again";
        }

    }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register | PHP Chat Application using Websocket</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css" />

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
</head>

<body>

    <div class="containter">
        <br />
        <br />
        <h1 class="text-center">Chat Application using Websocket</h1>
        
        <div class="row justify-content-md-center" >

            <div class="col col-md-4 mt-5">
                <?php 

                    if ($error != '') {
                        echo '
                            <div class="alert alert-waring alert-dismissible fade show" role="alert">
                                '.$error.'
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        ';
                    }

                    if ($success_message) {
                        echo '
                            <div class="alert alert-success">
                            '.$success_message.'
                            </div>
                        ';
                    }
                
                ?>
                <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form method="post" id="register_form">

                        <div class=form-group>
                            <label>Enter Your Name</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                data-parsley-pattern="/^[a-zA-Z\s]+$/" required />
                        </div>

                        <div class="form-group">
                            <label>Enter Your Email</label>
                            <input type="text" name="email" id="email" class="form-control"
                                data-parsley-type="email" required />
                        </div>

                        <div class="form-group">
                            <label>Enter Your Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                data-parsley-minlength="3" data-parsley-pattern="/^[a-zA-Z\s]+$/" required />
                        </div>

                        <div class="form-group text-center">
                            <input type="submit" name="register" class="btn btn-success" value="Register" />
                        </div>

                    </form>
                </div>
            </div>
            
        </div>
    </div>

           
 

</body>

</html>

<script>

    $('#register_form').parsley();

</script>
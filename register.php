<?php

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
 
    $data = $user->getUserDataByEmail();

 

    if (is_array($data) && count($data) > 0) {
        $error = 'This Email Already Register';
    } else {
    
        if ($user->save()) {
            $success_message = "Registeration Completed";
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
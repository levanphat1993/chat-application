<?php 

session_start();

if (!isset($_SeSSION['user_data'])) {
    header('localtion:index.php');
}

require_once('databases/ChatUser.php');

$user = new ChatUser;

$user_id = '';

foreach ($_SESSION['user_data'] as $key => $value) {
    $user_id = $value['id'];   
}

$user->setId($user_id);

$user_data = $user->getUserDataById();

$message = '';

if (isset($_POST['edit'])) {
   
   
    $user_profile = $_POST['hidden_profile'];


 
    if ($_FILES['profile']['name'] != '') {

        $user_profile = $user->updateImage($_FILES['profile']);
        $_SESSION['user_data'][$user_id]['profile'] = $user_profile;

    }

    
    $user->setName($_POST['name']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);
    $user->setprofile($user_profile);
    $user->setId($user_id);

    if ($user->updateData()) {

        $message = '<div class="alert alert-success">Profile Deatials Update</div>';

    }

}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat application in php using web scocket programming</title>
	<!-- Bootstrap core CSS -->
    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
</head>
<body>
	<div class="container">
		<br />
        <br />
        <h1 class="text-center">PHP Chat Application using Websocket</h1>
        <br />
        <br />
        <?php echo $message ?>
		<div class="card">
			<div class="card-header">
                <div class="row">
                    <div class="col-md-6">Profile</div>
                    <div class="col-md-6 text-right"><a href="chatroom.php" class="btn btn-warning btn-sm">Go to Chat</a></div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" id="profile_form" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control" 
                                data-parsley-pattern="/^[a-zA-Z\s]+$/"
                                value="<?php echo $user_data['name']; ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="form-control"
                                data-parsley-type="email" required readonly 
                                value="<?php echo $user_data['email']; ?>" />
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                                data-parsley-minlength="3" data-parsley-pattern="/^[a-zA-Z\s]+$/"
                                value="<?php echo $user_data['password']; ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Profile</label>
                        <input type="file" name="profile" id="profile" />
                        <br/>
                        <img src="<?php echo $user_data['profile']; ?>" 
                            class="img-fluid img-thumbnail mt-3" width="100" />
                        <input type="hidden" name="hidden_profile"
                            value="<?php echo $user_data['profile'] ?>" />
                    </div>

                    <div class="form-group text-center">
                        <input type="submit" name="edit" class="btn btn-primary" value="Edit" />
                    </div>

                </form>
            </div>
		</div>
	</div>
</body>
</html>

<script>

$(document).ready(function(){


});

</script>
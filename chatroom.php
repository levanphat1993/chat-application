<?php 
session_start();



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
	<style type="text/css">
		html,
		body {
		  height: 100%;
		  width: 100%;
		  margin: 0;
		}
		#wrapper
		{
			display: flex;
		  	flex-flow: column;
		  	height: 100%;
		}
		#remaining
		{
			flex-grow : 1;
		}
		#messages {
			height: 200px;
			background: whitesmoke;
			overflow: auto;
		}
		#chat-room-frm {
			margin-top: 10px;
		}
		#user_list
		{
			height:450px;
			overflow-y: auto;
		}

		#messages_area
		{
			height: 650px;
			overflow-y: auto;
			background-color:#e6e6e6;
		}

	</style>
</head>
<body>
	<div class="container">
		<br />
        <h3 class="text-center">Realtime One to One Chat App using Ratchet WebSockets with PHP Mysql - Online Offline Status</h3>
        <br />
		<div class="row">
            <div class="col-lg-8">

            </div>
            <div class="col-lg-4">
                <?php 
                    
                    foreach ($_SESSION['user_data'] as $key => $value) {
                        ?>
                        
                            <div class="mt-3 mb-3 text-center">
                                <img src="<?php echo $value['profile']; ?>" width="150" class="img-fluid rounded-circle img-thumbnail" />
                                <h3 class="mt-2"><?php echo $vaule['name']; ?></h3>
                                <a href="profile.php" class="btn btn-secondary mt-2 mb-2">edit</a>
                            </div>
                        
                        <?php
                    }
                
                ?>
            </div>
		</div>
	</div>
</body>
<script type="text/javascript">
	
	
</script>
</html>
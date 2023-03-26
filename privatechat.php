<?php 

//privatechat.php

session_start();

if(!isset($_SESSION['user_data']))
{
	header('location:index.php');
}

require_once('databases/ChatUser.php');
require_once('databases/ChatRooms.php');

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
			height: 75vh;
			overflow-y: auto;
			/*background-color:#e6e6e6;*/
			/*background-color: #EDE6DE;*/
		}

	</style>
</head>
<body>
	<div class="container-fluid">
		
		<div class="row">

			<div class="col-lg-3 col-md-4 col-sm-5" style="background-color: #f1f1f1; height: 100vh; border-right:1px solid #ccc;">
                <?php 
                    
                    $login_user_id = '';

                    foreach ($_SESSION['user_data'] as $key => $value) {

                        $login_user_id = $value['id'];

                    

                ?>

                <div class="mt-3 mb-3 text-center">
                    <img src="<?php echo $value['profile'];  ?>" class="img-fluid rounded-circle img-thumbnail" width="150" />
                    <h3 class="mt-2"><?php echo $valuep['name']; ?></h3>
                    <a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
                </div>
                
                <?php 
                    }

                    $user = new ChatUser;

                    $user->setId( $login_user_id);
                    $user_data = $user->getUserAllDataWithStatusCount();

                ?>

				<div class="list-group" style=" max-height: 100vh; margin-bottom: 10px; overflow-y:scroll; -webkit-overflow-scrolling: touch;">
                    <?php 
                        foreach ($user_data as $key => $user) {

                          

                            $icon = '<i class="fa fa-circle text-danger"></i>';

                            if ($user['login_status'] == 'Login') {
                                
                                $icon = '<i class="fa fa-circle text-success"></i>';

                            }

                            if ($user_data['id'] != $login_user_id){

                                if ($user_data['count_status'] > 0) {
                                
                                    $total_unread_message = '<span class="badge badge-danger badge-pill">'.$user_data['count_status'].'</span>';
                                
                                } else {

                                    $total_unread_message = '';
                                }

                                echo "
                                <a class='list-group-item list-group-item-action select_user' style='cursor:pointer' data-userid = '".$user['user_id']."'>
								<img src='".$user["profile"]."' class='img-fluid rounded-circle img-thumbnail' width='50' />
								<span class='ml-1'>
									<strong>
										<span id='list_user_name_".$user["id"]."'>".$user['name']."</span>
										<span id='userid_".$user['id']."'>".$total_unread_message."</span>
									</strong>
								</span>
								<span class='mt-2 float-right' id='userstatus_".$user['id']."'>".$icon."</span>
							</a>
                                ";

                            }

                        }
                    
                    ?>
				</div>
			</div>
			
			<div class="col-lg-9 col-md-8 col-sm-7">
				<br />
		        <h3 class="text-center">Realtime One to One Chat App using Ratchet WebSockets with PHP Mysql - Online Offline Status - 8</h3>
		        <hr />
		        <br />
		        <div id="chat_area"></div>
			</div>
			
		</div>
	</div>
</body>

</html>
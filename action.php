<?php

session_start();

if (isset($_POST['action']) && $_POST['action'] == 'leave') {

    require_once('databases/ChatUser.php');

    $user = new ChatUser;

    $user->setId($_POST['user_id']);
    $user->setLoginStatus('Logout');

    if ($user->updateUserLoginData()) {

        unset($_SESSION['user_data']);
        session_destroy();

        echo json_encode(['status' => 1]);

    }

}

?>
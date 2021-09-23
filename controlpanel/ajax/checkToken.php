<?php
    include('../include/connection.php');
	include("../../api/jwt.php");

	$output = array();

    if(!empty($_POST['current_token']) && !empty($_POST['refresh_token'])){
		$current_token = $_POST["current_token"];
		$refresh_token = $_POST["refresh_token"];

        $currentToken = check_token($conn,$current_token);
        $refreshToken = check_token($conn,$refresh_token);

        if($currentToken['status']){
            $output['status'] = TRUE;
            $output['current_token'] = FALSE;
            $output['login_id'] = $currentToken['data']['login_id'];
        } else{
            if($refreshToken['status']){
                $refreshContentID = $refreshToken['data']['login_id'];

                $new_current_token = create_token($conn,$refreshContentID,1);

                $output['status'] = TRUE;
                $output['current_token'] = $new_current_token['current_token'];
                $output['login_id'] = $refreshContentID;
            } else{
                $output['status'] = FALSE;
            }
        }
	}

	echo json_encode($output);
?>
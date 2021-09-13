<?php
    include('../include/connection.php');
	include("../../api/password.php");
	include("../../api/otp_sender.php");
    session_start();

	$output = array();

    if(isset($_POST['otp'])){
		$otp = $_POST["otp"];
        $login_id = $_SESSION['check_login_id'];

		$sql = "SELECT * FROM login WHERE BINARY login_id='$login_id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			if($otp == $row['login_otp']){
                unset($_SESSION['check_login_id']);
                $output['login_id'] = $login_id;
                $output['status'] = 'success';
                $output['message'] = 'Ok';
            } else{
                $output['status'] = 'fail';
			    $output['message'] = 'Invalid OTP';
            }
		} else{
			$output['status'] = 'fail';
			$output['message'] = 'Invalid Login';
		}
	}

	echo json_encode($output);
?>
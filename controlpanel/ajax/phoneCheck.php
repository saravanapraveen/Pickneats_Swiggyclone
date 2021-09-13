<?php
    include('../include/connection.php');
	include("../../api/password.php");
	include("../../api/otp_sender.php");

	$output = array();

    if(isset($_POST['phone'])){
		$phone = $_POST["phone"];

		$sql = "SELECT * FROM login WHERE BINARY login_phone_number='$phone'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			$login_id = $row['login_id'];

			$randomid = mt_rand(1000,9999);
			$msg = "Your verification OTP pickneats account is ".$randomid.".";

			$msg = urlencode($msg);

			$sql = "UPDATE login SET login_otp='$randomid' WHERE login_id='$login_id'";
			if($conn->query($sql) === TRUE){
				// if(Send_OTP($phone,$msg,$randomid)->status == 'success'){
					$output['status'] = 'success';
            		$output['login_id'] = $login_id;
				// }
			}
		} else{
			$output['status'] = 'fail';
			$output['message'] = 'Invalid Phone Number';
		}
	}

	echo json_encode($output);
?>
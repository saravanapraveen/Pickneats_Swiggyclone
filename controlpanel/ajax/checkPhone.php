<?php
    include('../include/connection.php');
	include("../../api/password.php");

	$output = array();

    if(isset($_POST['user_phone'])){
		$user_phone = $_POST["user_phone"];
		$login_id = $_POST["login_id"];

		if($login_id == ""){
			$sql = "SELECT * FROM login WHERE login_phone_number='$user_phone'";
		}else{
			$sql = "SELECT * FROM login WHERE login_phone_number='$user_phone' AND login_id!='$login_id'";
		}
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			$output['status'] = TRUE;
		} else{
			$output['status'] = FALSE;
		}
	}

	echo json_encode($output);
?>
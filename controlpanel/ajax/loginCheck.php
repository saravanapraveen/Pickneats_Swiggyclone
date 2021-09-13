<?php
	ini_set('display_errors','off');
    include('../include/connection.php');
	include("../../api/password.php");

	$output = array();

    if(isset($_POST['username'])){
		$username = $_POST["username"];
		$password = $_POST["password"];

		$sql = "SELECT * FROM login WHERE BINARY username='$username'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			if(checkPass($conn,$row['password'],$row['cipher'],$password)){
				$output['status'] = 'success';
				$output['login_id'] = $row["login_id"];
			} else{
				$output['status'] = 'fail';
				$output['message'] = 'Incorrect Password!';
			}
		} else{
			$output['status'] = 'fail';
			$output['message'] = 'Invalid Username!';
		}
	}

	echo json_encode($output);
?>
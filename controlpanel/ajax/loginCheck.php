<?php
    include('../include/connection.php');
	include("../../api/password.php");
	include("../../api/jwt.php");

	$output = array();

    if(isset($_POST['username'])){
		$username = $_POST["username"];
		$password = $_POST["password"];

		$sql = "SELECT * FROM login WHERE BINARY username='$username'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			if(checkPass($conn,$row['password'],$row['cipher'],$password)){
				$login_id = $row["login_id"];

				$tokens = create_token($conn,$login_id);
				$output['status'] = TRUE;
				$output['login_id'] = $login_id;
				$output['current_token'] = $tokens['current_token'];
				$output['refresh_token'] = $tokens['refresh_token'];
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
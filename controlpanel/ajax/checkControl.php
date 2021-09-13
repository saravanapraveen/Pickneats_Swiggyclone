<?php
    include('../include/connection.php');
	include("../../api/password.php");

	$output = array();

    if(isset($_POST['login_id'])){
		$login_id = $_POST["login_id"];

		$sql = "SELECT * FROM login WHERE login_id='$login_id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			$output['status'] = TRUE;
			$output['control'] = $row['control'];
		} else{
			$output['status'] = FALSE;
		}
	}

	echo json_encode($output);
?>
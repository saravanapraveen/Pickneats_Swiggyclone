<?php
    ini_set('display_errors','off');
    include('../include/connection.php');
	include("../../api/password.php");

	$output = array();

    if(isset($_POST['user_name'])){
		$user_name = $_POST["user_name"];
		$login_id = $_POST["login_id"];

		if($login_id == ""){
			$sql = "SELECT * FROM login WHERE BINARY username='$user_name'";
		}else{
			$sql = "SELECT * FROM login WHERE BINARY username='$user_name' AND login_id!='$login_id'";
		}
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			$output['status'] = TRUE;
			$output['query'] = $sql;
		} else{
			$output['status'] = FALSE;
            $output['query'] = $sql;
		}
	}

	echo json_encode($output);
?>
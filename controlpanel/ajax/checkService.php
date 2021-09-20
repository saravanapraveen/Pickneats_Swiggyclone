<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['service_name'])){
		$service_name = $_POST["service_name"];
		$service_id = $_POST["service_id"];

		if($service_id == ""){
			$sql = "SELECT * FROM service WHERE BINARY service_name='$service_name'";
		}else{
			$sql = "SELECT * FROM service WHERE BINARY service_name='$service_name' AND service_id!='$service_id'";
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
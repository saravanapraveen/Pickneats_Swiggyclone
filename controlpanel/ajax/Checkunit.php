<?php
	ini_set('display_errors','off');
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['unit_name'])){
		$unit_name = $_POST["unit_name"];
		$unit_id = $_POST["unit_id"];

		if($unit_id == ""){
			$sql = "SELECT * FROM unit WHERE unit_name='$unit_name'";
		}else{
			$sql = "SELECT * FROM unit WHERE unit_name='$unit_name' AND unit_id!='$unit_id'";
		}
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

			$output['status'] = TRUE;
			$output['sql'] = $sql;
		} else{
			$output['status'] = FALSE;
		}
	}

	echo json_encode($output);
?>
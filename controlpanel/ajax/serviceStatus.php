<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['service_id'])){
		$service_id = $_POST["service_id"];
		$city_id = $_POST["city_id"];

		$sql = "SELECT * FROM service_status WHERE city_id='$city_id' AND service_id='$service_id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
            $sql = "DELETE FROM service_status WHERE city_id='$city_id' AND service_id='$service_id'";
		} else{
			$sql = "INSERT INTO service_status (service_id,city_id) VALUES ('$service_id','$city_id')";
		}
        if($conn->query($sql) === TRUE){
            $output['status'] = 'success';
            $output['message'] = 'Status updated';
        } else{
            $output['status'] = 'failed';
            $output['message'] = 'Status updation failed';
        }
	}

	echo json_encode($output);
?>
<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['city_id'])){
		$city_id = $_POST["city_id"];

		$sql = "SELECT * FROM city WHERE city_id='$city_id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();

            $output['status'] = 'success';
            $output['city']['city_name'] = $row["city_name"];
            $output['city']['city_latitude'] = $row["city_latitude"];
            $output['city']['city_longitude'] = $row["city_longitude"];
		} else{
			$output['status'] = 'fail';
			$output['message'] = 'Invalid Username!';
		}
	}

	echo json_encode($output);
?>
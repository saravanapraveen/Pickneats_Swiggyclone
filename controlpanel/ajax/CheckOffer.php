<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['offer_name'])){
		$offer_name = $_POST["offer_name"];
        $offer_id = $_POST['offer_id'];

		if($offer_id == ""){
			$sql = "SELECT * FROM offers WHERE BINARY offer_coupon_code='$offer_name'";
		}else{
			$sql = "SELECT * FROM offers WHERE BINARY offer_coupon_code='$offer_name' AND offer_id!='$offer_id'";
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
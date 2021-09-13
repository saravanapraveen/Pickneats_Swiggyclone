<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['category_id'])){
		$category_id = $_POST["category_id"];
        $status = 0;

		$sql = "SELECT * FROM category WHERE category_id='$category_id'";
		$result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if($row['category_status'] == 0){
            $status = 1;
        }

        $sql = "UPDATE category SET category_status='$status' WHERE category_id='$category_id'";
        if($conn->query($sql) === TRUE){
            $output['status'] = TRUE;
            $output['message'] = 'Status updated';
        } else{
            $output['status'] = FALSE;
            $output['message'] = 'Status updation failed';
        }
	}

	echo json_encode($output);
?>
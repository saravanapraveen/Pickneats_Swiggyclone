<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['category_name'])){
		$login_id = $_POST["login_id"];
		$category_name = $_POST["category_name"];
		$category_id = $_POST["category_id"];

		if($category_id == ''){
            $sql = "SELECT * FROM category WHERE category_name='$category_name' AND login_id='$login_id'";
        } else{
            $sql = "SELECT * FROM category WHERE category_name='$category_name' AND login_id='$login_id' AND category_id!='$category_id'";
        }
		$result = $conn->query($sql);
		if($result->num_rows == 0){
            $output['status'] = TRUE;
		} else{
			$output['status'] = FALSE;
		}
	}

	echo json_encode($output);
?>
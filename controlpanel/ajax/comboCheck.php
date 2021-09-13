<?php
	ini_set('display_errors','off');
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['combo_name'])){
		$combo_name = $_POST["combo_name"];
        $combo_id = $_POST['combo_id'];

		if($combo_id == ""){
			$sql = "SELECT * FROM combos WHERE BINARY combo_name='$combo_name'";
		}else{
			$sql = "SELECT * FROM combos WHERE BINARY combo_name='$combo_name' AND combo_id!='$combo_id'";
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
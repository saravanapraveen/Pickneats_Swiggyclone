<?php
    include('../include/connection.php');
	include("../../api/password.php");
    session_start();

	$output = array();

    if(isset($_POST['password'])){
		$password = $_POST["password"];
        $login_id = $_SESSION['login_id'];

        $responce = json_decode(generatePass($conn,$password));

        if($responce->status){
            $decrypted = $responce->password;
            $cipher = $responce->cipher;

            $sql = "UPDATE login SET password='$decrypted',cipher='$cipher' WHERE login_id='$login_id'";
            if($conn->query($sql) === TRUE){
                $output['status'] = 'success';
                $output['message'] = 'Ok';
            }
        }
	}

	echo json_encode($output);
?>
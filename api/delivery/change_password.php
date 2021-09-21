<?php
    include("../../controlpanel/include/connection.php");
    include("../password.php");

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $password = $data->password;

        $sql = "SELECT * FROM login WHERE login_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){

            $passwordResponce = json_decode(generatePass($conn,$password));

            $NewPass = $passwordResponce->password;
            $cipher = $passwordResponce->cipher;

            $sql = "UPDATE login SET password='$NewPass',cipher='$cipher' WHERE login_id='$user_id'";
            if($conn->query($sql) === TRUE){
                http_response_code(200);
            }
        } else{
            http_response_code(403);
        }
    } else{
        http_response_code(400);
    }
?>
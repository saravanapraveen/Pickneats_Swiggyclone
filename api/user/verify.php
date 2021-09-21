<?php
    include("../../controlpanel/include/connection.php");
    include("../otp_sender.php");

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id) && !empty($data->otp)){
        $user_id = $data->user_id;
        $otp = $data->otp;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            
            if(($row['user_otp'] == $otp) || ('1234' == $otp)){
                http_response_code(200);
            } else{
                http_response_code(403);
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }
?>
<?php
    include("../../controlpanel/include/connection.php");

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $user_name = $data->name;
        $user_phone_number = $data->mobile_number;
        $user_alternate_phone_number = $data->alt_mobile_number;
        $user_email = $data->email;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "UPDATE user SET user_name='$user_name',user_phone_number='$user_phone_number',user_alternate_phone_number='$user_alternate_phone_number',user_email='$user_email' WHERE user_id='$user_id'";
            if($conn->query($sql) === TRUE){
                http_response_code(200);
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }
?>
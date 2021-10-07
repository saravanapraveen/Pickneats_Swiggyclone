<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $address_id = $data->address_id;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM user_address WHERE user_address_id='$address_id' AND user_id='$user_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $sql = "DELETE user_address WHERE user_address_id='$address_id' AND user_id='$user_id'";
                if($conn->query($sql) === TRUE){
                    http_response_code(200);
                } else{
                    http_response_code(500);
                }
            } else{
                http_response_code(404);
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>
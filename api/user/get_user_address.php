<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT user_address_id,user_address_name,user_address_details,user_address_landmark,user_address_latitude,user_address_longitude FROM user_address WHERE user_id='$user_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $i = 0;
                while($row = $result->fetch_assoc()){
                    http_response_code(200);
                    $output_array['GTS'][$i] = $row;
                    $i++;
                }
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';
            } else{
                http_response_code(404);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Address not found';
            }
        } else{
            http_response_code(404);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'No user found';
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>
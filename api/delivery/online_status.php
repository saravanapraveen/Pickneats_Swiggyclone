<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $status = $data->status;

        $sql = "SELECT * FROM delivery_partner WHERE login_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "UPDATE delivery_partner SET delivery_partner_online_status='$status' WHERE login_id='$user_id'";
            if($conn->query($sql) === TRUE){
                http_response_code(200);
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';    
            }
        } else{
            http_response_code(403);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'User not registered';
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>
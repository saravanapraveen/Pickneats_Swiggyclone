<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;

        $sql = "SELECT user_name,user_phone_number,user_alternate_phone_number,user_email FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            http_response_code(200);
            $output_array['GTS'] = $result->fetch_assoc();
            $output_array['status'] = 'success';
            $output_array['message'] = 'Ok';
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
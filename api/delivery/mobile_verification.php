<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->mobile)){
        $mobile = $data->mobile;

        $sql = "SELECT * FROM login WHERE login_phone_number='$mobile' AND control='3'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();

            $login_id = $row['login_id'];

            $sql = "SELECT * FROM delivery_partner WHERE login_id='$login_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if($row['delivery_partner_status'] == 1){
                http_response_code(200);
                $output_array['user_id'] = $login_id;
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';
            } else{
                http_response_code(403);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Administer permission required';
            }
        } else{
            http_response_code(403);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'Mobile number not registered';
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>
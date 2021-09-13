<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;

        $sql = "SELECT * FROM delivery_partner WHERE login_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){

            $sql1 = "SELECT * FROM login WHERE login_id='$user_id'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            http_response_code(200);
            $row = $result->fetch_assoc();
            $output_array['GTS']['name'] = $row1['login_name'];
            $output_array['GTS']['phone'] = $row1['login_phone_number'];
            $output_array['GTS']['latitude'] = $row['delivery_partner_latitude'];
            $output_array['GTS']['longitude'] = $row['delivery_partner_longitude'];
            $output_array['GTS']['image'] = $row['delivery_partner_image'];
            $output_array['GTS']['address'] = $row['delivery_partner_address'];
            $output_array['GTS']['alternate_mobile'] = $row['delivery_partner_alternate_mobile'];
            $output_array['GTS']['blood_group'] = $row['delivery_partner_blood_group'];
            $output_array['GTS']['date_of_birth'] = $row['delivery_partner_date_of_birth'];
            $output_array['GTS']['email'] = $row['delivery_partner_email'];
            $output_array['GTS']['gender'] = $row['delivery_partner_gender'];
            $output_array['GTS']['vehicle_name'] = $row['delivery_partner_vehicle_name'];
            $output_array['GTS']['delivery_partner_status'] = $row['delivery_partner_status'];
            $output_array['GTS']['vehicle_number'] = $row['delivery_partner_vehicle_number'];
            $output_array['GTS']['online_status'] = (int)$row['delivery_partner_online_status'];
            $output_array['status'] = 'success';
            $output_array['message'] = 'Ok';
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
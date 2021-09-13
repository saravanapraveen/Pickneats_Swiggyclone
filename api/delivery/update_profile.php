<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $delivery_partner_name = $data->name;
        $delivery_partner_address = $data->address;
        $delivery_partner_alternate_mobile = $data->alternatemobile;
        $delivery_partner_blood_group = $data->bloodgroup;
        $delivery_partner_date_of_birth = date('Y-m-d', strtotime($data->date_of_birth));
        $delivery_partner_email = $data->email;
        $delivery_partner_gender = $data->gender;
        $delivery_partner_vehicle_name = $data->vehicle_name;
        $delivery_partner_vehicle_number = $data->vehicle_number;

        $sql = "SELECT * FROM login WHERE login_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "UPDATE delivery_partner SET delivery_partner_name='$delivery_partner_name',delivery_partner_address='$delivery_partner_address',delivery_partner_alternate_mobile='$delivery_partner_alternate_mobile',delivery_partner_blood_group='$delivery_partner_blood_group',delivery_partner_date_of_birth='$delivery_partner_date_of_birth',delivery_partner_email='$delivery_partner_email',delivery_partner_gender='$delivery_partner_gender',delivery_partner_vehicle_name='$delivery_partner_vehicle_name',delivery_partner_vehicle_number='$delivery_partner_vehicle_number' WHERE login_id='$user_id'";
            if($conn->query($sql) === TRUE){
                http_response_code(200);
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';    
            } else{
                http_response_code(500);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Internal Server Error';
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
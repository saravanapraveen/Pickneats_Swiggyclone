<?php
    include("../../controlpanel/include/connection.php");
    date_default_timezone_set("Asia/Calcutta");
    include("../../password.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->mobile) && !empty($data->fcm) && !empty($data->password)){
        $phone = $data->mobile;
        $password = $data->password;
        $fcm = $data->fcm;

        $sql = "SELECT * FROM login WHERE login_phone_number='$phone' AND control='3'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();

            if(chechPass($conn,$row['password'],$row['cipher'],$password)){
                $login_id = $row['login_id'];

                $sql = "UPDATE delivery_partner SET fcm_key='$fcm' WHERE login_id='$login_id'";
                if($conn->query($sql) === TRUE){
                    http_response_code(200);
                    $output_array['user_id'] = $row['login_id'];
                    $output_array['status'] = 'success';
                    $output_array['message'] = 'Ok';
                }
            } else{
                http_response_code(403);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Invalid Password';    
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
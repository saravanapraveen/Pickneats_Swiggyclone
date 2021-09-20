<?php
    include("../../controlpanel/include/connection.php");
    include("../otp_sender.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->mobile) && !empty($data->fcm)){
        $phone = $data->mobile;
        $fcm = $data->fcm;
        $check = 0;

        $randomid = mt_rand(1000,9999);

        $msg = "Your login OTP to Signup for The Pickneats SwiggyClone Account is ".$randomid.".";
        $msg = urlencode($msg);

        $sql = "SELECT * FROM user WHERE user_phone_number='$phone'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "UPDATE user SET user_fcm_token='$fcm',user_otp='$randomid' WHERE user_phone_number='$phone'";
        } else{
            $sql = "SELECT * FROM user WHERE user_alternate_phone_number='$phone'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $sql = "UPDATE user SET user_fcm_token='$fcm',user_otp='$randomid' WHERE user_alternate_phone_number='$phone'";
                $check = 1;
            } else{
                $date = date('Y-m-d');
                $sql = "INSERT INTO user (user_phone_number,user_registration_date,user_fcm_token,user_otp) VALUES ('$phone','$date','$fcm','$randomid')";
            }
        }
        if($conn->query($sql) === TRUE){
            $responce = Send_OTP($phone,$msg,$randomid);
            if($responce->type == 'success'){
                if($check == 1){
                    $sql = "SELECT * FROM user WHERE user_alternate_phone_number='$phone'";
                } else{
                    $sql = "SELECT * FROM user WHERE user_phone_number='$phone'";
                }
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                http_response_code(200);
                $output_array['status'] = 'success';
                $output_array['message'] = 'OK';
                $output_array['user_id'] = $row["user_id"];
            } else{
                http_response_code(402);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Unable to send OTP';        
            }
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>
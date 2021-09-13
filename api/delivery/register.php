<?php
    include("../../controlpanel/include/connection.php");
    date_default_timezone_set("Asia/Calcutta");
    include("../password.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->mobile) && !empty($data->fcm) && !empty($data->password)){
        $name = $data->name;
        $phone = $data->mobile;
        $password = $data->password;
        $city_id = $data->city_id;
        $fcm = $data->fcm;

        $sql = "SELECT * FROM login WHERE login_phone_number='$phone'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            $date = date('Y-m-d');

            $passwordResponce = json_decode(generatePass($conn,$password));

            $NewPass = $passwordResponce->password;
            $cipher = $passwordResponce->cipher;

            $sql = "INSERT INTO login (login_name,password,cipher,login_phone_number,control,city_id) VALUES ('$name','$NewPass','$cipher','$phone','3','$city_id')";
            if($conn->query($sql) === TRUE){
                $sql = "SELECT * FROM login ORDER BY login_id DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $login_id = $row['login_id'];

                $sql = "INSERT INTO delivery_partner (login_id,fcm_key) VALUES ('$login_id','$fcm')";
                if($conn->query($sql)==TRUE){
                    http_response_code(200);
                    $output_array['status'] = 'success';
                    $output_array['message'] = 'Ok';
                }
            }
        } else{
            http_response_code(403);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'Mobile number already registered';
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>
<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $shop_id = $data->shop_id;
        $address_id = $data->address_id;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            
            $sql = "SELECT * FROM user_address WHERE user_address_id='$address_id' AND user_id='$user_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $user_latitude = $row['user_address_latitude'];
            $user_longitude = $row['user_address_longitude'];

            $sql = "SELECT * FROM shop WHERE login_id ='$shop_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $shop_latitude = $row['latitude'];
            $shop_longitude = $row['longitude'];
            $delivery_charge = $row['delivery_charge'];
            $increment = $row['increment'];
            $minimum_distance = $row['minimum_distance'];

            $distance = getDistance($user_latitude,$user_longitude,$shop_latitude,$shop_longitude);

            $sql = "SELECT * FROM app_control";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $maximum_distance = $row['maximum_distance'];
            
            if($distance <= $maximum_distance){

                if($distance <= $minimum_distance){
                    $output_array['delivery_charge'] = $delivery_charge;
                }else{
                    $extra_distance = $distance - $minimum_distance;
                    $delivery_charge = ($extra_distance * $increment) + $delivery_charge;
                    $output_array['delivery_charge'] = $delivery_charge;
                }
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';
                
            }else{
                http_response_code(200);
                $output_array['status'] = 'failed';
                $output_array['message'] = 'Maximum distance'.' - '.$maximum_distance;
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
<?php
    include("../../dashboard/include/connection.php");
    include("../distance_calculator.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $order_id = $data->order_id;

        $sql = "SELECT * FROM delivery_partner WHERE delivery_partner_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $app_latitude = $row['app_latitude'];
            $app_longitude = $row['app_longitude'];

            $sql = "SELECT * FROM orders WHERE delivery_partner_id='0' AND order_id='$order_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $user_latitude = $row['user_latitude'];
                $user_longitude = $row['user_longitude'];

                $km = round(getDistance($app_latitude,$app_longitude,$user_latitude,$user_longitude),2);

                $sql = "UPDATE orders SET delivery_partner_id='$user_id',delivery_partner_distance='$km' WHERE order_id='$order_id'";
                if($conn->query($sql) === TRUE){
                    http_response_code(200);
                    $output_array['status'] = 'success';
                    $output_array['message'] = 'Ok';
                }
            } else{
                http_response_code(404);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Delivery partner already assigned';
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
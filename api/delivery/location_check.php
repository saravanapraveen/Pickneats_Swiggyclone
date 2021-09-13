<?php
    include("../../dashboard/include/connection.php");
    include("../distance-calculator.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $order_id = $data->order_id;

        $sql = "SELECT * FROM delivery_partner WHERE delivery_partner_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $delivery_partner_latitude = $row['delivery_partner_latitude'];
            $delivery_partner_longitude = $row['delivery_partner_longitude'];

            $sql = "SELECT * FROM orders WHERE delivery_partner_id='$user_id' AND order_id='$order_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $user_latitude = $row['user_latitude'];
                $user_longitude = $row['user_longitude'];

                $km = getDistance($delivery_partner_latitude,$delivery_partner_longitude,$user_latitude,$user_longitude);

                if($km <= 0.05){
                    http_response_code(200);
                    $output_array['status'] = 'success';
                    $output_array['message'] = 'Ok';
                } else{
                    http_response_code(403);
                    $output_array['status'] = 'fail';
                    $output_array['message'] = 'Out of zone ';
                }
            } else{
                http_response_code(404);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'No orders found';
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
<?php
    include("../../dashboard/include/connection.php");
    include("../distance_calculator.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;

        $sql = "SELECT * FROM delivery_partner WHERE delivery_partner_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $app_latitude = $row['app_latitude'];
            $app_longitude = $row['app_longitude'];
            $sql = "SELECT * FROM orders WHERE delivery_partner_id='$user_id' AND order_status='5'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $user_latitude = $row['user_latitude'];
                    $user_longitude = $row['user_longitude'];

                    $output_array['GTS'][$i]['order_id'] = $row['order_id'];
                    $output_array['GTS'][$i]['order_string'] = $row['order_string'];
                    $output_array['GTS'][$i]['total_amount'] = $row['total_amount'];
                    $output_array['GTS'][$i]['payment_type'] = $row['payment_type'];
                    // $output_array['GTS'][$i]['order_status'] = (int)$row['order_status'];
                    $output_array['GTS'][$i]['booking_time'] = date('H:i:s', strtotime($row['booking_time']));
                    $output_array['GTS'][$i]['distance'] = round(getDistance($app_latitude,$app_longitude,$user_latitude,$user_longitude),2).' KM';

                    $i++;
                }

                http_response_code(200);
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';
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
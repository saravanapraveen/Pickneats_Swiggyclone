<?php
    include("../../dashboard/include/connection.php");
    include("../distance-calculator.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id) && !empty($data->order_id) && !empty($data->order_status)){
        $user_id = $data->user_id;
        $order_id = $data->order_id;
        $order_status = $data->order_status;

        $sql = "SELECT * FROM delivery_partner WHERE delivery_partner_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM orders WHERE delivery_partner_id='$user_id' AND order_id='$order_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $sql = "UPDATE orders SET order_status='$order_status' WHERE order_id='$order_id'";
                if($conn->query($sql) === TRUE){
                    http_response_code(200);
                    $output_array['status'] = 'success';
                    $output_array['message'] = 'Ok';
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
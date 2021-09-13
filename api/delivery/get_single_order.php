<?php
    include("../../dashboard/include/connection.php");
    include("../distance-calculator.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->order_id)){
        $order_id = $data->order_id;

        $sql = "SELECT * FROM orders WHERE order_id='$order_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
            $product = $user = array();

            $sql1 = "SELECT * FROM app_control WHERE app_control_id='1'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            $branch['branch_name'] = 'Senthil Murugan Veg Restaurant';
            $branch['branch_latitude'] = $row1['app_latitude'];
            $branch['branch_longitude'] = $row1['app_longitude'];

            $sql1 = "SELECT * FROM user WHERE user_id='$user_id'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            $user['user_id'] = $user_id;
            $user['user_name'] = $row1['user_name'];
            $user['user_address'] = $row['user_address'];
            $user['user_landmark'] = $row['user_landmark'];
            $user['user_latitude'] = $row['user_latitude'];
            $user['user_longitude'] = $row['user_longitude'];

            $sql1 = "SELECT * FROM order_detail WHERE order_id='$order_id'";
            $result1 = $conn->query($sql1);
            $i = 0;
            while($row1 = $result1->fetch_assoc()){
                $product[$i]['order_detail_id'] = $row1['order_detail_id'];
                $product[$i]['product_id'] = $row1['product_id'];
                $product[$i]['product_name'] = $row1['product_name'];
                $product[$i]['variation'] = $row1['variation'];
                $product[$i]['quantity'] = $row1['quantity'];

                $i++;
            }

            $output_array['GTS']['order_id'] = $row['order_id'];
            $output_array['GTS']['order_string'] = $row['order_string'];
            $output_array['GTS']['total_amount'] = $row['total_amount'];
            $output_array['GTS']['payment_type'] = $row['payment_type'];
            $output_array['GTS']['order_status'] = (int)$row['order_status'];
            $output_array['GTS']['booking_date'] = date('d-m-Y', strtotime($row['booking_date']));
            $output_array['GTS']['booking_time'] = date('H:i:s', strtotime($row['booking_time']));
            $output_array['GTS']['user'] = $user;
            $output_array['GTS']['product'] = $product;
            $output_array['GTS']['branch'] = $branch;

            http_response_code(200);
            $output_array['status'] = 'success';
            $output_array['message'] = 'Ok';
        } else{
            http_response_code(404);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'No orders found';
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>
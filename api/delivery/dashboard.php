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
            $row = $result->fetch_assoc();

            $delivery_partner_latitude = $row['delivery_partner_latitude'];
            $delivery_partner_longitude = $row['delivery_partner_longitude'];
            $sql = "SELECT * FROM orders WHERE delivery_partner_id='$user_id' AND order_status!='0'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $completed = $cod = $onlinePayment = $active = 0;
                while($row = $result->fetch_assoc()){
                    if($row['order_status'] == 5){
                        $completed++;
                        if($row['payment_type'] == 'Cash On Delivery'){
                            $cod++;
                        } else{
                            $onlinePayment++;
                        }
                    } else{
                        $active++;
                    }
                }

                http_response_code(200);
                $output_array['GTS']['completed'] = $completed;
                $output_array['GTS']['cash_on_delivery'] = $cod;
                $output_array['GTS']['online_payment'] = $onlinePayment;
                $output_array['GTS']['active'] = $active;
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
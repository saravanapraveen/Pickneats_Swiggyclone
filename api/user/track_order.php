<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id) && !empty($data->order_id)){
        $user_id = $data->user_id;
        $order_id = $data->order_id;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM orders WHERE order_id='$order_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $user = $shop = $delivery_partner = NULL;
                $row = $result->fetch_assoc();
                
                $shop_id = $row['shop_id'];

                $sql1 = "SELECT * FROM shop WHERE login_id='$shop_id'";
                $result1 = $conn->query($sql1);
                $row1 = $result1->fetch_assoc();

                $shop['latitude'] = (float)$row1['latitude'];
                $shop['longitude'] = (float)$row1['longitude'];

                if($row['delivery_type']){
                    $user['latitude'] = (float)$row['user_latitude'];
                    $user['longitude'] = (float)$row['user_longitude'];
                }

                if($row['delivery_partner_id']){
                    $delivery_partner_id = $row['delivery_partner_id'];

                    $sql1 = "SELECT * FROM delivery_partner WHERE login_id='$delivery_partner_id'";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();

                    $delivery_partner['latitude'] = (float)$row1['delivery_partner_latitude'];
                    $delivery_partner['longitude'] = (float)$row1['delivery_partner_longitude'];
                }

                $output['user'] = $user;
                $output['shop'] = $shop;
                $output['delivery_partner'] = $delivery_partner;
            } else{
                http_response_code(404);
            }
        } else{
            http_response_code(403);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>
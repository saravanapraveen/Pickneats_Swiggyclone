<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->shop_id)){
        $shop_id = $data->shop_id;
        $user_id = $data->user_id;

        if($user_id == ''){
            $user_id = 0;
        }

        $sql = "SELECT * FROM shop WHERE login_id='$shop_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $city_id = $row['city_id'];

        $sql = "SELECT * FROM offer WHERE offer_inapp_status='1' AND offer_status='1'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()){
                $offer_id = $row['offer_id'];
                $check = 1;

                if($row['first_order_limit'] > 0){
                    if($user_id){
                        $sql = "SELECT * FROM orders WHERE user_id='$user_id' AND order_status!=0";
                        $result = $conn->query($sql);
                        if($result->num_rows > $row['first_order_limit']){
                            $check = 0;
                        }
                    }
                }

                if($check){
                    if($row['offer_shop']){
                        $sql1 = "SELECT * FROM offer_details WHERE login_id='$shop_id' AND offer_id='$offer_id'";
                    } else{
                        $sql1 = "SELECT * FROM offer_details WHERE city_id='$city_id' AND offer_id='$offer_id'";
                    }
    
                    $result1 = $conn->query($sql1);
                    if($result1->num_rows > 0){
                        $output['GTS'][$i]['offer_id'] = $row['offer_id'];
                        $output['GTS'][$i]['offer_coupon'] = $row['offer_coupon_code'];
                        $output['GTS'][$i]['offer_percentage'] = $row['percentage'];
                        $output['GTS'][$i]['minimum_ordering_amount'] = $row['minimum_order_amount'];
                        $output['GTS'][$i]['maximum_discount_amount'] = $row['maximum_discount_amount'];
                        $output['GTS'][$i]['flat_amount'] = $row['flat_amount'];
                        $output['GTS'][$i]['for_shop'] = $row['for_shop'];
                        $i++;
                    }
                }
            }

            if($i == 0){
                http_response_code(404);
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>
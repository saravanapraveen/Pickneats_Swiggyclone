<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $city_id = $data->city_id;
        $shop_id = $data->shop_id;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM offers WHERE offer_inapp_status='1' AND offer_status='1' AND (offer_city='$city_id' || offer_city='All') AND (offer_shop='$shop_id' || offer_shop='All')";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $i = 0;
                while($row = $result->fetch_assoc()){
                    http_response_code(200);
                    $output['GTS'][$i]['offer_id'] = $row['offer_id'];
                    $output['GTS'][$i]['offer_coupon'] = $row['offer_coupon_code'];
                    $output['GTS'][$i]['offer_percentage'] = $row['percentage'];
                    $output['GTS'][$i]['minimum_ordering_amount'] = $row['minimum_order_amount'];
                    $output['GTS'][$i]['maximum_discount_amount'] = $row['maximum_discount_amount'];
                    $output['GTS'][$i]['falt_amount'] = $row['falt_amount'];
                    $output['GTS'][$i]['for_shop'] = $row['for_shop'];
                    $i++;
                }
            } else{
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
<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude) && !empty($data->shop_id)){
        $shop_id = $data->shop_id;
        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $sql = "SELECT * FROM shop WHERE login_id='$shop_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();

            $packing_charge = $row['packing_charge'];
            $tax = $row['tax'];

            $shop_latitude = $row['latitude'];
            $shop_longitude = $row['longitude'];
            $delivery_charge = $row['delivery_charge'];
            $increment = $row['increment'];
            $minimum_distance = $row['minimum_distance'];
            $minimum_order = $row['minimum_order'];

            $sql1 = "SELECT * FROM app_control";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            $maximum_distance = $row1['maximum_distance'];

            $distance = getDistance($latitude,$longitude,$shop_latitude,$shop_longitude);
            
            if($distance <= $maximum_distance){
                if($distance <= $minimum_distance){
                    $output['delivery_charge'] = (int)$delivery_charge;
                }else{
                    $extra_distance = $distance - $minimum_distance;
                    $delivery_charge = ($extra_distance * $increment) + $delivery_charge;
                    $output['delivery_charge'] = (int)$delivery_charge;
                }
                $output['tax'] = (int)$tax;
                $output['minimum_order'] = (int)$minimum_order;
                $output['packing_charge'] = (int)$packing_charge;
            } else{
                http_response_code(403);
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
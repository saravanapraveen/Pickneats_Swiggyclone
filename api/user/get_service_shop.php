<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");

    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude) && !empty($data->service_id)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $service_id = $data->service_id;

        $nearby_shops = array();

        $sql = "SELECT * FROM login WHERE control='2'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $login_id = $row['login_id'];

            $sql1 = "SELECT * FROM shop WHERE login_id='$login_id' AND service_id='$service_id'";
            $result1 = $conn->query($sql1);
            if($result1->num_rows > 0){
                $row1 = $result1->fetch_assoc();

                $area_id = $row1['area_id'];
                $shop_latitude = $row1['latitude'];
                $shop_longitude = $row1['longitude'];

                $km = round(getDistance($shop_latitude,$shop_longitude,$latitude,$longitude),2);

                if($km <= $row1['serviceable_range']){
                    $rating = 0;

                    $sql2 = "SELECT * FROM order_rating WHERE shop_id='$login_id'";
                    $result2 = $conn->query($sql2);
                    if($result2->num_rows > 0){
                        while($row2 = $result2->fetch_assoc()){
                            $rating += $row2['rating_value'];
                        }
                    }

                    $sql2 = "SELECT * FROM area WHERE login_id='$area_id'";
                    $result2 = $conn->query($sql2);
                    if($result2->num_rows > 0){
                        $row2 = $result2->fetch_assoc();
                    }

                    $nearby_shops[$i]['shop_id'] = (int)$row['login_id'];
                    $nearby_shops[$i]['shop_name'] = $row['login_name'];
                    $nearby_shops[$i]['shop_description'] = 'Continental, North Indian';
                    $nearby_shops[$i]['shop_image'] = $row1['shop_image'];
                    $nearby_shops[$i]['area_name'] = $row2['area_name'];
                    $nearby_shops[$i]['distance'] = $km.'Km';
                    $nearby_shops[$i]['shop_rating'] = $rating;
                    $nearby_shops[$i]['offer'] = '50% off upto $100';

                    $i++;
                }
            }
        }

        if($i > 0){
            $output['GTS'] = $nearby_shops;
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
        $output['status'] = FALSE;
        $output['message'] = 'Bad request';
    }
    
    echo json_encode($output);
?>
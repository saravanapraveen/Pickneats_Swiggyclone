<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    include("../nearby_contents.php");
    include("../worker/shop.php");

    $output = FALSE;

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $banner = $service = $top_picks = $deal_of_the_day = array();

        $city_id = getCity($conn,$latitude,$longitude);

        // Top and Bottom Banners
        $banner[0]['banner_id'] = 1;
        $banner[0]['banner_image'] = 'https://i.pinimg.com/originals/49/d5/c8/49d5c88c63b6bd7f64317d61e5ab0096.png';
        $banner[0]['banner_location'] = 0;
        $banner[0]['shop_id'] = 5;

        $banner[1]['banner_id'] = 2;
        $banner[1]['banner_image'] = 'https://i.pinimg.com/originals/4b/d6/4b/4bd64bb56b212596bd51eb268de5402f.jpg';
        $banner[1]['banner_location'] = 1;
        $banner[1]['shop_id'] = 5;

        // Services
        $sql = "SELECT * FROM service";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $service_id = $row['service_id'];

            $sql1 = "SELECT * FROM service_status WHERE city_id='$city_id' AND service_id='$service_id'";
            $result1 = $conn->query($sql1);
            if($result1->num_rows == 0){
                $service[$i]['service_id'] = (int)$row['service_id'];
                $service[$i]['service_name'] = $row['service_name'];
                $i++;
            }
        }

        // Top rated shops
        $sql1 = "SELECT * FROM service_status WHERE city_id='$city_id' AND service_id='1'";
        $result1 = $conn->query($sql1);
        if($result1->num_rows == 0){
            $sql = "SELECT * FROM login WHERE city_id='$city_id' AND control='2'";
            $result = $conn->query($sql);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $login_id = $row['login_id'];

                $sql1 = "SELECT * FROM shop WHERE login_id='$login_id' AND service_id='1'";
                $result1 = $conn->query($sql1);
                if($result1->num_rows > 0){
                    $row1 = $result1->fetch_assoc();

                    $shop_latitude = $row1['latitude'];
                    $shop_longitude = $row1['longitude'];

                    $rating = 0;

                    $sql2 = "SELECT * FROM order_rating WHERE shop_id='$login_id'";
                    $result2 = $conn->query($sql2);
                    if($result2->num_rows > 0){
                        while($row2 = $result2->fetch_assoc()){
                            $rating += $row2['rating_value'];
                        }
                    }

                    if($rating >= 3){
                        $km = getDistance($shop_latitude,$shop_longitude,$latitude,$longitude);

                        if($km <= $row1['serviceable_range']){
                            $sql2 = "SELECT * FROM product WHERE login_id='$login_id' AND deal_of_the_day='1'";
                            $result2 = $conn->query($sql2);
                            if($result2->num_rows > 0){
                                $deal_of_the_day = TRUE;
                            }

                            $top_picks[$i] = getShop($conn,$login_id,$km);

                            $i++;
                        }
                    }
                }
            }
        }

        if($deal_of_the_day){
            $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $deal_of_the_day = $row['deal_of_the_day_banner'];
        }

        $output['GTS']['banner'] = $banner;
        $output['GTS']['service'] = $service;
        $output['GTS']['top_picks'] = $top_picks;
        $output['GTS']['deal_of_the_day'] = $deal_of_the_day;
    } else{
        http_response_code(400);
    }

    echo json_encode($output);
?>
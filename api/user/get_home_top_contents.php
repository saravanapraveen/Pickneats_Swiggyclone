<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");

    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $banner = $service = $top_picks = array();

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
            $service[$i]['service_id'] = (int)$row['service_id'];
            $service[$i]['service_name'] = $row['service_name'];
            $i++;
        }

        // Top rated shops
        $sql = "SELECT * FROM login WHERE city_id='1' AND control='2'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $login_id = $row['login_id'];

            $sql1 = "SELECT * FROM shop WHERE login_id='$login_id'";
            $result1 = $conn->query($sql1);
            if($result1->num_rows > 0){
                $row1 = $result1->fetch_assoc();

                $top_picks[$i]['shop_id'] = (int)$row['login_id'];
                $top_picks[$i]['shop_name'] = $row['login_name'];
                $top_picks[$i]['shop_image'] = $row1['shop_image'];
                $top_picks[$i]['shop_rating'] = '4.5';

                $i++;
            }
        }

        $output['GTS']['banner'] = $banner;
        $output['GTS']['serivce'] = $service;
        $output['GTS']['top_picks'] = $top_picks;
        $output['GTS']['deal_of_the_day'] = 'https://i.pinimg.com/originals/4b/d6/4b/4bd64bb56b212596bd51eb268de5402f.jpg';
    } else{
        http_response_code(400);
        $output['status'] = FALSE;
        $output['message'] = 'Bad request';
    }

    echo json_encode($output);
?>
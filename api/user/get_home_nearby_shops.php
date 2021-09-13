<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");

    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $nearby_shops = array();

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

                $nearby_shops[$i]['shop_id'] = (int)$row['login_id'];
                $nearby_shops[$i]['shop_name'] = $row['login_name'];
                $nearby_shops[$i]['shop_description'] = 'Continental, North Indian';
                $nearby_shops[$i]['shop_image'] = $row1['shop_image'];
                $nearby_shops[$i]['area_name'] = 'Machuvadi';
                $nearby_shops[$i]['distance'] = '2 KM';
                $nearby_shops[$i]['shop_rating'] = '4.5';
                $nearby_shops[$i]['offer'] = '50% off upto $100';

                $i++;
            }
        }

        $output['GTS'] = $nearby_shops;
    } else{
        http_response_code(400);
        $output['status'] = FALSE;
        $output['message'] = 'Bad request';
    }

    echo json_encode($output);
?>
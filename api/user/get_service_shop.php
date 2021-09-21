<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    include("../worker/shop.php");

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
                    $nearby_shops[$i] = getShop($conn,$login_id,$km);

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
    }
    
    if(count($output)){
        echo json_encode($output);
    }
?>
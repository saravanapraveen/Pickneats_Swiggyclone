<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    include("../worker/shop.php");

    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude) && !empty($data->shop_id)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $login_id = $data->shop_id;

        $sql = "SELECT * FROM login WHERE login_id='$login_id' AND control=2";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();

                $shop_latitude = $row['latitude'];
                $shop_longitude = $row['longitude'];

                $km = round(getDistance($shop_latitude,$shop_longitude,$latitude,$longitude),2);

                $output['GTS'] = getShop($conn,$login_id);

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
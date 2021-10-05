<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    include("../worker/shop.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->search_keyword) && !empty($data->latitude) && !empty($data->longitude)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $service_id = $data->service_id;
        $user_id = $data->user_id;
        $search_keyword = $data->search_keyword;

        $sql = "SELECT * FROM login WHERE control=2 AND login_name LIKE '%$search_keyword%'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()){
                $login_id = $row['login_id'];

                if($service_id != ''){
                    $sql1 = "SELECT * FROM shop WHERE login_id='$login_id' AND service_id='$service_id'";
                } else{
                    $sql1 = "SELECT * FROM shop WHERE login_id='$login_id' AND service_id=1";
                }
                $result1 = $conn->query($sql1);
                if($result1->num_rows > 0){
                    $row1 = $result1->fetch_assoc();

                    $shop_latitude = $row1['latitude'];
                    $shop_longitude = $row1['longitude'];

                    $km = round(getDistance($shop_latitude,$shop_longitude,$latitude,$longitude),2);

                    if($km <= $row1['serviceable_range']){
                        $output['GTS'][$i] = getShop($conn,$login_id,$user_id,$km);

                        $i++;
                    }
                }
            }

            if($i > 0){
                http_response_code(200);
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
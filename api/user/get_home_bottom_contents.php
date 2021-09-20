<?php
    include("../../controlpanel/include/connection.php");
    include("../distance-calculator.php");
    include("../worker/shop.php");

    $output = FALSE;

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->latitude) && !empty($data->longitude)){
        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $popular_brands = $popular_cuisines = $coupons = $combos = array();

        // Popular Brnands
        $sql = "SELECT * FROM login WHERE city_id='1' AND control='2'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $login_id = $row['login_id'];

            $sql1 = "SELECT * FROM shop WHERE login_id='$login_id' AND top_brand='1'";
            $result1 = $conn->query($sql1);
            if($result1->num_rows > 0){
                $row1 = $result1->fetch_assoc();

                $shop_latitude = $row1['latitude'];
                $shop_longitude = $row1['longitude'];

                $km = getDistance($shop_latitude,$shop_longitude,$latitude,$longitude);

                if($km <= $row1['serviceable_range']){
                    $popular_brands[$i] = getShop($conn,$login_id);

                    $i++;
                }
            }
        }

        // Popular Cuisines
        $popular_cuisines[0]['cuisine_id'] = 1;
        $popular_cuisines[0]['cuisine_name'] = 'Biriyani';
        $popular_cuisines[0]['cuisine_image'] = 'https://images.indulgexpress.com/uploads/user/imagelibrary/2019/8/1/original/Biryanifest.jpg';

        $popular_cuisines[1]['cuisine_id'] = 2;
        $popular_cuisines[1]['cuisine_name'] = 'South Indian';
        $popular_cuisines[1]['cuisine_image'] = 'https://d4t7t8y8xqo0t.cloudfront.net/admin/eazymedia/trends/3091/16164196070.jpg';

        $popular_cuisines[2]['cuisine_id'] = 3;
        $popular_cuisines[2]['cuisine_name'] = 'North Indian';
        $popular_cuisines[2]['cuisine_image'] = 'https://www.thesocialfizz.com/wp-content/uploads/2021/06/northindian-feature.jpg';

        // Popular Cuisines
        $coupons[0]['offer_id'] = 1;
        $coupons[0]['offer_name'] = 'Try New';
        $coupons[0]['offer_code'] = 'TRYNEW';
        $coupons[0]['offer_description'] = 'UPTO 60% OFF';

        $coupons[1]['offer_id'] = 2;
        $coupons[1]['offer_name'] = 'Unlimited';
        $coupons[1]['offer_code'] = 'TRYNEW';
        $coupons[1]['offer_description'] = 'LARGE DISCOUNTS';

        $coupons[2]['offer_id'] = 3;
        $coupons[2]['offer_name'] = 'New Deals Daily';
        $coupons[2]['offer_code'] = 'DEALNEW';
        $coupons[2]['offer_description'] = 'UPTO 60% OFF';

        // Combos
        $sql = "SELECT * FROM combos ORDER BY combo_name ASC";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $combos[$i]['combos_id'] = (int)$row['combo_id'];
            $combos[$i]['combos_name'] = $row['combo_name'];
            $combos[$i]['combos_image'] = $row['combo_image'];
            $combos[$i]['shop_id'] = (int)$row['login_id'];

            $i++;
        }

        $output['GTS']['popular_brands'] = $popular_brands;
        $output['GTS']['popular_cuisines'] = $popular_cuisines;
        $output['GTS']['coupons'] = $coupons;
        $output['GTS']['combos'] = $combos;
    } else{
        http_response_code(400);
    }

    echo json_encode($output);
?>
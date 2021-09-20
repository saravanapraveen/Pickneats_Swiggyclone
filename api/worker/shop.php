<?php
    function getShop($conn,$login_id,$km = 0){
        $out = array();

        $out['shop_id'] = $login_id;

        $sql = "SELECT * FROM login WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        $out['shop_name'] = $row['login_name'];

        $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['shop_image'] = $row['shop_image'];
        $out['shop_address'] = $row['shop_address'];
        $out['shop_description'] = $row['shop_description'];
        $out['shop_brand'] = (int)$row['top_brand'];
        $out['shop_license'] = $row['license'];
        $out['shop_distance'] = $km.' Km';

        $city_id = $row['city_id'];
        $area_id = $row['area_id'];
        $shop_status = $row['shop_status'];
        $openTime = strtotime($row['open_time']);
        $closeTime = strtotime($row['close_time']);

        $sql = "SELECT * FROM city WHERE city_id='$city_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['city_name'] = $row['city_name'];

        $sql = "SELECT * FROM area WHERE login_id='$area_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['area_name'] = $row['area_name'];

        $rating = 0;

        $sql = "SELECT * FROM order_rating WHERE shop_id='$login_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $rating += $row['rating_value'];
            }
        }

        $out['shop_rating'] = $rating;
        $currentTime = strtotime(date('h:i A'));

        if($shop_status == 1){
            if(($openTime <= $currentTime) && ($closeTime >= $currentTime)){
                $out['shop_status'] = 1;
                $out['shop_message'] = 'Available';
            } else{
                $out['shop_status'] = 0;
                $out['shop_message'] = 'Shop opens at '.date('h:i A', $openTime);
            }
        } else{
            $out['shop_status'] = 0;
            $out['shop_message'] = 'Shop closed';
        }

        $out['shop_coupon'] = [];
        return $out;
    }
?>
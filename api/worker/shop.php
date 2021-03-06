<?php
    include("city.php");
    include("area.php");
    include("coupon.php");
    function getShop($conn,$login_id,$user_id = 0,$km = 0){
        $out = array();
        $currentTime = strtotime(date('h:i A'));

        $out['shop_id'] = (int)$login_id;

        $sql = "SELECT * FROM login WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        $out['shop_name'] = $row['login_name'];

        $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $city_id = $row['city_id'];
        $area_id = $row['area_id'];
        $shop_status = $row['shop_status'];
        $openTime = strtotime($row['open_time']);
        $closeTime = strtotime($row['close_time']);

        $out['shop_image'] = $row['shop_image'];
        $out['shop_address'] = $row['shop_address'];
        $out['shop_description'] = $row['shop_description'];
        $out['shop_brand'] = (int)$row['top_brand'];
        $out['shop_license'] = $row['license'];
        $out['shop_for_two'] = (int)$row['for_two'];
        $out['shop_preparation_time'] = (int)$row['preparation_time'];
        $out['shop_distance'] = $km.' Km';
        $out['area'] = getArea($conn,$area_id);
        $out['city'] = getCity($conn,$city_id);
        $out['shop_rating'] = getShopRating($conn,$login_id);
        $out['shop_recommended'] = getShopRecommended($conn,$login_id);
        $out['shop_combo'] = getShopCombo($conn,$login_id);
        $out['shop_coupon'] = getShopCoupon($conn,$login_id,$city_id,$user_id);

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
        return $out;
    }

    function getShopCoupon($conn,$login_id,$city_id,$user_id){
        $out = array();
        $order_count = 0;

        if($user_id){
            $sql1 = "SELECT * FROM orders WHERE user_id='$user_id' AND order_status>0";
            $result1 = $conn->query($sql1);
            $order_count = $result1->num_rows;
        }

        $i = 0;

        $sql = "SELECT * FROM offer_details WHERE login_id='$login_id' OR login_id=0";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            if($row['city_id'] == $city_id){
                $offer_id = $row['offer_id'];
                $check = 0;

                $sql1 = "SELECT * FROM offer WHERE offer_id='$offer_id'";
                $result1 = $conn->query($sql1);
                $row1 = $result1->fetch_assoc();

                if($row1['first_order_limit']){
                    if($row1['first_order_limit'] >= $order_count){
                        $check = 1;
                    }
                } else{
                    $check = 1;
                }

                if($check){
                    $out[$i] = getCoupon($conn,$offer_id);

                    $i++;
                }
            }
        }
        
        return $out;
    }

    function getShopTiming($conn,$login_id){
        $out = array();

        $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $currentTime = strtotime(date('h:i A'));

        $openTime = strtotime($row['open_time']);
        $closeTime = strtotime($row['close_time']);

        if(($openTime <= $currentTime) && ($closeTime >= $currentTime)){
            $out['shop_status'] = TRUE;
        } else{
            $out['shop_status'] = FALSE;
        }

        return $out;
    }

    function getShopCombo($conn,$login_id){
        $shop_combo = 0;

        $sql = "SELECT * FROM combos WHERE login_id='$login_id' AND combo_status=1";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $shop_combo = 1;
        }

        return (int)$shop_combo;
    }

    function getShopRecommended($conn,$login_id){
        $product_recommended = 0;

        $sql = "SELECT * FROM product WHERE login_id='$login_id' AND product_recommended=1";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $product_recommended = 1;
        }

        return (int)$product_recommended;
    }

    function getShopRating($conn,$login_id){
        $rating = 0;

        $sql = "SELECT * FROM order_rating WHERE shop_id='$login_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $rating += $row['rating_value'];
            }
        }

        return (float)$rating;
    }
?>
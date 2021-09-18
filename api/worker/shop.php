<?php
    function getShop($conn,$login_id){
        $out = array();

        $out['shop_id'] = $login_id;

        $sql = "SELECT * FROM login WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        $out['shop_name'] = $row['login_name'];

        $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['shop_address'] = $row['shop_address'];

        $city_id = $row['city_id'];
        $area_id = $row['area_id'];

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
        $out['time'] = date('h:i A');

        return $out;
    }
?>
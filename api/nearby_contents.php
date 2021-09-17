<?php
    function getCity($conn,$latitude,$longitude){
        $city = array();
        $city_id = 0;

        $sql = "SELECT * FROM city ORDER BY city_id DESC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            $city_latitude = $row['city_latitude'];
            $city_longitude = $row['city_longitude'];

            $city[$row['city_id']] = getDistance($city_latitude,$city_longitude,$latitude,$longitude);
        }
        asort($city);

        $i = 0;
        foreach($city AS $city_id_key => $km){
            if($i == 0){
                $city_id = $city_id_key;
            }
            $i++;
        }
        return $city_id;
    }
?>
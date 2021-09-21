<?php
    function getCity($conn,$city_id){
        $out = array();

        $sql = "SELECT * FROM city WHERE city_id='$city_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['city_id'] = (int)$row['city_id'];
        $out['city_name'] = $row['city_name'];
        $out['city_status'] = (int)$row['city_status'];

        return $out;
    }
?>
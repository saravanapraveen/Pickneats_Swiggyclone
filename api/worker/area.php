<?php
    function getArea($conn,$login_id){
        $out = array();

        $sql = "SELECT * FROM login WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['area_id'] = (int)$row['login_id'];
        $out['area_controller_name'] = $row['login_name'];
        $out['area_controller_phone_number'] = $row['login_phone_number'];

        $sql = "SELECT * FROM area WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['area_name'] = $row['area_name'];
        $out['area_control_radius'] = (float)$row['control_radius'];

        return $out;
    }
?>
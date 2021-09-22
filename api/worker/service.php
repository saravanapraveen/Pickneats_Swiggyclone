<?php
    function getService($conn,$service_id){
        $out = array();

        $sql = "SELECT * FROM service WHERE service_id='$service_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['service_id'] = (int)$row['service_id'];
        $out['service_name'] = $row['service_name'];
        $out['service_image'] = $row['service_image'];

        return $out;
    }
?>
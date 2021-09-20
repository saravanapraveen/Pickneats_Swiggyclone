<?php
    function getCategory($conn,$category_id){
        $out = array();

        $sql = "SELECT * FROM category WHERE category_id='$category_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $out['category_id'] = (int)$row['category_id'];
        $out['category_name'] = $row['category_name'];
        $out['category_image'] = $row['category_image'];
        $out['category_status'] = (int)$row['category_status'];

        return $out;
    }
?>
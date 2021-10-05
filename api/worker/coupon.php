<?php
    function getCoupon($conn,$coupon_id){
        $out = array();

        $sql = "SELECT * FROM offer WHERE offer_id='$coupon_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        $out['coupon_id'] = (int)$coupon_id;
        $out['coupon_code'] = $row['combo_name'];
        $out['minimum_ordering_amount'] = (int)$row['minimum_order_amount'];
        $out['percentage'] = (int)$row['percentage'];
        $out['maximum_discount_amount'] = (int)$row['maximum_discount_amount'];
        $out['flat_amount'] = (int)$row['flat_amount'];
        $out['offer_coupon_description'] = $row['terms'];

        return $out;
    }
?>
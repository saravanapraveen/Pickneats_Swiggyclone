<?php
    include("product.php");
    function getCombo($conn,$combo_id,$product_id = 0){
        $out = $products = array();

        $sql = "SELECT * FROM combos WHERE combo_id='$combo_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        $out['combo_id'] = (int)$combo_id;
        $out['combo_name'] = $row['combo_name'];
        $out['combo_image'] = $row['combo_image'];
        $out['combo_price'] = (int)$row['combo_price'];

        $sql = "SELECT * FROM combo_products WHERE combo_id='$combo_id'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $currentProduct = $row['combo_product_id'];

            if($product_id != $currentProduct){
                $products[$i] = getProduct($conn,$currentProduct);

                $i++;
            }
        }

        $out['combo_products'] = $products;

        return $out;
    }
?>
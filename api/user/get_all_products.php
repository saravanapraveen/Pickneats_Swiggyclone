<?php
    include("../../controlpanel/include/connection.php");
    
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    $user_id = $data->user_id;

    $sql = "SELECT * FROM product WHERE product_status='1' ORDER BY product_name ASC";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $i = 0;
        while($row = $result->fetch_assoc()){
            $product_id = $row['product_id'];
            $product_percentage = $row['percentage'];
            $category_id = $row['category_id'];
            $fav = 0;
            $price_Array = $product_addon = $product_timing = array();

            $sql1 = "SELECT * FROM product_variation WHERE product_id='$product_id'";
            $result1 = $conn->query($sql1);
            $j = 0;
            while($row1 = $result1->fetch_assoc()){
                $unit_id = $row1['product_variation_unit_id'];
                $unit = '';

                $sql2 = "SELECT * FROM unit WHERE unit_id='$unit_id'";
                $result2 = $conn->query($sql2);
                if($result2->num_rows > 0){
                    $row2 = $result2->fetch_assoc();   

                    $unit = $row2['unit_name'];
                }

                $price_Array[$j]['product_variation_id'] = $row1['product_variation_id'];
                $price_Array[$j]['product_variation_name'] = $row1['product_variation_name'];
                $price_Array[$j]['product_unit'] = $unit;
                $price_Array[$j]['product_price'] = round($row1['variation_selling_amt']);
                $j++;
            }

            $sql1 = "SELECT * FROM product_addon WHERE product_id='$product_id'";
            $result1 = $conn->query($sql1);
            $j = 0;
            while($row1 = $result1->fetch_assoc()){
                $product_addon_id = $row1['addon_id'];
                $addon = '';

                $sql2 = "SELECT * FROM addon WHERE addon_id='$product_addon_id'";
                $result2 = $conn->query($sql2);
                if($result2->num_rows > 0){
                    $row2 = $result2->fetch_assoc();   

                    $addon = $row2['addon_name'];
                }

                $product_addon[$j]['product_addon_id'] = $row1['product_addon_id'];
                $product_addon[$j]['addon_id'] = $row2['addon_id'];
                $product_addon[$j]['addon_name'] = $addon;
                $product_addon[$j]['addon_price'] = $row2['addon_price'];
                $j++;
            }

            $sql1 = "SELECT * FROM product_timing WHERE product_id='$product_id'";
            $result1 = $conn->query($sql1);
            $j = 0;
            while($row1 = $result1->fetch_assoc()){
                $timing_id = $row1['timing_id'];

                $sql2 = "SELECT * FROM timing WHERE timing_id='$timing_id'";
                $result2 = $conn->query($sql2);
                if($result2->num_rows > 0){
                    $row2 = $result2->fetch_assoc();   

                    $timing_name = $row2['timing_name'];
                }

                $product_timing[$j]['product_timing_id'] = $row1['product_timing_id'];
                $product_timing[$j]['timing_id'] = $row2['timing_id'];
                $product_timing[$j]['timing_name'] = $timing_name;
                $product_timing[$j]['intime'] = $row2['intime'];
                $product_timing[$j]['outtime'] = $row2['outtime'];
                $j++;
            }

            // if($user_id != ''){
            //     $sql2 = "SELECT * FROM favorite WHERE user_id='$user_id' AND product_id='$product_id'";
            //     $result2 = $conn->query($sql2);
            //     if($result2->num_rows > 0){
            //         $fav = 1;
            //     }
            // }

            $sql1 = "SELECT * FROM category WHERE category_id='$category_id'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            $output_array['GTS'][$i]['product_id'] = $row['product_id'];
            $output_array['GTS'][$i]['product_name'] = $row['product_name'];
            $output_array['GTS'][$i]['product_image'] = $row['product_image'];
            $output_array['GTS'][$i]['category_name'] = $row1['category_name'];
            $output_array['GTS'][$i]['product_price'] = $price_Array;
            $output_array['GTS'][$i]['product_addon'] = $product_addon;
            $output_array['GTS'][$i]['product_timing'] = $product_timing;
            // $output_array['GTS'][$i]['product_recommended'] = $row['product_recommended'];
            $output_array['GTS'][$i]['product_percentage'] = $row['percentage'];
            // $output_array['GTS'][$i]['is_favourite'] = $fav;
            $output_array['GTS'][$i]['product_type'] = $row['product_type'];
            // $output_array['GTS'][$i]['eggless'] = $row['eggless'];
            // $output_array['GTS'][$i]['product_offer'] = $row['product_offer'];
            $i++;
        }
        http_response_code(200);
        $output_array['status'] = 'success';
        $output_array['message'] = 'Ok';
    } else{
        http_response_code(404);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'No products found';
    }

    echo json_encode($output_array);
?>
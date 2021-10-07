<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/shop.php");
    include("../worker/product.php");
    include("../fcm.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id) && !empty($data->hash_id) && !empty($data->payment_method)){
        $user_id = $data->user_id;
        $hash_id = $data->hash_id;
        $payment_method = $data->payment_method;
        $no_contact_deliery = $data->no_contact_deliery;
        $instructions = $data->instructions;
        $os = $data->from;

        if($payment_method != 1){
            $order_status = 1;
            $payment_status = 1;
        } else{
            $order_status = 2;
            $payment_status = 2;
        }
        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows){
            $row = $result->fetch_assoc();

            $user_fcm_token = $row['user_fcm_token'];
            $user_name = $row['user_name'];

            $sql = "SELECT * FROM cart_history WHERE user_id='$user_id' AND cart_history_id='$hash_id'";
            $result = $conn->query($sql);
            if($result->num_rows){
                $row = $result->fetch_assoc();

                $rawData = $row['cart_data'];
                $processedData = json_decode($rawData);

                $productArray = $processedData->products;
                $comboArray = $processedData->combos;
                $shop_id = $processedData->shop_id;
                $user_address_id = $processedData->address_id;
                $delivery_type = $processedData->delivery_type;
                $product_total = $processedData->product_total;
                $addon_total = $processedData->addon_total;
                $total_amount = $processedData->order_total;
                $coupon_id = $processedData->coupon_id;
                $coupon_discount = $processedData->coupon_discount;
                $delivery_charge = $processedData->delivery_charge;
                $packing_charge = $processedData->packing_charge;
                $tax_amount = $processedData->tax_amount;
                $tip_amount = $processedData->tip_amount;

                $sql = "SELECT * FROM orders ORDER BY order_id DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $existingOrderId = $row['order_id'] + 1;

                $order_string = 'PIC'.date('YmdHi').$existingOrderId;

                $sql = "SELECT * FROM shop WHERE login_id='$shop_id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $city_id = $row['city_id'];
                $area_id = $row['area_id'];

                $user_address_details = $user_latitude = $user_longitude = '';

                if($delivery_type){
                    $sql = "SELECT * FROM user_address WHERE user_address_id='$user_address_id'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    $user_address_details = $row['user_address_details'].' '.$row['user_address_landmark'];
                    $user_latitude = $row['user_address_latitude'];
                    $user_longitude = $row['user_address_longitude'];
                }

                $order_date = date('Y-m-d');
                $order_time = date('H:i:s');

                $sql = "INSERT INTO orders (order_string,user_id,shop_id,city_id,area_id,user_address_id,user_address_details,user_latitude,user_longitude,order_date,order_time,delivery_type,total_amount,product_total,addon_total,delivery_charge,packing_charge,tax_amount,tip_amount,offer_id,offer_amount,order_status,payment_status,payment_method,no_contact_deliery,instructions,os,hash) VALUES ('$order_string','$user_id','$shop_id','$city_id','$area_id','$user_address_id','$user_address_details','$user_latitude','$user_longitude','$order_date','$order_time','$delivery_type','$total_amount','$product_total','$addon_total','$delivery_charge','$packing_charge','$tax_amount','$tip_amount','$offer_id','$offer_amount','$order_status','$payment_status','$payment_method','$no_contact_deliery','$instructions','$os','$rawData')";
                if($conn->query($sql) === TRUE){
                    $check = 1;
                    $sql = "SELECT * FROM orders WHERE order_string='$order_string'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    $order_id = $row['order_id'];

                    if(count($productArray)){
                        for($i=0;$i<count($productArray);$i++){
                            $product_id = $productArray[$i]->id;
                            $variation_id = $productArray[$i]->variation_id;
                            $quantity = $productArray[$i]->quantity;
                            $addonArray = $productArray[$i]->selected_addons;

                            $sql = "SELECT * FROM product WHERE product_id='$product_id'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();

                            $product_name = mysqli_real_escape_string($conn,$row['product_name']);

                            $sql = "SELECT * FROM product_variation WHERE product_variation_id='$variation_id'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();

                            $product_variation_unit_id = $row['product_variation_unit_id'];

                            $sql1 = "SELECT * FROM unit WHERE unit_id='$product_variation_unit_id'";
                            $result1 = $conn->query($sql1);
                            $row1 = $result1->fetch_assoc();

                            $product_variation_name = $row['product_variation_name'].' '.$row1['unit_name'];
                            $product_price = $row['variation_selling_amt'];

                            $addon_id = $addon_name = $addon_price = '';
                            if(count($addonArray)){
                                for($j=0;$j<count($addonArray);$j++){
                                    $addon = $addonArray[$j]->addon_id;
                                    $addon_id .= $addon.',';
    
                                    $sql = "SELECT * FROM addon WHERE addon_id='$addon'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
    
                                    $addon_name .= $row['addon_name'].',';
                                    $addon_price .= $row['addon_price'].',';
                                }

                                $addon_id = rtrim($addon_id, ',');
                                $addon_name = rtrim($addon_name, ',');
                                $addon_price = rtrim($addon_price, ',');
                            }

                            $sql = "INSERT INTO order_detail (order_id,product_id,variation_id,name,product_variation_name,product_price,quantity,addon_id,addon_name,addon_price) VALUES ('$order_id','$product_id','$variation_id','$product_name','$product_variation_name','$product_price','$quantity','$addon_id','$addon_name','$addon_price')";
                            if($conn->query($sql) !== TRUE){
                                $check = 0;
                            }
                        }
                    }

                    if(count($comboArray)){
                        for($i=0;$i<count($comboArray);$i++){
                            $combo_id = $productArray[$i]->combo_id;
                            $quantity = $productArray[$i]->quantity;

                            $sql = "SELECT * FROM combos WHERE combo_id='$combo_id'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();

                            $combo_name = mysqli_real_escape_string($conn,$row['combo_name']);
                            $combo_price = $row['combo_price'];

                            $sql = "INSERT INTO order_detail (order_id,combo_id,name,product_price,quantity) VALUES ('$order_id','$combo_id','$combo_name','$combo_price','$quantity')";
                            if($conn->query($sql) !== TRUE){
                                $check = 0;
                            }
                        }
                    }

                    if($check){
                        if($payment_method == 1){
                            $notificationData['title'] = 'Hi '.$user_name;
                            $notificationData['body'] = 'Your order has been placed';
                            $notificationData['type'] = 1;
                            $notificationData['order_id'] = (int)$order_id;
                            $notificationData['order_status'] = (int)$order_status;

                            $responce = send_fcm($conn,$user_fcm_token,$notificationData);
                            $output['order_id'] = (int)$order_id;
                        } else{
                            $output['order_id'] = (int)$order_id;
                        }
                    } else{
                        $sql = "DELETE FROM orders WHERE order_id='$order_id'";
                        if($conn->query($sql) === TRUE){
                            http_response_code(500);
                        }
                    }
                }
            } else{
                http_response_code(404);
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>
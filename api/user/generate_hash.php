<?php
    include("../../controlpanel/include/connection.php");
    include("../distance_calculator.php");
    include("../worker/shop.php");
    include("../worker/product.php");
    $output = array();

    $generatedData = file_get_contents('php://input');
    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id) && !empty($data->shop_id)){
        $user_id = $data->user_id;
        $login_id = $data->shop_id;
        $user_address_id = $data->address_id;
        $productArray = $data->products;
        $comboArray = $data->combos;
        $offer_id = $data->coupon_id;
        $coupon_amount = $data->coupon_discount;
        $tip_amount = $data->tip_amount;
        $product_total = $data->product_total;
        $order_total = $data->order_total;
        $delivery_type = $data->delivery_type;

        $total = 0;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM shop WHERE login_id='$login_id' AND shop_status=1";
            $result = $conn->query($sql);
            if($result->num_rows){
                $row = $result->fetch_assoc();

                $packing_charge = $row['packing_charge'];
                $tax = $row['tax'];

                $shop_latitude = $row['latitude'];
                $shop_longitude = $row['longitude'];
                $delivery_charge = $row['delivery_charge'];
                $increment = $row['increment'];
                $minimum_distance = $row['minimum_distance'];

                if(getShopTiming($conn,$login_id)['shop_status']){
                    $productChecker = $comboChecker = $offerChecker = 1;
                    $productMsg = $comboMsg = '';

                    if(count($productArray)){
                        foreach($productArray AS $products){
                            if($productChecker){
                                $product_id = $products->id;
                                $product_variation_id = $products->variation_id;
                                $quantity = $products->quantity;
                                $addonArray = $products->selected_addons;

                                $sql = "SELECT * FROM product WHERE product_id='$product_id' AND product_status=1 AND login_id='$login_id'";
                                $result = $conn->query($sql);
                                if($result->num_rows){
                                    $sql = "SELECT * FROM product_variation WHERE product_variation_id='$product_variation_id' AND product_id='$product_id'";
                                    $result = $conn->query($sql);
                                    if($result->num_rows){
                                        $row = $result->fetch_assoc();

                                        $total += ($quantity * $row['variation_selling_amt']);

                                        if(count($addonArray)){
                                            foreach($addonArray AS $addons){
                                                if($productChecker){
                                                    $addon_id = $addons->addon_id;

                                                    $sql = "SELECT * FROM product_addon WHERE addon_id='$addon_id' AND product_id='$product_id'";
                                                    $result = $conn->query($sql);
                                                    if($result->num_rows){
                                                        $sql = "SELECT * FROM addon WHERE addon_id='$addon_id' AND addon_status=1";
                                                        $result = $conn->query($sql);
                                                        if($result->num_rows){
                                                            $row = $result->fetch_assoc();

                                                            $total += ($quantity * $row['addon_price']);
                                                        } else{
                                                            $productChecker = 0;
                                                            $productMsg = 'Addon not available';
                                                        }
                                                    } else{
                                                        $productChecker = 0;
                                                        $productMsg = 'Invalid Addon';
                                                    }
                                                }
                                            }
                                        }
                                    } else{
                                        $productChecker = 0;
                                        $productMsg = 'Mismatch price';
                                    }
                                } else{
                                    $productChecker = 0;
                                    $productMsg = 'Product not available';
                                }
                            }
                        }
                    }

                    if($productChecker){
                        if(count($comboArray)){
                            foreach($comboArray AS $combos){
                                if($comboChecker){
                                    $combo_id = $combos->combo_id;
                                    $quantity = $combos->quantity;

                                    $sql = "SELECT * FROM combos WHERE login_id='$login_id' AND combo_id='$combo_id' AND combo_status=1";
                                    $result = $conn->query($sql);
                                    if($result->num_rows){
                                        $row = $result->fetch_assoc();

                                        $total += ($row['combo_price'] * $quantity);
                                    } else{
                                        $comboChecker = 0;
                                        $comboMsg = 'Combo not available';
                                    }
                                }
                            }
                        }

                        if($total == $product_total){
                            if($comboChecker){
                                $coupon_amount_check = 0;
                                if($offer_id){
                                    $sql = "SELECT * FROM offer WHERE offer_id='$offer_id'";
                                    $result = $conn->query($sql);
                                    if($result->num_rows > 0){
                                        $row = $result->fetch_assoc();

                                        if($total >= $row['minimum_order_amount']){
                                            if($row['flat_amount']){
                                                $coupon_amount_check = $row['flat_amount'];
                                            } else{
                                                $coupon_amount_check = $total * $row['percentage'] / 100;

                                                if($coupon_amount_check > $row['maximum_discount_amount']){
                                                    $coupon_amount_check = $row['maximum_discount_amount'];
                                                }
                                            }
                                        } else{
                                            $offerChecker = 0;
                                        }
                                    } else{
                                        $offerChecker = 0;
                                    }
                                }

                                if($offerChecker){
                                    if($coupon_amount == $coupon_amount_check){
                                        $total = round(($total * $tax / 100) + $total);
                                        $DistanceCheck = 0;

                                        if($delivery_type){
                                            $sql = "SELECT * FROM user_address WHERE user_id='$user_id' AND user_address_id='$user_address_id'";
                                            $result = $conn->query($sql);
                                            if($result->num_rows){
                                                $row = $result->fetch_assoc();

                                                $latitude = $row['user_address_latitude'];
                                                $longitude = $row['user_address_longitude'];

                                                $sql = "SELECT * FROM app_control";
                                                $result = $conn->query($sql);
                                                $row = $result->fetch_assoc();

                                                $maximum_distance = $row['maximum_distance'];

                                                $distance = getDistance($latitude,$longitude,$shop_latitude,$shop_longitude);
                                                
                                                if($distance <= $maximum_distance){
                                                    $DistanceCheck = 1;
                                                    if($distance <= $minimum_distance){
                                                        $delivery_charge = (int)$delivery_charge;
                                                    }else{
                                                        $extra_distance = $distance - $minimum_distance;
                                                        $delivery_charge = (int)round(($extra_distance * $increment) + $delivery_charge);
                                                    }
                                                } else{
                                                    http_response_code(403);
                                                    $output['message'] = 'Service not Available for this location';
                                                }
                                            } else{
                                                http_response_code(401);
                                                $output['message'] = 'User address not found';
                                            }
                                        } else{
                                            $DistanceCheck = 1;
                                        }

                                        if($DistanceCheck){
                                            $total += $delivery_charge + $packing_charge + $tip_amount;

                                            if($total == $order_total){
                                                $sql = "INSERT INTO cart_history (user_id,cart_data) VALUES ('$user_id','$generatedData')";
                                                if($conn->query($sql) === TRUE){
                                                    $sql = "SELECT * FROM cart_history WHERE user_id='$user_id' ORDER BY cart_history_id DESC LIMIT 1";
                                                    $result = $conn->query($sql);
                                                    if($result->num_rows){
                                                        $row = $result->fetch_assoc();

                                                        $output['hash_id'] = (int)$row['cart_history_id'];
                                                        $output['total_amount'] = $total;
                                                    } else{
                                                        $output['total_amount'] = $sql;
                                                    }
                                                }
                                            } else{
                                                http_response_code(403);

                                                $output['message'] = 'Invalid Total';
                                            }
                                        }
                                    } else{
                                        http_response_code(401);
                                        $output['d'] = $coupon_amount_check;
                                        $output['message'] = 'Invalid Coupon Amount';
                                    }
                                } else{
                                    http_response_code(401);
                                    $output['message'] = 'Invalid Coupon';
                                }
                            } else{
                                http_response_code(401);
                                $output['message'] = $comboMsg;
                            }
                        } else{
                            http_response_code(401);
                            $output['t'] = $total;
                            $output['message'] = 'Invalid Product total';
                        }
                    } else{
                        http_response_code(401);
                        $output['message'] = $productMsg;
                    }
                } else{
                    http_response_code(401);
                    $output['message'] = 'Shop turned off';
                }
            } else{
                http_response_code(401);
                $output['message'] = 'Shop turned off';
            }
        } else{
            http_response_code(401);
            $output['message'] = 'User not found';
        }
    } else{
        http_response_code(400);
        $output['message'] = 'Bad request';
    }

    if(count($output)){
        echo json_encode($output);
    }
?>
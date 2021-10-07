<?php
    include("../../controlpanel/include/connection.php");

    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id) && !empty($data->order_id)){
        $user_id = $data->user_id;
        $order_id = $data->order_id;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows){
            $sql = "SELECT * FROM orders WHERE user_id='$user_id' AND order_id='$order_id'";
            $result = $conn->query($sql);
            if($result->num_rows){
                $row = $result->fetch_assoc();

                $shop_id = $row['shop_id'];
                $offer_id = $row['offer_id'];
                $payment_mode_id = $row['payment_method'];

                $output['GTS']['order_id'] = $order_id;
                $output['GTS']['order_string'] = $row['order_string'];
                $output['GTS']['order_string'] = $row['order_string'];
                $output['GTS']['order_date'] = date('d-m-Y', strtotime($row['order_date']));
                $output['GTS']['order_time'] = strtotime($row['order_time']);
                $output['GTS']['order_status'] = (int)$row['order_status'];
                $output['GTS']['payment_status'] = (int)$row['payment_status'];
                $output['GTS']['delivery_type'] = (int)$row['delivery_type'];
                $output['GTS']['total_amount'] = (int)$row['total_amount'];
                $output['GTS']['product_total'] = (int)$row['product_total'];
                $output['GTS']['addon_total'] = (int)$row['addon_total'];
                $output['GTS']['delivery_charge'] = (int)$row['delivery_charge'];
                $output['GTS']['packing_charge'] = (int)$row['packing_charge'];
                $output['GTS']['tax_amount'] = (int)$row['tax_amount'];
                $output['GTS']['tip_amount'] = (int)$row['tip_amount'];
                $output['GTS']['no_contact_deliery'] = (int)$row['no_contact_deliery'];
                $output['GTS']['instructions'] = $row['instructions'];

                $sql1 = "SELECT * FROM payment_mode WHERE payment_mode_id='$payment_mode_id'";
                $result1 = $conn->query($sql1);
                $row1 = $result1->fetch_assoc();

                $output['GTS']['payment_mode']['payment_mode_id'] = (int)$payment_mode_id;
                $output['GTS']['payment_mode']['payment_mode_name'] = $row1['payment_mode_name'];

                if($offer_id){
                    $sql1 = "SELECT * FROM offer WHERE offer_id='$offer_id'";
                    $result1 = $conn->query($sql1);
                    if($result1->num_rows){
                        $row1 = $result1->fetch_assoc();

                        $output['GTS']['offer']['offer_id'] = (int)$offer_id;
                        $output['GTS']['offer']['offer_coupon_code'] = $row1['offer_coupon_code'];
                        $output['GTS']['offer']['offer_amount'] = (int)$row['offer_amount'];
                    } else{
                        $output['GTS']['offer'] = NULL;
                    }
                } else{
                    $output['GTS']['offer'] = NULL;
                }

                $output['GTS']['user']['user_address_id'] = (int)$row['user_address_id'];
                $output['GTS']['user']['user_address_details'] = $row['user_address_details'];
                $output['GTS']['user']['user_latitude'] = (float)$row['user_latitude'];
                $output['GTS']['user']['user_longitude'] = (float)$row['user_longitude'];

                if($shop_id){
                    $sql1 = "SELECT * FROM login WHERE login_id='$shop_id'";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();

                    $shop_name = $row1['login_name'];

                    $sql1 = "SELECT * FROM shop WHERE login_id='$shop_id'";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();

                    $output['GTS']['shop']['shop_address_id'] = (int)$shop_id;
                    $output['GTS']['shop']['shop_name'] = $shop_name;
                    $output['GTS']['shop']['preparation_time'] = (int)$row1['preparation_time'];
                    $output['GTS']['shop']['shop_description'] = $row1['shop_description'];
                    $output['GTS']['shop']['shop_address'] = $row1['shop_address'];
                }

                $sql1 = "SELECT * FROM order_detail WHERE order_id='$order_id' AND product_id!=0";
                $result1 = $conn->query($sql1);
                if($result1->num_rows){
                    $i = 0;
                    while($row1 = $result1->fetch_assoc()){
                        $output['GTS']['product'][$i]['product_id'] = (int)$row1['product_id'];
                        $output['GTS']['product'][$i]['product_name'] = $row1['name'];
                        $output['GTS']['product'][$i]['product_variation_name'] = $row1['product_variation_name'];
                        $output['GTS']['product'][$i]['product_price'] = (int)$row1['product_price'];
                        $output['GTS']['product'][$i]['quantity'] = (int)$row1['quantity'];
                        
                        $i++;
                    }
                } else{
                    $output['GTS']['product'] = [];
                }

                $sql1 = "SELECT * FROM order_detail WHERE order_id='$order_id' AND combo_id!=0";
                $result1 = $conn->query($sql1);
                if($result1->num_rows){
                    $i = 0;
                    while($row1 = $result1->fetch_assoc()){
                        $combo_id = $row1['combo_id'];
                        $products = array();
    
                        $sql2 = "SELECT * FROM combo_products WHERE combo_id='$combo_id'";
                        $result2 = $conn->query($sql2);
                        $j = 0;
                        while($row2 = $result2->fetch_assoc()){
                            $currentProduct = $row2['combo_product_id'];
    
                            $sql3 = "SELECT * FROM product WHERE product_id='$currentProduct'";
                            $result3 = $conn->query($sql3);
                            $row3 = $result3->fetch_assoc();
    
                            $products[$j]['name'] = $row3['product_name'];
    
                            $j++;
                        }
    
                        $output['GTS']['combo'][$i]['combo_id'] = (int)$row1['combo_id'];
                        $output['GTS']['combo'][$i]['combo_name'] = $row1['name'];
                        $output['GTS']['combo'][$i]['combo_price'] = (int)$row1['product_price'];
                        $output['GTS']['combo'][$i]['quantity'] = (int)$row1['quantity'];
                        $output['GTS']['combo'][$i]['product'] = $products;
                    }
                } else{
                    $output['GTS']['combo'] = [];
                }

                if($row['delivery_partner_id']){
                    $delivery_partner_id = $row['delivery_partner_id'];

                    $sql1 = "SELECT * FROM login WHERE login_id='$delivery_partner_id'";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();

                    $delivery_partner_name = $row1['login_name'];
                    $delivery_partner_phone_number = $row1['login_phone_number'];

                    $sql1 = "SELECT * FROM delivery_partner WHERE login_id='$delivery_partner_id'";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();

                    $output['GTS']['delivery_partner']['delivery_partner_id'] = (int)$row1['delivery_partner_id'];
                    $output['GTS']['delivery_partner']['name'] = $delivery_partner_name;
                    $output['GTS']['delivery_partner']['phone'] = $delivery_partner_phone_number;
                    $output['GTS']['delivery_partner']['latitude'] = (float)$row1['delivery_partner_latitude'];
                    $output['GTS']['delivery_partner']['longitude'] = (float)$row1['delivery_partner_longitude'];
                }
            } else{
                http_response_code(404);
            }
        } else{
            http_response_code(403);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>
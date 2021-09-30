<?php
    include("service.php");
    include("category.php");
    function getProduct($conn,$product_id){
        $out = array();

        $sql = "SELECT * FROM product WHERE product_id='$product_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $login_id = $row['login_id'];

        $sql1 = "SELECT * FROM login WHERE login_id='$login_id'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();

        $out['product_id'] = (int)$product_id;
        $out['product_name'] = $row['product_name'];
        $out['product_image'] = $row['product_image'];
        $out['product_type'] = (int)$row['product_type'];
        $out['product_description'] = $row['product_description'];
        $out['product_recommended'] = (int)$row['product_recommended'];
        $out['service'] = getService($conn,$row['service_id']);
        $out['category'] = getCategory($conn,$row['category_id']);
        $out['shop_id'] = (int)$row['login_id'];
        $out['shop_name'] = $row1['login_name'];
        $out['product_price'] = getProductVariation($conn,$product_id);
        $out['product_addon'] = getProductAddon($conn,$product_id);

        if($row['product_status'] == 1){
            $timingCheck = getProductTiming($conn,$product_id);

            $out['product_status'] = $timingCheck['status'];
            $out['product_message'] = $timingCheck['message'];
        } else{
            $out['product_status'] = 0;
            $out['product_message'] = 'Out of Stock';
        }

        return $out;
    }

    function getProductVariation($conn,$product_id){
        $out = array();

        $sql = "SELECT * FROM product_variation WHERE product_id='$product_id'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $unit_id = $row['product_variation_unit_id'];

            $sql1 = "SELECT * FROM unit WHERE unit_id='$unit_id'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            $out[$i]['product_variation_id'] = (int)$row['product_variation_id'];
            $out[$i]['product_variation_name'] = $row['product_variation_name'];
            $out[$i]['product_variation_unit_name'] = $row1['unit_name'];
            $out[$i]['product_price'] = (int)$row['variation_selling_amt'];

            $i++;
        }

        return $out;
    }

    function getProductAddon($conn,$product_id){
        $out = array();

        $sql = "SELECT * FROM product_addon WHERE product_id='$product_id'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $addon_id = $row['addon_id'];

            $sql1 = "SELECT * FROM addon WHERE addon_id='$addon_id' AND addon_status=1";
            $result1 = $conn->query($sql1);
            if($result1->num_rows){
                $row1 = $result1->fetch_assoc();

                $out[$i]['addon_id'] = (int)$row1['addon_id'];
                $out[$i]['addon_name'] = $row1['addon_name'];
                $out[$i]['addon_price'] = (int)$row1['addon_price'];

                $i++;
            }
        }

        return $out;
    }

    function getProductTiming($conn,$product_id){
        $out = $timingArray = $inarray = $outarray = array();
        $nowTime = strtotime(date('H:i:s'));

        $sql = "SELECT * FROM product_timing WHERE product_id='$product_id'";
        $result = $conn->query($sql);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $timing_id = $row['timing_id'];

            $sql1 = "SELECT * FROM timing WHERE timing_id='$timing_id'";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();

            $timingArray[$timing_id] = $row1['intime'];
        }

        asort($timingArray);

        foreach($timingArray as $timing_id => $intime){
            $sql = "SELECT * FROM timing WHERE timing_id='$timing_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            array_push($inarray,$row["intime"]);
            array_push($outarray,$row["outtime"]);
        }

        $incount = count($inarray);

        $temp = $incount - 1;

        $available = $finder = 0;

        for($t=0;$t<$incount;$t++){
            $inTime = strtotime($inarray[$t]);
            $outTime = strtotime($outarray[$t]);

            $ta = $t + 1;

            $time1 = $nowTime - $inTime;
            $time2 = $outTime - $nowTime;

            if(($time1 >= 0) && ($time2 >= 0)){
                $available = 1;
            } else{
                if($available != 1 && $finder == 0){
                    if($t == $temp){
                        $finder = $incount;
                    } else{
                        $inTime = strtotime($outarray[$t]);
                        $outTime = strtotime($inarray[$ta]);

                        $time1 = $nowTime - $inTime;
                        $time2 = $outTime - $nowTime;

                        if(($time1 >= 0) && ($time2 >= 0)){
                            $finder = $ta;
                        }
                    }
                }
            }
        }

        if($available != 1){
            if($finder == $incount){
                $date2 = strtotime($inarray['0']);

                $time1 = $date2 - $nowTime;

                if($time1 >= 0){
                    $msg = "Available After ".date('h:i A', strtotime($inarray['0']));
                } else{
                    $msg = "Available After Tomorrow ".date('h:i A', strtotime($inarray['0']));
                }
            } else{
                $msg = "Available After ".date('h:i A', strtotime($inarray[$finder]));
            }
        } else{
            $msg = "Available";
        }

        $out['status'] = $available;
        $out['message'] = $msg;

        return $out;
    }
?>
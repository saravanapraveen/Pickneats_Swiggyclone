<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/combo.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->product_id)){
        $product_id = $data->product_id;

        $sql = "SELECT * FROM product WHERE product_id='$product_id' AND product_status=1";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM combo_products WHERE combo_product_id='$product_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $combo_id = $row['combo_id'];

                    $sql1 = "SELECT * FROM combos WHERE login_id='$login_id' AND combo_status=1";
                    $result1 = $conn->query($sql1);
                    if($result->num_rows > 0){
                        $output['GTS'][$i] = getCombo($conn,$combo_id,$product_id);

                        $i++;
                    }
                }

                if($i == 0){
                    http_response_code(404);
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
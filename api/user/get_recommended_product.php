<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/product.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->shop_id)){
        $shop_id = $data->shop_id;

        $sql = "SELECT * FROM product WHERE login_id='$shop_id' AND product_recommended=1 ORDER BY product_name ASC";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()){
                $product_id = $row['product_id'];
                
                $output['GTS'][$i] = getProduct($conn,$product_id);

                $i++;
            }

            if($i > 0){
                http_response_code(200);
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